<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\UpdateHomeRequest;
use App\Http\Resources\Admin\HomeResource;
use App\Models\Home;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('home_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new HomeResource(Home::with(['home'])->get());
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

        return (new HomeResource($home))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
