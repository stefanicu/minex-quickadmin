<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\UpdateHomeRequest;
use App\Models\Home;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('home_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $homes = Home::with(['idd', 'media'])->get();

        return view('admin.homes.index', compact('homes'));
    }

    public function edit(Home $home)
    {
        abort_if(Gate::denies('home_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $home->load('idd');

        return view('admin.homes.edit', compact('home'));
    }

    public function update(UpdateHomeRequest $request, Home $home)
    {
        $home->update($request->all());

        if ($request->input('image', false)) {
            if (! $home->image || $request->input('image') !== $home->image->file_name) {
                if ($home->image) {
                    $home->image->delete();
                }
                $home->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
            }
        } elseif ($home->image) {
            $home->image->delete();
        }

        return redirect()->route('admin.homes.index');
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('home_create') && Gate::denies('home_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Home();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
