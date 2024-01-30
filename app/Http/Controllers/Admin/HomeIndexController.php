<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\UpdateHomeIndexRequest;
use App\Models\HomeIndex;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class HomeIndexController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('home_index_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $homeIndices = HomeIndex::with(['media'])->get();

        return view('admin.homeIndices.index', compact('homeIndices'));
    }

    public function edit(HomeIndex $homeIndex)
    {
        abort_if(Gate::denies('home_index_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.homeIndices.edit', compact('homeIndex'));
    }

    public function update(UpdateHomeIndexRequest $request, HomeIndex $homeIndex)
    {
        $homeIndex->update($request->all());

        if ($request->input('image', false)) {
            if (! $homeIndex->image || $request->input('image') !== $homeIndex->image->file_name) {
                if ($homeIndex->image) {
                    $homeIndex->image->delete();
                }
                $homeIndex->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
            }
        } elseif ($homeIndex->image) {
            $homeIndex->image->delete();
        }

        return redirect()->route('admin.home-indices.index');
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('home_index_create') && Gate::denies('home_index_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new HomeIndex();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
