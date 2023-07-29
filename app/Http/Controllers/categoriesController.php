<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json([
                'status' => true,
                'data' => $categories,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'name' => 'required|unique:categories|max:255',
                    'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 400);
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $path = public_path('/images/categories/' . $filename);
                move_uploaded_file($image->getPathname(), $path);
            }

            $c = Category::create([

                'name' => $request->name,
                'image' => '/images/categories/' . $filename
            ]);

            return response()->json([
                'status' => true,
                'message' =>  $request->name . ' created as category',
                'data' => $c

            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $category = Category::find($id);
            if ($id) {
                return response()->json([
                    'status' => true,
                    'data' => $category->load("services", "projects"),
                    //'d'=>$category->services->load("user")->with($category->services->user->profile)
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'category not found'
                ], 500);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        try {
            $category = Category::find($id);
            if ($category) {
                $validateUser = Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:255',
                        'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
                    ]
                );
                if ($validateUser->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'validation error',
                        'errors' => $validateUser->errors()
                    ], 400);
                }
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $filename = time() . '.' . $image->getClientOriginalExtension();
                    $path = public_path('images/categories/' . $filename);
                    if ($category->image) {
                        unlink(public_path($category->image));
                    }
                    move_uploaded_file($image->getPathname(), $path);
                    $category->image = '/images/categories/' . $filename;
                }
                $category->name = $request->name;
                $category->save();
                return response()->json([
                    "success" => true,
                    "message" => "category updated successfully.",
                    'data' => $category
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'category not found',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $category = Category::find($id);
            if ($category) {
                if ($category->image) {
                    unlink(public_path($category->image));
                }
                $category->delete();
                return response()->json([
                    "success" => true,
                    "message" => "category deleted successfully.",
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'category not found',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
