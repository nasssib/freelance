<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfilesController extends Controller
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
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);

        if ($user) {

            return response()->json([
                "success" => true,
                'user' => $user->load("profile"),
                'AVG_rate_now' => $user->averageRating(),
                'number_rate_now' => $user->numRating()
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'user not found',
            ], 404);
        }
    }

    public function update(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'about' => 'required|max:255',
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

            $profile = Auth::user()->profile;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $path = public_path('images/profiles/' . $filename);
                if ($profile->image) {
                    unlink(public_path($profile->image));
                }
                move_uploaded_file($image->getPathname(), $path);
                $profile->image = '/images/profiles/' . $filename;
            }
            $profile->about = $request->about;
            $profile->save();
            return response()->json([
                "success" => true,
                "message" => "profile updated successfully.",
                'data' => $profile
            ]);
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
    public function destroy(Profile $profile)
    {
        //
    }
}
