<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\UpdateHomeIdRequest;
use App\Http\Resources\Admin\HomeIdResource;
use App\Models\HomeId;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeIdApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('home_id_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new HomeIdResource(HomeId::all());
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

        return (new HomeIdResource($homeId))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
