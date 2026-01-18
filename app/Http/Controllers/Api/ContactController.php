<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\SubmissionFile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    /**
     * Store a new contact submission
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string|max:100|min:2',
                'email' => 'required|email:rfc,dns|max:100',
                'phone' => 'nullable|string|max:20|regex:/^[\+]?[0-9\-\s\(\)]+$/',
                'interest' => ['required', Rule::in(['general', 'website_bug', 'business_partnership', 'other'])],
                'message' => 'required|string|max:5000|min:10',
                'consent' => 'required|boolean|accepted',
                'honeypot' => 'nullable|string|max:0', // Should be empty for humans
                'files' => 'nullable|array|max:5',
                'files.*' => 'file|max:5120|mimes:pdf,doc,docx,txt,jpg,jpeg,png,gif,webp' // 5MB max per file
            ], [
                'full_name.required' => 'Full name is required.',
                'full_name.min' => 'Full name must be at least 2 characters.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please provide a valid email address.',
                'phone.regex' => 'Please provide a valid phone number.',
                'interest.required' => 'Please select your interest.',
                'interest.in' => 'Please select a valid interest option.',
                'message.required' => 'Message is required.',
                'message.min' => 'Message must be at least 10 characters.',
                'consent.required' => 'You must agree to the privacy policy.',
                'consent.accepted' => 'You must agree to the privacy policy.',
                'honeypot.max' => 'Invalid submission detected.',
                'files.max' => 'You can upload a maximum of 5 files.',
                'files.*.max' => 'Each file must be smaller than 5MB.',
                'files.*.mimes' => 'Only PDF, Word documents, text files, and images are allowed.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check honeypot (spam protection)
            if (!empty($request->honeypot)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid submission detected.'
                ], 400);
            }

            DB::beginTransaction();

            try {
                // Create the contact submission
                $submission = ContactSubmission::create([
                    'full_name' => trim($request->full_name),
                    'email' => strtolower(trim($request->email)),
                    'phone' => $request->phone ? trim($request->phone) : null,
                    'interest' => $request->interest,
                    'message' => trim($request->message),
                    'consent' => $request->consent,
                    'honeypot' => $request->honeypot,
                    'ip_address' => $request->ip() ?: $request->header('CF-Connecting-IP') ?: $request->header('X-Forwarded-For'),
                    'status' => 'new'
                ]);

                // Handle file uploads if any
                if ($request->hasFile('files')) {
                    $uploadedFiles = [];

                    foreach ($request->file('files') as $file) {
                        if ($file->isValid()) {
                            // Generate unique filename
                            $originalName = $file->getClientOriginalName();
                            $extension = $file->getClientOriginalExtension();
                            $filename = time() . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9\-_\.]/', '', $originalName);

                            // Store file
                            $path = $file->storeAs('contact-files', $filename, 'public');

                            if ($path) {
                                $uploadedFiles[] = [
                                    'submission_id' => $submission->id,
                                    'file_name' => $originalName,
                                    'file_path' => $path,
                                    'file_type' => $file->getMimeType(),
                                    'file_size' => $file->getSize(),
                                    'created_at' => now()
                                ];
                            }
                        }
                    }

                    // Bulk insert files if any were uploaded successfully
                    if (!empty($uploadedFiles)) {
                        SubmissionFile::insert($uploadedFiles);
                    }
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Thank you for your message! We\'ll get back to you soon.',
                    'data' => [
                        'submission_id' => $submission->id,
                        'status' => $submission->status
                    ]
                ], 201);

            } catch (\Exception $e) {
                DB::rollBack();

                // Clean up any uploaded files if transaction failed
                if (isset($uploadedFiles)) {
                    foreach ($uploadedFiles as $fileData) {
                        Storage::disk('public')->delete($fileData['file_path']);
                    }
                }

                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your submission. Please try again later.'
            ], 500);
        }
    }

    /**
     * Get submission status (for tracking)
     */
    public function status(Request $request, $id): JsonResponse
    {
        try {
            $submission = ContactSubmission::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $submission->id,
                    'status' => $submission->status,
                    'created_at' => $submission->created_at->toISOString(),
                    'updated_at' => $submission->updated_at->toISOString()
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Submission not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the submission status.'
            ], 500);
        }
    }
}
