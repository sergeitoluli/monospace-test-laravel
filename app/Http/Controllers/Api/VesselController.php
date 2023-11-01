<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vessel;
use App\Models\Voyage;
use Illuminate\Http\Request;

class VesselController extends Controller
{
    /* BONUS */

    public function update(Request $request, $vesselId)
    {
        $vessel = Vessel::find($vesselId);

        if (!$vessel) {
            return response()->json(['message' => 'There are no vessels with this id!'], 404);
        }

        if ($request->filled('name')) {
            $vessel->name = $request->get('name');
        }

        if (!$vessel->update()) {
            return response()->json(['message' => 'Internal error! Cannot update the vessel name!'], 500);
        }

        $query = Voyage::where('vessel_id', $vesselId);

        $startDates = $query->select('start')->get();
        
        foreach ($startDates as $date) {
            if (!$query->update(['code' => $vessel->name . '-' . $date->start])) {
                return response()->json(['message' => 'Internal error! Cannot update the voyages code!'], 500);
            }
        }

        return response()->json(['message' => 'Vessel name updated successfully!'], 200);
    }
}
