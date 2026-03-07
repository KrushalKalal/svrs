<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dropdown;
use App\Models\DropdownField;
use App\Models\Lead;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MeetingController extends Controller
{
    public function createMeetingApi($id)
    {
        $meetingStatus = DropdownField::where('name', 'Meeting Status')->with('dropdowns')->first();

        $meetingStatus = $meetingStatus ? $meetingStatus->dropdowns->pluck('id', 'value') : collect();

        $meetingReason = DropdownField::with('dropdowns')->where('name', 'Meeting Reason')->first()?->dropdowns->pluck('value') ?? collect();

        $leads = Lead::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'leads'           => $leads,
                'meeting_status'  => $meetingStatus,
                'meeting_reason'  => $meetingReason,
            ]
        ]);
    }
    public function storeMeetingApi(Request $request)
    {
        $isMobile = preg_match('/Mobile|Android|iPhone|iPad|iPod/i', $request->header('User-Agent'));
        $platform = $isMobile ? 'mobile' : 'web';

        $deviceIdentifier = $platform === 'web'
            ? $request->ip()
            : ($request->header('X-Device-ID') ?? $request->ip());

        /*--------------------------------
        | Validation
        --------------------------------*/
        $validator = Validator::make($request->all(), [
            'leads_id'           => 'required|exists:leads,id',
            'emp_id'             => 'required|exists:users,id',
            'person_name'        => 'required|string|max:255',
            'mobile_number'      => 'required|digits:10',
            'next_meeting_date'  => 'required|date|after_or_equal:today',
            'next_meeting_time'  => 'required|date_format:H:i',
            'meeting_status'     => 'required|string|max:100',
            'meeting_reason'     => 'nullable|string|max:255',
            'comments'           => 'required|string',
            'latitude'           => 'nullable|string',
            'longitude'          => 'nullable|string',
            'attachment'         => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        /*--------------------------------
        | File Upload
        --------------------------------*/
        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = 'meeting_' . time() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/meetings/';
            $file->move(public_path($path), $filename);
            $attachmentPath = $path . $filename;
        }

        /*--------------------------------
        | Save Meeting
        --------------------------------*/
        $nextMeetingDateTime = \Carbon\Carbon::createFromFormat(
            'Y-m-d H:i',
            $request->next_meeting_date . ' ' . $request->next_meeting_time
        );
        
        $meeting = Meeting::create([
            'leads_id'          => $request->leads_id,
            'emp_id'            => $request->emp_id,
            'person_name'       => $request->person_name,
            'mobile_number'     => $request->mobile_number,
            'next_meeting_date' => $nextMeetingDateTime,
            'meeting_status'    => $request->meeting_status,
            'meeting_reason'    => $request->meeting_reason,
            'comments'          => $request->comments,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'attachment'        => $attachmentPath,
            'created_by'        => $request->emp_id,
            'platform'          => 'Mobile',
            'device_identifier' => $deviceIdentifier,
        ]);

        /*--------------------------------
        | Response
        --------------------------------*/
        return response()->json([
            'success'   => true,
            'message'   => 'Meeting saved successfully!',
            'data'      => [
                'meeting_id' => $meeting->id,
                'lead_id'    => $meeting->leads_id
            ]
        ], 201);
    }
    public function deleteMeetingApi($id)
    {
        Meeting::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Meeting deleted successfully'
        ]);
    }
    public function getMeetingReasons($id)
    {
        if (!$id) {
            return response()->json([
                'status' => false,
                'message' => 'Status is required',
                'data' => []
            ], 422);
        }
    
        $reasons = Dropdown::where('parent_id', $id)->select('id', 'label','value')->get();
    
        return response()->json([
            'status' => true,
            'message' => 'Meeting reasons fetched successfully',
            'data' => $reasons
        ]);
    }
    public function leadMeeting($id)
    {
        $meetings = Meeting::where('leads_id', $id)
            ->orderBy('next_meeting_date', 'desc')
            ->get();
            
        return response()->json([
            'success' => true,
            'message' => 'Meetings fetched successfully',
            'data' => $meetings
        ], 200);
    }
}
