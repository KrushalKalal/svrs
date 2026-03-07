<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Policy;

class PolicyController extends Controller
{
    public function privacy()
    {
        $policy = Policy::where('type', 'privacy')->first();
        return view('admin.privacy', compact('policy'));
    }

    public function term()
    {
        $terms = Policy::where('type', 'terms')->first();
        return view('admin.terms', compact('terms'));
    }

    public function update_privacy(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);

        Policy::updateOrCreate(
            ['type' => 'privacy'],
            ['content' => $request->content]
        );

        return response()->json([
            'status' => true,
            'message' => 'Privacy Policy updated successfully'
        ]);
    }

    public function update_term(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);

        Policy::updateOrCreate(
            ['type' => 'terms'],
            ['content' => $request->content]
        );

        return response()->json([
            'status' => true,
            'message' => 'Terms & Conditions updated successfully'
        ]);
    }
}
