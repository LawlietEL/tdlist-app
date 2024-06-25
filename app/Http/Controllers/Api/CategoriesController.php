<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Categories::all();
            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'Categories available',
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
                'categories_name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()], 400);
            }

            // Membuat Kategori Tugas Baru
            $data = Categories::create([
                'users_id' => $request->users_id,
                'categories_name' => $request->categories_name,
            ]);

            // Data akan dikirimkan dalam bentuk response list
            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'Category added successfully',
            ];

            // Jika berhasil maka akan mengirimkan status code 200
            return response()->json($response, 200);
        } catch (\Exception $th) {
            $response = [
                'success' => false,
                'message' => 'No category added',
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
            $data = Categories::where('id', $id)->first();
            if ($data == null) {
                $response = [
                    'success' => false,
                    'message' => 'Category not found',
                ];
                return response()->json($response, 404);
            }
            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'Category found',
            ];

            return response()->json($response, 200);
        } catch (\Exception $th) {
            $response = [
                'success' => false,
                'message' => 'Category not found',
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
                'categories_name' => 'required',
                
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()], 400);
            }

            $data = Categories::findOrFail($id);
            $data->update($request->all());

            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'Category updated successfully',
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Failed to update category: ' . $e->getMessage(),
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
            $save = Categories::find($id);
            if ($save == null) {
                return response()->json(['success' => false, 'message' => 'Category not found'], 404);
            }
            $save->delete();
            $response = [
                'success' => true,
                'message' => 'Category deleted successfully',
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Failed to delete category: ' . $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }
}
