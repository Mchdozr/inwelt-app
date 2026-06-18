<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Support\SiteContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('pages.contact');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|max:2000',
        ]);

        $message = ContactMessage::create($data);

        $notifyTo = SiteContact::email();

        if ($notifyTo) {
            Mail::to($notifyTo)->send(new ContactMessageReceived($message));
        }

        return back()->with('success', 'Mesajınız alındı. En kısa sürede geri dönüş yapacağız.');
    }
}
