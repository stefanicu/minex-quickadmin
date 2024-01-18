<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Application;
use App\Models\Category;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class CategoriesController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::with(['product_image', 'applications', 'media'])->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        abort_if(Gate::denies('category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product_images = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $applications = Application::pluck('name', 'id');

        return view('admin.categories.create', compact('applications', 'product_images'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->all());
        $category->applications()->sync($request->input('applications', []));
        if ($request->input('cover_photo', false)) {
            $category->addMedia(storage_path('tmp/uploads/' . basename($request->input('cover_photo'))))->toMediaCollection('cover_photo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $category->id]);
        }

        return redirect()->route('admin.categories.index');
    }

    public function edit(Category $category)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product_images = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $applications = Application::pluck('name', 'id');

        $category->load('product_image', 'applications');

        return view('admin.categories.edit', compact('applications', 'category', 'product_images'));
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

        return redirect()->route('admin.categories.index');
    }

    public function destroy(Category $category)
    {
        abort_if(Gate::denies('category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $category->delete();

        return back();
    }

    public function massDestroy(MassDestroyCategoryRequest $request)
    {
        $categories = Category::find(request('ids'));

        foreach ($categories as $category) {
            $category->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('category_create') && Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Category();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
