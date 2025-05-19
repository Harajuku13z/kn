<?php

namespace App\Http\Controllers;

use App\Mail\ContactAdminNotification;
use App\Mail\ContactUserConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Submit contact form
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit(Request $request)
    {
        // Validate form data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Get validated data
        $data = $validator->validated();

        try {
            // Send email to admin
            Mail::to(config('mail.admin_address', 'contact@ezaklinklin.com'))
                ->send(new ContactAdminNotification($data));

            // Send confirmation email to user
            Mail::to($data['email'])
                ->send(new ContactUserConfirmation($data));

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Votre message a été envoyé avec succès!'
            ]);
        } catch (\Exception $e) {
            // Log error
            \Log::error('Contact form error: ' . $e->getMessage());

            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'Une erreur s\'est produite lors de l\'envoi du message.'
            ], 500);
        }
    }
} 