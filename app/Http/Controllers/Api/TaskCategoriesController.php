<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskCategoriesController extends Controller
{  
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tasks_id' => 'required',
                'categories_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()], 400);
            }

            // Membuat Kategori Tugas Baru
            $data = TaskCategories::create([
                'tasks_id' => $request->tasks_id,
                'categories_id' => $request->categories_id,
            ]);

            // Data akan dikirimkan dalam bentuk response list
            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'Successfull',
            ];

            // Jika berhasil maka akan mengirimkan status code 200
            return response()->json($response, 200);
        } catch (\Exception $th) {
            $response = [
                'success' => false,
                'message' => 'Failed',
            ];
            // Jika error maka akan mengirimkan status code 500
            return response()->json($response, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $save = TaskCategories::find($id);
            if ($save == null) {
                return response()->json(['success' => false, 'message' => 'Failed'], 404);
            }
            $save->delete();
            $response = [
                'success' => true,
                'message' => 'Successfull',
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Delete Failed: ' . $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }
}
