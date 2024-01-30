<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\UpdateHomeIndexRequest;
use App\Http\Resources\Admin\HomeIndexResource;
use App\Models\HomeIndex;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeIndexApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('home_index_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new HomeIndexResource(HomeIndex::all());
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

        return (new HomeIndexResource($homeIndex))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
