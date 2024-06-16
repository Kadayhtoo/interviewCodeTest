<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserApprovedMail;

class AdminController extends Controller
{
    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_approved' => true]);
        
        $details = [

            'title' => 'Mail from MyProject',
    
            'body' => 'Your account registration is approved by Admin!!'
    
        ];
        // Send approval email
        Mail::to($user->email)->send(new UserApprovedMail($user));

        return response()->json(['message' => 'User approved successfully.']);
    }
}
