<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVesselRequest;
use App\Models\Vessel;
use App\Models\VesselOpex;
use DB;
use Illuminate\Http\Request;

class VesselOpexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($vesselId)
    {
        $query = DB::table('vessel_opex')
            ->join('vessels', 'vessels.id', '=', 'vessel_opex.vessel_id')
            ->join('voyages', 'vessels.id', '=', 'voyages.vessel_id')
            ->where('vessels.id', $vesselId)
            ->select(
                'voyages.id as voyage_id',
                'voyages.start as start',
                'voyages.end as end',
                'voyages.revenues as voyage_revenues',
                'voyages.expenses as voyage_expenses',
                'start AS start',
                DB::raw('"end" AS "end"'),
                'profit as voyage_profit',
                DB::raw('(profit / date_part(\'day\', "end"::timestamp - start::timestamp)) AS voyage_profit_daily_average')
            )
            ->get();

        return response()->json($query);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVesselRequest $request, $vesselId)
    {
        $opex = VesselOpex::query()
            ->where('vessel_id', $vesselId)
            ->where('date', $request->get('date'))
            ->count();

        if ($opex) {
            return response()->json([
                'message' => 'A vessel cannot have two different opex amounts for the same date!'
            ], 400);
        }

        $request['vessel_id'] = $vesselId;

        if (!VesselOpex::create($request->all())) {
            return response()->json(['message' => 'Internal error! Cannot store the vessel opex!'], 500);
        }

        return response()->json(['message' => 'Vessel operation expenses stored successfully!'], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VesselOpex $vesselOpex)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VesselOpex $vesselOpex)
    {
        //
    }
}
