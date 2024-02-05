<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\Admin\CategoryResource;
use App\Models\Category;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoriesApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CategoryResource(Category::with(['product_image', 'applications'])->get());
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->all());
        $category->applications()->sync($request->input('applications', []));
        if ($request->input('cover_photo', false)) {
            $category->addMedia(storage_path('tmp/uploads/' . basename($request->input('cover_photo'))))->toMediaCollection('cover_photo');
        }

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->all());
        $category->applications()->sync($request->input('applications', []));
        if ($request->input('cover_photo', false)) {
            if (! $category->cover_photo || $request->input('cover_photo') !== $category->cover_photo->file_name) {
                if ($category->cover_photo) {
                    $category->cover_photo->delete();
                }
                $category->addMedia(storage_path('tmp/uploads/' . basename($request->input('cover_photo'))))->toMediaCollection('cover_photo');
            }
        } elseif ($category->cover_photo) {
            $category->cover_photo->delete();
        }

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Category $category)
    {
        abort_if(Gate::denies('category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $category->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
