<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\UpdateFrontPageRequest;
use App\Http\Resources\Admin\FrontPageResource;
use App\Models\FrontPage;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FrontPageApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('front_page_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new FrontPageResource(FrontPage::all());
    }

    public function update(UpdateFrontPageRequest $request, FrontPage $frontPage)
    {
        $frontPage->update($request->all());

        if ($request->input('image', false)) {
            if (! $frontPage->image || $request->input('image') !== $frontPage->image->file_name) {
                if ($frontPage->image) {
                    $frontPage->image->delete();
                }
                $frontPage->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
            }
        } elseif ($frontPage->image) {
            $frontPage->image->delete();
        }

        return (new FrontPageResource($frontPage))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
