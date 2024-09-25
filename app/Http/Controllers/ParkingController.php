<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parking;

class ParkingController extends Controller
{
    public function create(Request $request)
    {
        $parking = Parking::create([
            'building_id' => $request->building_id,
            'unit_id'     => $request->unit_id,
            'plan'        => $request->plan,
            'start_date'  => $request->startDate,
            'car_brand'   => $request->brand,
            'model'       => $request->model,
            'color'       => $request->color,
            'license_plate'=> $request->licensePlate,
            'email'        => $request->email,
            'phone_number'  => $request->phone_number
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Parking created successfully.',
            'data'    => $parking
        ]);
    }

    public function plans(Request $request)
    {
        $currentDate = now();
        $dateThirtyDaysAgo = now()->subDays(30);
        $plan = Parking::where('building_id', $request->building_id)
                    ->where('unit_id', $request->unit_id)
                    ->where('plan', '3days')
                    ->whereBetween('created_at', [$dateThirtyDaysAgo, $currentDate]) // Ensure it's within the last 30 days
                    ->get();
        if ($plan->isEmpty()) {
            return response()->json([
                'success' => false,
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Existing 3days parking',
                'data'    => $plan
            ]);
        }
    }
}
