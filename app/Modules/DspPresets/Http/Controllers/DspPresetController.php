<?php

namespace App\Modules\DspPresets\Http\Controllers;

use App\Models\AuditLog;
use App\Modules\DspPresets\Http\Requests\SavePresetRequest;
use App\Modules\DspPresets\Http\Resources\DspPresetResource;
use App\Modules\DspPresets\Models\DspPreset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class DspPresetController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $presets = $request->user()->dspPresets()->latest()->get();

        return DspPresetResource::collection($presets);
    }

    public function store(SavePresetRequest $request): DspPresetResource
    {
        $preset = DspPreset::create([
            'user_id'     => $request->user()->id,
            'name'        => $request->name,
            'description' => $request->description,
            'config'      => $request->input('config', []),
            'is_active'   => false,
        ]);

        AuditLog::record('dsp_preset.created', $request->user(), entityType: DspPreset::class, entityId: $preset->id);

        return new DspPresetResource($preset);
    }

    public function show(Request $request, DspPreset $dspPreset): DspPresetResource
    {
        abort_unless($dspPreset->user_id === $request->user()->id, 403);

        return new DspPresetResource($dspPreset);
    }

    public function update(SavePresetRequest $request, DspPreset $dspPreset): DspPresetResource
    {
        abort_unless($dspPreset->user_id === $request->user()->id, 403);

        $dspPreset->update([
            'name'        => $request->name,
            'description' => $request->description,
            'config'      => $request->input('config', $dspPreset->config),
        ]);

        return new DspPresetResource($dspPreset->fresh());
    }

    public function destroy(Request $request, DspPreset $dspPreset): JsonResponse
    {
        abort_unless($dspPreset->user_id === $request->user()->id, 403);

        $dspPreset->delete();

        return response()->json(null, 204);
    }

    public function activate(Request $request, DspPreset $dspPreset): DspPresetResource
    {
        abort_unless($dspPreset->user_id === $request->user()->id, 403);

        DB::transaction(function () use ($request, $dspPreset) {
            DspPreset::where('user_id', $request->user()->id)
                ->where('id', '!=', $dspPreset->id)
                ->update(['is_active' => false]);

            $dspPreset->update(['is_active' => true]);
        });

        AuditLog::record('dsp_preset.activated', $request->user(), entityType: DspPreset::class, entityId: $dspPreset->id);

        return new DspPresetResource($dspPreset->fresh());
    }
}
