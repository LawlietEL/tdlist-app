<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Tasks::all();
            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'Task available',
            ];

            return response()->json($response, 200);
        } catch (\Exception $th) {
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'users_id' => 'required',
                'task' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()], 400);
            }

            // Membuat data task baru
            $data = Tasks::create([
                'users_id' => $request->users_id,
                'task' => $request->task,
                'due_date' => $request->due_date,
                'reminder_datetime' => $request->reminder_datetime,
                'is_important' => $request->is_important,
                'is_completed' => $request->is_completed,
            ]);

            // Data akan dikirimkan dalam bentuk response list
            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'Task added successfully',
            ];

            // Jika berhasil maka akan mengirimkan status code 200
            return response()->json($response, 200);
        } catch (\Exception $th) {
            $response = [
                'success' => false,
                'message' => 'No tasks added',
            ];
            // Jika error maka akan mengirimkan status code 500
            return response()->json($response, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Mengambil data berdasarkan id task yang pertama kali ketemu
            $data = Tasks::where('id', $id)->first();
            if ($data == null) {
                $response = [
                    'success' => false,
                    'message' => 'Task not found',
                ];
                return response()->json($response, 404);
            }
            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'Task found',
            ];

            return response()->json($response, 200);
        } catch (\Exception $th) {
            $response = [
                'success' => false,
                'message' => 'Task not found',
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'task' => 'required',
                
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()], 400);
            }

            $data = Tasks::findOrFail($id);
            $data->update($request->all());

            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'Task updated successfully',
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Failed to update task: ' . $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $save = Tasks::find($id);
            if ($save == null) {
                return response()->json(['success' => false, 'message' => 'Task not found'], 404);
            }
            $save->delete();
            $response = [
                'success' => true,
                'message' => 'Task deleted successfully',
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Failed to delete task: ' . $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }
}
