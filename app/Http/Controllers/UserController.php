<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkTime;

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
                'message' => 'Valid ID number not found in the id_number string.'
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

    public function createTask(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'user_ids' => 'required|array',
        ]);

        $startDate = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'creator_id' => auth()->id(),
        ]);

        $task->assignees()->attach($request->user_ids);

        return response()->json(['message' => 'Task created successfully.']);
    }

    public function myTasks()
    {
        $tasks = auth()->user()
            ->assignedTasks()
            ->with('creator')
            ->get();

        return response()->json(['tasks' => $tasks]);
    }

    public function completeTask($taskId)
    {
        $user = auth()->user();

        $user->assignedTasks()->updateExistingPivot($taskId, [
            'is_completed' => true,
        ]);

        return response()->json(['message' => 'Task marked as completed.']);
    }

    public function allUsers()
    {
        $users = \App\Models\User::select('id', 'name')->get();
        return response()->json(['users' => $users]);
    }

    public function startWork()
    {
        $user = Auth::user();

        $openSession = WorkTime::where('user_id', $user->id)->whereNull('end_time')->first();
        if ($openSession) {
            return response()->json(['message' => 'Work session already started', 'work_time' => $openSession]);
        }

        $workTime = WorkTime::create([
            'user_id' => $user->id,
            'start_time' => now(),
        ]);

        return response()->json(['work_time' => $workTime]);
    }

    public function endWork()
    {
        $user = Auth::user();

        $workTime = WorkTime::where('user_id', $user->id)
            ->whereNull('end_time')
            ->latest()
            ->first();

        if (!$workTime) {
            return response()->json(['error' => 'No active work session found'], 404);
        }

        $endTime = now();
        $duration = abs($endTime->diffInSeconds($workTime->start_time));

        $workTime->update([
            'end_time' => $endTime,
            'duration_seconds' => $duration,
        ]);

        return response()->json(['work_time' => $workTime]);
    }

    public function getWorkTimes()
    {
        $user = Auth::user();

        $workTimes = WorkTime::where('user_id', $user->id)
            ->orderBy('start_time', 'desc')
            ->get();

        return response()->json(['work_times' => $workTimes]);
    }
}
