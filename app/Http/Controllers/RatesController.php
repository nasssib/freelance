<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RatesController extends Controller
{
    public function rating(Request $request)
    {

        $validateUser = Validator::make(
            $request->all(),
            [
                'rated_id' => 'required|exists:users,id',
                'rating' => 'required|integer|min:1|max:5',
            ]
        );
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 400);
        }

        $rater_id = auth()->id();
        $rated_id = $request->rated_id;

        if ($rater_id == $rated_id) {
            return response()->json([
                'status' => false,
                'message' => 'you cant rate your self',
            ], 400);
        }
        $rating = Rate::updateOrCreate(
            ['rater_id' => $rater_id, 'rated_id' => $rated_id],
            ['rating' => $request->input('rating')]
        );


        return response()->json([
            'status' => true,
            'message' => 'Rating submitted successfully',
            'rating' =>  $rating,
            'AVG rate now'=>User::find($rated_id)->averageRating(),
            'number rate now'=>User::find($rated_id)->numRating()
        ]);
    }
}
