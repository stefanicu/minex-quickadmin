<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreIndustryRequest;
use App\Http\Requests\UpdateIndustryRequest;
use App\Http\Resources\Admin\IndustryResource;
use App\Models\Industry;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IndustriesApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('industry_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IndustryResource(Industry::all());
    }

    public function store(StoreIndustryRequest $request)
    {
        $industry = Industry::create($request->all());

        if ($request->input('photo', false)) {
            $industry->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
        }

        return (new IndustryResource($industry))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateIndustryRequest $request, Industry $industry)
    {
        $industry->update($request->all());

        if ($request->input('photo', false)) {
            if (! $industry->photo || $request->input('photo') !== $industry->photo->file_name) {
                if ($industry->photo) {
                    $industry->photo->delete();
                }
                $industry->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
            }
        } elseif ($industry->photo) {
            $industry->photo->delete();
        }

        return (new IndustryResource($industry))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
