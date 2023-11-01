<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVesselRequest;
use App\Models\Vessel;
use App\Models\VesselOpex;
use App\Models\Voyage;
use DB;
use Illuminate\Http\Request;

class VesselOpexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($vesselId)
    {
        if (!Voyage::where('vessel_id', $vesselId)->count()) {
            return response()->json(['message' => 'There are no voyages for this vessel!'], 404);
        }
        
        $query = DB::select(
            'SELECT 
            voyages.id as voyage_id,
            voyages.start,
            voyages."end",
            voyages.revenues as voyage_revenues,
            voyages.expenses as voyage_expenses,
            voyages.profit as voyage_profit,
            CAST((profit / date_part(\'day\', "end"::timestamp - start::timestamp)) AS DECIMAL(8,2)) as voyage_profit_daily_average,
            vessel_expenses_total,
            (profit - vessel_expenses_total) AS net_profit,
            CAST(((profit - vessel_expenses_total) / date_part(\'day\', "end"::timestamp - start::timestamp)) AS DECIMAL(8,2)) as net_profit_daily_average
            FROM
            voyages
            INNER JOIN (
              SELECT vessel_id, 
              SUM(expenses) AS vessel_expenses_total
              FROM vessel_opex
              GROUP BY vessel_id
            ) AS vessel_opex ON voyages.vessel_id = vessel_opex.vessel_id
            WHERE
            voyages.vessel_id = 2;
            '
        );

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
}
