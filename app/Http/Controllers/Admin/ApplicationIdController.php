<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Models\ApplicationId;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ApplicationIdController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('application_id_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationIds = ApplicationId::with(['categories', 'media'])->get();

        return view('admin.applicationIds.index', compact('applicationIds'));
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('application_id_create') && Gate::denies('application_id_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ApplicationId();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
