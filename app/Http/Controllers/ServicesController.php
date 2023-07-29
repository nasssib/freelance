<?php

namespace App\Http\Controllers;

use App\Models\service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServicesController extends Controller
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
                    'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:50000000',
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

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $path = public_path('/images/services/' . $filename);
                move_uploaded_file($image->getPathname(), $path);
            }

            $c = service::Create([

                'user_id' => auth()->user()->id,
                'category_id' => $request->category_id,
                'title' => $request->title,
                'price' => $request->price,
                'description' => $request->description,
                'image' => '/images/services/' . $filename
            ]);

            return response()->json([
                'status' => true,
                'message' =>  $request->name . ' created as service',
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
        $service = service::findOrFail($id);

        if ($service->accept_at != null) {

            return response()->json([
                'status' => true,
                'service' => $service

            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'can\'t show the service (no accept)',
            ], 404);
        }
    }

    public function show_un_accepted($id)
    {
        $service = service::findOrFail($id);

        if ($service->user_id == auth()->user()->id ||  auth()->user()->type == 2) {

            return response()->json([
                'status' => true,
                'service' => $service

            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'can\'t show the service ',
            ], 404);
        }
    }
    public function update(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'service_id' => 'required|exists:services,id',
                    'category_id' => 'required|exists:categories,id',
                    'title' => 'required|max:255',
                    'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg',
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


            $service = service::find($request->service_id);

            if ($service->user->id != auth()->user()->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'you can\'t edit this service',
                ], 400);
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $path = public_path('images/services/' . $filename);
                if ($service->image) {
                    unlink(public_path($service->image));
                }
                move_uploaded_file($image->getPathname(), $path);
                $service->image = '/images/services/' . $filename;
            }


            $service->category_id = $request->category_id;
            $service->title = $request->title;
            $service->price = $request->price;
            $service->description = $request->description;
            $service->image = '/images/services/' . $filename;
            $service->accept_at = null;
            $service->save();


            return response()->json([
                'status' => true,
                'message' => 'service updated successfully',
                'data' => $service

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
            $service = service::find($id);
            if ($service) {
                if ($service->user_id == auth()->user()->id) {
                    if ($service->image) {
                        unlink(public_path($service->image));
                    }
                    $service->delete();
                    return response()->json([
                        "success" => true,
                        "message" => "service deleted successfully.",
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'you can\'t delete this service',
                    ], 404);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'service not found',
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
