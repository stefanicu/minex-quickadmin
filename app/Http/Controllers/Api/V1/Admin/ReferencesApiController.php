<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreReferenceRequest;
use App\Http\Requests\UpdateReferenceRequest;
use App\Http\Resources\Admin\ReferenceResource;
use App\Models\Reference;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReferencesApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('reference_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ReferenceResource(Reference::with(['industries'])->get());
    }

    public function store(StoreReferenceRequest $request)
    {
        $reference = Reference::create($request->all());

        foreach ($request->input('photo_square', []) as $file) {
            $reference->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photo_square');
        }

        if ($request->input('photo_wide', false)) {
            $reference->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo_wide'))))->toMediaCollection('photo_wide');
        }

        return (new ReferenceResource($reference))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateReferenceRequest $request, Reference $reference)
    {
        $reference->update($request->all());

        if (count($reference->photo_square) > 0) {
            foreach ($reference->photo_square as $media) {
                if (! in_array($media->file_name, $request->input('photo_square', []))) {
                    $media->delete();
                }
            }
        }
        $media = $reference->photo_square->pluck('file_name')->toArray();
        foreach ($request->input('photo_square', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $reference->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photo_square');
            }
        }

        if ($request->input('photo_wide', false)) {
            if (! $reference->photo_wide || $request->input('photo_wide') !== $reference->photo_wide->file_name) {
                if ($reference->photo_wide) {
                    $reference->photo_wide->delete();
                }
                $reference->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo_wide'))))->toMediaCollection('photo_wide');
            }
        } elseif ($reference->photo_wide) {
            $reference->photo_wide->delete();
        }

        return (new ReferenceResource($reference))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Reference $reference)
    {
        abort_if(Gate::denies('reference_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $reference->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
