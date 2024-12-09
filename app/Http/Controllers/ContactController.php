<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Mail\Contact as ContactMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        return Inertia::render("Contact", [
            "team" => auth()->user()?->currentTeam,
            "user" => auth()->user()
        ]);
    }

    public function create(Request $request) {
        if (Auth::check()) {
            $validated = $request->validate([
                'phone' => ['required', 'max:50', 'min:10'],
                'subject' => ['required', 'max:50', 'string'],
                'message' => ['required'],
            ]);
            $validated['name'] = Auth::user()->name;
            $validated['email'] = Auth::user()->email;
            $validated['company'] = Auth::user()->currentTeam->name;
            Contact::create($validated);
        } else {
            $validated = $request->validate([
                'name' => ['required', 'max:50', 'string'],
                'email' => ['required', 'max:50', 'email'],
                'company' => ['max:50', 'string'],
                'phone' => ['required', 'max:50', 'min:10'],
                'subject' => ['required', 'max:50', 'string'],
                'message' => ['required'],
            ]);
        }
        Contact::create($validated);

        Mail::to(User::first()->email)
            ->send(new ContactMail(
                name:  $validated['name'],
                email:  $validated['email'],
                company:  $validated['company'],
                contactSubject:  $validated['subject'],
                phone:  $validated['phone'],
                message:  $validated['message'],
                loggedIn: Auth::check()),
            );

        return response()->json(['success' => true]);
    }
}
