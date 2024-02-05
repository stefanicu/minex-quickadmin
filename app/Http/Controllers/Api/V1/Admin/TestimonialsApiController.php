<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreTestimonialRequest;
use App\Http\Requests\UpdateTestimonialRequest;
use App\Http\Resources\Admin\TestimonialResource;
use App\Models\Testimonial;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TestimonialsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('testimonial_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TestimonialResource(Testimonial::all());
    }

    public function store(StoreTestimonialRequest $request)
    {
        $testimonial = Testimonial::create($request->all());

        foreach ($request->input('logo', []) as $file) {
            $testimonial->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('logo');
        }

        return (new TestimonialResource($testimonial))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial)
    {
        $testimonial->update($request->all());

        if (count($testimonial->logo) > 0) {
            foreach ($testimonial->logo as $media) {
                if (! in_array($media->file_name, $request->input('logo', []))) {
                    $media->delete();
                }
            }
        }
        $media = $testimonial->logo->pluck('file_name')->toArray();
        foreach ($request->input('logo', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $testimonial->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('logo');
            }
        }

        return (new TestimonialResource($testimonial))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Testimonial $testimonial)
    {
        abort_if(Gate::denies('testimonial_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $testimonial->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
