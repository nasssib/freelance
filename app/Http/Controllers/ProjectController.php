<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'category_id' => 'required|exists:categories,id',
                    'title' => 'required|max:255',
                    //'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg',
                    'price' => 'required|integer',
                    'description' => 'required|string'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 400);
            }

            // if ($request->hasFile('image')) {
            //     $image = $request->file('image');
            //     $filename = time() . '.' . $image->getClientOriginalExtension();
            //     $path = public_path('/images/projects/' . $filename);
            //     move_uploaded_file($image->getPathname(), $path);
            // }

            $c = Project::Create([

                'user_id' => auth()->user()->id,
                'category_id' => $request->category_id,
                'title' => $request->title,
                'price' => $request->price,
                'description' => $request->description,
                //'image' => '/images/projects/' . $filename
            ]);

            return response()->json([
                'status' => true,
                'message' =>  $request->name . ' created as Project',
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
     * Display the specified resource.
     */
    public function show_accepted($id)
    {
        $Project = Project::find($id);

        if ($Project->accept_at != null) {

            return response()->json([
                'status' => true,
                'Project' => $Project

            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'can\'t show the Project ',
            ], 404);
        }
    }

    public function show_un_accepted($id)
    {
        $Project = Project::find($id);

        if ($Project->user_id == auth()->user()->id ||  auth()->user()->type == 2) {

            return response()->json([
                'status' => true,
                'Project' => $Project

            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'can\'t show the Project ',
            ], 404);
        }
    }
    public function update(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'project_id' => 'required|exists:projects,id',
                    'category_id' => 'required|exists:categories,id',
                    'title' => 'required|max:255',
                    //'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg',
                    'price' => 'required|integer',
                    'description' => 'required|string'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 400);
            }


            $Project = Project::find($request->project_id);

            if ($Project->user->id != auth()->user()->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'you can\'t edit this Project',
                ], 400);
            }

            // if ($request->hasFile('image')) {
            //     $image = $request->file('image');
            //     $filename = time() . '.' . $image->getClientOriginalExtension();
            //     $path = public_path('images/projects/' . $filename);
            //     if ($Project->image) {
            //         unlink(public_path($Project->image));
            //     }
            //     move_uploaded_file($image->getPathname(), $path);
            //     $Project->image = '/images/projects/' . $filename;
            // }


            $Project->category_id = $request->category_id;
            $Project->title = $request->title;
            $Project->price = $request->price;
            $Project->description = $request->description;
            //$Project->image = '/images/projects/' . $filename;
            $Project->accept_at = null;
            $Project->save();


            return response()->json([
                'status' => true,
                'message' => 'Project updated successfully',
                'data' => $Project

            ], 200);
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
            $Project = Project::find($id);
            if ($Project) {
                if ($Project->user_id == auth()->user()->id) {
                    if ($Project->image) {
                        unlink(public_path($Project->image));
                    }
                    $Project->delete();
                    return response()->json([
                        "success" => true,
                        "message" => "Project deleted successfully.",
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'you can\'t delete this Project',
                    ], 404);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Project not found',
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
