<?php

namespace App\Modules\Profiles\Http\Controllers;

use App\Models\AuditLog;
use App\Modules\Profiles\Http\Requests\AddThresholdRequest;
use App\Modules\Profiles\Http\Requests\CreateProfileRequest;
use App\Modules\Profiles\Http\Resources\HearingProfileResource;
use App\Modules\Profiles\Http\Resources\HearingThresholdResource;
use App\Modules\Profiles\Models\HearingProfile;
use App\Modules\Profiles\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class ProfileController extends Controller
{
    public function __construct(private readonly ProfileService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $profiles = $request->user()->hearingProfiles()->latest()->get();

        return HearingProfileResource::collection($profiles);
    }

    public function store(CreateProfileRequest $request): HearingProfileResource
    {
        $profile = $this->service->create(
            user: $request->user(),
            name: $request->name,
            notes: $request->notes,
            sourceSessionId: $request->source_session_id,
        );

        AuditLog::record('profile.created', $request->user(), entityType: HearingProfile::class, entityId: $profile->id);

        return new HearingProfileResource($profile->load('thresholds'));
    }

    public function show(Request $request, HearingProfile $profile): HearingProfileResource
    {
        abort_unless($profile->user_id === $request->user()->id, 403);

        return new HearingProfileResource($profile->load('thresholds'));
    }

    public function update(Request $request, HearingProfile $profile): HearingProfileResource
    {
        abort_unless($profile->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'name'  => ['sometimes', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $profile->update($validated);

        return new HearingProfileResource($profile->load('thresholds'));
    }

    public function destroy(Request $request, HearingProfile $profile): JsonResponse
    {
        abort_unless($profile->user_id === $request->user()->id, 403);

        $profile->delete();

        return response()->json(null, 204);
    }

    public function activate(Request $request, HearingProfile $profile): HearingProfileResource
    {
        abort_unless($profile->user_id === $request->user()->id, 403);

        $profile = $this->service->setActive($profile);

        AuditLog::record('profile.activated', $request->user(), entityType: HearingProfile::class, entityId: $profile->id);

        return new HearingProfileResource($profile->load('thresholds'));
    }

    public function addThreshold(AddThresholdRequest $request, HearingProfile $profile): HearingThresholdResource
    {
        abort_unless($profile->user_id === $request->user()->id, 403);

        $threshold = $this->service->addThreshold($profile, $request->validated());

        return new HearingThresholdResource($threshold);
    }
}
