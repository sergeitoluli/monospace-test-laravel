<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVoyageRequest;
use App\Models\Vessel;
use App\Models\Voyage;
use Illuminate\Http\Request;

class VoyageController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVoyageRequest $request)
    {
        $query = Voyage::query()
            ->where('vessel_id', $request->get('vessel_id'))
            ->where('status', 'ongoing')
            ->count();

        if ($query && $request->get('status') == 'ongoing') {
            return response()->json([
                'message' => 'This vessel is already on a voyage! Cannot store the voyage!'
            ], 400);
        }

        if ($request->get('start') >= $request->get('end')) {
            return response()->json([
                'message' => 'Start date should not be later than end date! Please check! Cannot store the voyage!'
            ], 400);
        }

        $profit = $request->get('revenues') - $request->get('expenses');

        if ($profit <= 0) {
            return response()->json([
                'message' => 'Profit is less or equal to 0! Please check revenues and expenses! Cannot store the voyage!'
            ], 400);
        }

        if (!$request->filled('status') || !in_array($request->get('status'), ['processing', 'ongoing', 'submitted'])) {
            $request['status'] = 'pending';
        }

        $vessel = Vessel::find($request->get('vessel_id'))->name;

        $request['code'] = $vessel . '-' . $request->get('start');
        $request['profit'] = $profit;

        if (!Voyage::create($request->all())) {
            return response()->json(['message' => 'Internal error! Cannot store the voyage!'], 500);
        }

        return response()->json(['message' => 'Voyage stored successfully!'], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $voyageId)
    {
        $voyage = Voyage::find($voyageId);

        if (!$voyage) {
            return response()->json(['message' => 'Voyage not found! Please check again!'], 404);
        }

        if ($voyage->status == 'submitted') {
            return response()->json(['message' => 'This voyage cannot be updated, because is submitted!'], 400);
        }

        $query = Voyage::query()
            ->where('vessel_id', $voyage->vessel_id)
            ->where('status', 'ongoing')
            ->count();

        if ($query && $request->get('status') == 'ongoing') {
            return response()->json([
                'message' => 'This vessel is already on a voyage! Cannot update the voyage!'
            ], 400);
        }

        if ($request->filled('revenues') && $request->filled('expenses')) {
            $profit = $request->get('revenues') - $request->get('expenses');

            if ($profit > 0) {
                $voyage->profit = $profit;
            } else {
                return response()->json([
                    'message' => 'Profit is less or equal to 0! Please check revenues and expenses! Cannot store the voyage!'
                ], 400);
            }
        }

        if ($request->filled('status')) {
            if (in_array($request->get('status'), ['processing', 'ongoing', 'submitted'])) {
                $voyage->status = $request->get('status');
            } else {
                $voyage->status = 'pending';
            }
        }

        /* BONUS */
        if ($request->filled('start') && !$request->filled('end')) {
            if ($request->get('start') >= $voyage->end) {
                return response()->json([
                    'message' => 'Start date should not be later than end date! Please check! Cannot update the voyage!'
                ], 400);
            }
            $name = Vessel::find($voyage->vessel_id)->name;
            $voyage->start = $request->get('start');
            $voyage->code = $name . '-' . $request->get('start');
        }

        if (!$request->filled('start') && $request->filled('end')) {
            if ($voyage->start >= $request->get('end')) {
                return response()->json([
                    'message' => 'Start date should not be later than end date! Please check! Cannot update the voyage!'
                ], 400);
            }

            $voyage->end = $request->get('end');
        }

        if ($request->filled('start') && $request->filled('end')) {
            if ($request->get('start') >= $request->get('end')) {
                return response()->json([
                    'message' => 'Start date should not be later than end date! Please check! Cannot update the voyage!'
                ], 400);
            }

            $name = Vessel::find($voyage->vessel_id)->name;
            $voyage->code = $name . '-' . $request->get('start');
            $voyage->start = $request->get('start');
            $voyage->end = $request->get('end');
        }

        if (!$voyage->update()) {
            return response()->json(['message' => 'Internal error! Cannot update the voyage!'], 500);
        }

        return response()->json(['message' => 'Voyage updated successfully!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($voyageId)
    {
        $voyage = Voyage::find($voyageId);

        if (!$voyage) {
            return response()->json(['message' => 'Voyage not found! Please check!'], 404);
        }

        if (!$voyage->delete()) {
            return response()->json(['message' => 'Internal error! Cannot delete the voyage!'], 500);
        }

        return response()->json(['message' => 'Voyage deleted successfully!'], 200);
    }
}
