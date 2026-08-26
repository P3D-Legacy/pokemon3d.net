<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceUpdateRequest;
use App\Models\GameVersion;
use App\Models\Resource;
use App\Models\ResourceUpdate;
use App\Notifications\Resource\UpdateNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResourceUpdateController extends Controller
{
    public function create(string $uuid): Response
    {
        $resource = $this->findResource($uuid);
        $this->authorize('postUpdate', $resource);

        return Inertia::render('resources/updates/create', [
            'resource' => [
                'uuid' => $resource->uuid,
                'name' => $resource->name,
            ],
            'gameVersions' => GameVersion::query()
                ->orderByDesc('release_date')
                ->get(['id', 'version'])
                ->map(fn (GameVersion $version): array => [
                    'id' => $version->id,
                    'version' => $version->version,
                ])
                ->values()
                ->all(),
            'copy' => [
                'resources' => __('Resources'),
                'title' => __('Post an update'),
                'versionTitle' => __('Version Title'),
                'gameVersion' => __('Latest supported version'),
                'selectGameVersion' => __('Select a game version'),
                'description' => __('Description'),
                'file' => __('Resource File (ZIP)'),
                'externalDownloadUrl' => __('External download URL'),
                'fileOrUrlHelp' => __('Upload a ZIP file or provide an HTTPS download link. Do not submit both.'),
                'cancel' => __('Cancel'),
                'submit' => __('Post Update'),
            ],
        ]);
    }

    public function store(StoreResourceUpdateRequest $request, string $uuid): RedirectResponse
    {
        $resource = $this->findResource($uuid);
        $this->authorize('postUpdate', $resource);

        $validated = $request->validated();

        $resourceUpdate = ResourceUpdate::create([
            'title' => $validated['version'],
            'description' => $validated['description'],
            'resource_id' => $resource->id,
            'game_version_id' => $validated['gameversion'],
            'external_download_url' => $validated['external_download_url'] ?? null,
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = Str::slug($resource->name).'-'.$resourceUpdate->title.'.'.$file->extension();

            $resourceUpdate->clearMediaCollection('resource_update_file');
            $resourceUpdate
                ->addMedia($file)
                ->usingName($fileName)
                ->toMediaCollection('resource_update_file');
        }

        $followers = $resource->followers()
            ->where('users.id', '!=', $request->user()->id)
            ->get();

        Notification::send($followers, new UpdateNotification($resource, $resourceUpdate));

        session()->flash('flash.bannerStyle', 'success');
        session()->flash('flash.banner', __('Update posted successfully!'));

        return redirect()->route('resource.uuid', $resource->uuid);
    }

    public function download(string $uuid, ResourceUpdate $update): StreamedResponse|RedirectResponse
    {
        $resource = $this->findResource($uuid);

        if ((int) $update->resource_id !== (int) $resource->id) {
            abort(404);
        }

        if (filled($update->external_download_url)) {
            $update->incrementDownload();

            return redirect()->away($update->external_download_url);
        }

        $mediaItem = $update->getFirstMedia('resource_update_file');

        if (! $mediaItem) {
            session()->flash('flash.banner', trans('File not found on server!'));
            session()->flash('flash.bannerStyle', 'danger');

            return redirect()->route('resource.uuid', $resource->uuid);
        }

        $disk = Storage::disk($mediaItem->disk);
        $path = $mediaItem->getPathRelativeToRoot();

        if (! $disk->exists($path)) {
            session()->flash('flash.banner', trans('File not found on server!'));
            session()->flash('flash.bannerStyle', 'danger');

            return redirect()->route('resource.uuid', $resource->uuid);
        }

        $update->incrementDownload();

        return $disk->download($path, $mediaItem->name);
    }

    protected function findResource(string $uuid): Resource
    {
        return Resource::query()
            ->where('uuid', $uuid)
            ->firstOrFail();
    }
}
