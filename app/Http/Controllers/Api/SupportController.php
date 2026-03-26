<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    /**
     * List all support tickets of logged-in user.
     */
    public function index(Request $request)
    {
        $tickets = CustomerSupport::where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'subject' => $t->subject,
                'message' => $t->message,
                'reply' => $t->reply,
                'status' => $t->status,
                'date' => $t->created_at->format('d M Y h:i A'),
            ]);

        return response()->json(['status' => true, 'tickets' => $tickets]);
    }

    /**
     * Create a new support ticket.
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        CustomerSupport::create([
            'user_id' => $request->user()->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 2, // pending
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Support ticket submitted. We will respond shortly.',
        ]);
    }
}