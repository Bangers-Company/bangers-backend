<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LineupSyncController extends Controller
{
    public function sync(Request $request, Event $event)
    {
        $validator = Validator::make($request->all(), [
            'lineup' => 'present|array',
            'lineup.*.act_id' => 'required|uuid|exists:acts,id',
            'lineup.*.stage_id' => 'nullable|uuid|exists:stages,id',
            'lineup.*.date' => 'nullable|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $now = now();
        $uniqueRecords = [];

        foreach ($request->lineup as $item) {
            $stageIdKey = $item['stage_id'] ?? 'null';
            $dateKey = $item['date'] ?? 'null';
            $key = "{$item['act_id']}_{$stageIdKey}_{$dateKey}";

            if (!isset($uniqueRecords[$key])) {
                $uniqueRecords[$key] = [
                    'event_id' => $event->id,
                    'act_id' => $item['act_id'],
                    'stage_id' => $item['stage_id'] ?? null,
                    'date' => $item['date'] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        $insertData = array_values($uniqueRecords);

        DB::transaction(function () use ($event, $insertData) {
            // Remove existing lineup associations
            DB::table('event_stage_acts')->where('event_id', $event->id)->delete();

            // Re-insert new associations
            if (!empty($insertData)) {
                DB::table('event_stage_acts')->insert($insertData);
            }
        });

        // Touch the event so caches/updated_at are refreshed
        $event->touch();

        return response()->json([
            'message' => 'Lineup synchronized successfully',
        ], 200);
    }
}
