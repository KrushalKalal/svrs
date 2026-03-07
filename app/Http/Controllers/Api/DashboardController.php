<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard(Request $request) 
    {
        
        $today      = Carbon::today();
        $tomorrow   = Carbon::tomorrow();
        $yesterday  = Carbon::yesterday();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd   = Carbon::now()->endOfMonth();

        $baseQuery = Lead::query()->empScope()->withCount('meetings');

        $meetingCounts = [

            // TODAY
            'today' => (clone $baseQuery)
                ->whereHas('latestMeeting', function ($q) use ($today) {
                    $q->whereDate('next_meeting_date', $today);
                })
                ->count(),

            // TOMORROW
            'tomorrow' => (clone $baseQuery)
                ->whereHas('latestMeeting', function ($q) use ($tomorrow) {
                    $q->whereDate('next_meeting_date', $tomorrow);
                })
                ->count(),

            // Fresh leads
            'fresh_leads' => (clone $baseQuery)
                ->whereDoesntHave('meetings')
                ->count(),

            // WEEKEND (current month)
            'weekend' => (clone $baseQuery)
                ->whereHas('latestMeeting', function ($q) use ($monthStart, $monthEnd) {
                    $q->whereBetween('next_meeting_date', [$monthStart, $monthEnd])
                        ->whereIn(DB::raw('DAYOFWEEK(next_meeting_date)'), [1, 7]);
                })
                ->count(),

            // VISIT DONE
            'visit_done' => (clone $baseQuery)
                ->whereHas('latestMeeting', function ($q) {
                    $q->where('meeting_status', 'visit done');
                })
                ->count(),


            'pending' => (clone $baseQuery)
                ->whereHas('latestMeeting', function ($q) use ($today) {
                    // Latest meeting date is in the past
                    $q->whereDate('next_meeting_date', '<', $today);
                })
                ->whereDoesntHave('meetings', function ($q) {
                    // No meeting exists AFTER the latest meeting
                    $q->whereColumn('meetings.created_at', '>', DB::raw("(SELECT MAX(m2.created_at) FROM meetings m2 WHERE m2.leads_id = meetings.leads_id)"));
                })
                ->count(),
        ];


        return response()->json([
            'status' => true,
            'message' => 'Dashboard data fetched successfully',
            'data' => $meetingCounts
        ]);
    }
    public function dashboard_lead($type)
    {
        $today = Carbon::today();

        $baseQuery = Lead::query()->empScope()->with('latestMeeting')->withCount('meetings');

        switch ($type) {
            case 'visit_done':
                $leadIds = $baseQuery->whereHas('latestMeeting', fn($q) => $q->where('meeting_status', 'visit done'))->pluck('id');
                break;
                
            case 'pending':
                $leadIds = $baseQuery
                    ->whereHas('latestMeeting', function ($q) use ($today) {
                        $q->whereDate('next_meeting_date', '<', $today);
                    })
                    ->whereDoesntHave('meetings', function ($q) {
                        $q->whereColumn('meetings.created_at', '>', DB::raw("(SELECT MAX(m2.created_at) FROM meetings m2 WHERE m2.leads_id = meetings.leads_id)"));
                    })
                    ->pluck('id');
                break;

            case 'today':
                $leadIds = $baseQuery
                    ->whereHas('latestMeeting', fn($q) => $q->whereDate('next_meeting_date', $today))
                    ->pluck('id');
                break;

            case 'tomorrow':
                $leadIds = $baseQuery
                    ->whereHas('latestMeeting', fn($q) => $q->whereDate('next_meeting_date', $today->copy()->addDay()))
                    ->pluck('id');
                break;

            case 'fresh_leads':
                $leadIds = $baseQuery->whereDoesntHave('meetings')->pluck('id');
                break;

            case 'weekend':
                $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
                $endOfMonth   = Carbon::now()->endOfMonth()->toDateString();
                $leadIds = $baseQuery
                    ->whereHas('latestMeeting', function ($q) use ($startOfMonth, $endOfMonth) {
                        $q->whereBetween('next_meeting_date', [$startOfMonth, $endOfMonth])
                            ->whereIn(DB::raw('DAYOFWEEK(next_meeting_date)'), [1, 7]);
                    })
                    ->pluck('id');
                break;

            default:
                $leadIds = collect();
        }

        $leads = Lead::query()->whereIn('id', $leadIds)->with('latestMeeting')->withCount('meetings')->get();
        
        return response()->json([
            'status' => true,
            'message' => $type.' lead fetched successfully',
            'data' => $leads
        ]);
    }
    public function notifications(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),
            'notifications' => $user->notifications()
                ->latest()
                ->take(50)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->data['title'] ?? '',
                        'message' => $notification->data['message'] ?? '',
                        'type' => $notification->type,
                        'read_at' => $notification->read_at,
                        'created_at' => $notification->created_at->toDateTimeString(),
                    ];
                })
        ]);
    }
    public function notifications_read(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }
       
        $notification = $request->user()->notifications()->where('id', $request->id)->firstOrFail();
        $notification->markAsRead();
        $leadId = $notification->data['lead_id'] ?? null;

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
            'lead_id' => $leadId
        ]);
    }
}
