<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\UpdateHomeIdRequest;
use App\Models\HomeId;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class HomeIdController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('home_id_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $homeIds = HomeId::with(['media'])->get();

        return view('admin.homeIds.index', compact('homeIds'));
    }

    public function edit(HomeId $homeId)
    {
        abort_if(Gate::denies('home_id_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.homeIds.edit', compact('homeId'));
    }

    public function update(UpdateHomeIdRequest $request, HomeId $homeId)
    {
        $homeId->update($request->all());

        if ($request->input('image', false)) {
            if (! $homeId->image || $request->input('image') !== $homeId->image->file_name) {
                if ($homeId->image) {
                    $homeId->image->delete();
                }
                $homeId->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
            }
        } elseif ($homeId->image) {
            $homeId->image->delete();
        }

        return redirect()->route('admin.home-ids.index');
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('home_id_create') && Gate::denies('home_id_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new HomeId();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
