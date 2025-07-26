<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $user,
            'address' => $user->addressName,
            'relative' => [
                'RelativeName' => $user->relative_name,
                'RelativeNumber' => $user->relative_number,
            ],
            'jobTitleName' => $user->jobTitle,
            'id_number' => $user->id_number,
        ]);
    }

    public function storeIdCard(Request $request)
    {
        $validated = $request->validate([
            'id_number' => 'required|string|max:255',
        ]);

        $rawIdNumber = $validated['id_number'];

        preg_match('/\d{11}/', $rawIdNumber, $matches);

        if (empty($matches)) {
            return response()->json([
                'message' => 'Valid TC Kimlik number not found in id_number string.'
            ], 422);
        }

        $idNumber = $matches[0];

        $user = $request->user();
        $user->id_number = $idNumber;
        $user->save();

        return response()->json([
            'message' => 'ID number extracted and saved successfully',
            'id_number' => $idNumber,
            'user' => $user,
        ], 201);
    }


    public function getUserDocuments(Request $request)
    {
        $user = $request->user();

        $documents = $user->documents()->get();

        return response()->json([
            'documents' => $documents,
        ]);
    }


    public function uploadPdfs(Request $request)
    {
        $request->validate([
            'documents.*' => 'required|file|mimes:pdf|max:20480',
        ]);

        $user = $request->user();

        if (!$request->hasFile('documents')) {
            return response()->json(['message' => 'No files uploaded'], 400);
        }

        $files = $request->file('documents');
        $savedDocuments = [];

        foreach ($files as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('user_documents', $filename, 'public');

            $document = $user->documents()->create([
                'file_path' => $path,
                'document_type' => 'pdf',
            ]);

            $savedDocuments[] = $document;
        }

        return response()->json([
            'message' => 'PDF files uploaded successfully',
            'documents' => $savedDocuments,
        ], 201);
    }

}
