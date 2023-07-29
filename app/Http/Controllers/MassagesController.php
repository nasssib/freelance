<?php

namespace App\Http\Controllers;

use App\Models\massage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MassagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            //$massages = $user->massages->all();
            $messages = DB::table('massages')
                ->join('users', 'users.id', '=', 'massages.sender_id')
                ->select('massages.id','sender_id','text','massages.created_at', 'users.name AS sender_name')
                ->where('massages.user_id', '=', $user->id)
                ->get();

            return response()->json([
                'status' => true,
                'data' => $messages,
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
                    'text' => 'required',
                    'user_id' => 'required|integer'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            massage::create([

                'text' => $request->text,
                'user_id' => $request->user_id,
                'sender_id' => Auth::user()->id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Massage sended',

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
    public function show(massage $massage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(massage $massage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, massage $massage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $massage = massage::find($id);
            if ($massage && $massage->user_id == Auth::user()->id) {
                $massage->delete();
                return response()->json([
                    "success" => true,
                    "message" => "massage deleted successfully.",
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'massage not found',
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
