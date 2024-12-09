<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        return Inertia::render("Contact");
    }

    public function create(Request $request) {
        Contact::create($request->validate([
            'name' => ['required', 'max:50', 'string'],
            'email' => ['required', 'max:50', 'email'],
            'company' => ['max:50', 'string'],
            'phone' => ['required', 'max:50', 'min:10'],
            'subject' => ['required', 'max:50', 'string'],
            'message' => ['required'],
        ]));

        // Here send email


        return response()->json(['success' => true]);
    }
}
