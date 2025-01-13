<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\ApplicationTranslation;
use App\Models\Category;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CategoriesController extends Controller
{
    use MediaUploadingTrait;
    
    public function index(Request $request)
    {
        abort_if(Gate::denies('category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        if ($request->ajax()) {
            //            $query = Category::with(['media', 'translations'])
            //                ->join('category_translations', function ($join) {
            //                    $join->on('categories.id', '=', 'category_translations.category_id')
            //                        ->where('category_translations.locale', '=', app()->getLocale());
            //                })
            //                ->select(sprintf('%s.*', (new Category)->table));
            
            $query = Category::with(['media', 'translations'])
                ->leftJoin('category_translations', function ($join) {
                    $join->on('categories.id', '=', 'category_translations.category_id')
                        ->where('category_translations.locale', '=', app()->getLocale());
                })
                ->select([
                    sprintf('%s.*', (new Category)->table),
                    DB::raw("COALESCE(category_translations.name, '---NO TRANSLATION---') as name"),
                ]);
            
            
            //            foreach ($query->get() as $category) {
            //                if($category->oldproductid){
            //                    $product_id = Product::find($category->oldproductid);
            //                    if($product_id){
            //                        $categ = Category::find($category->id); // Find the product by ID
            //                        $categ->product_image_id = $category->oldproductid;
            //                        $categ->save(); // Save the changes
            //                    }
            //                }
            //            }
            //
            //
            //            foreach ($query->get() as $category) {
            //                $cover_photo = Media::where('model_id', $category->id)
            //                    ->where('model_type', Category::class)
            //                    ->get();
            //
            //                if(count($cover_photo) === 0) {
            //                    if ($category->oldimage) {
            //                        if (file_exists(public_path().asset('uploads/categorii/'.$category->oldimage))) {
            //                            $category->addMediaFromUrl(
            //                                url('').asset('uploads/categorii/'.$category->oldimage)
            //                            )->toMediaCollection('cover_photo');
            //                        }
            //                    }else{
            //                        $category->addMediaFromUrl(
            //                            url('').asset('img/headers/aplicatie-xl.jpg')
            //                        )->toMediaCollection('cover_photo');
            //                    }
            //                }
            //            }
            
            
            $table = Datatables::of($query);
            
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            
            $table->editColumn('actions', function ($row) {
                $viewGate = 'category_show';
                $editGate = 'category_edit';
                $deleteGate = 'category_delete';
                $crudRoutePart = 'categories';
                
                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });
            
            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('online', function ($row) {
                return '<input type="checkbox" disabled '.($row->online ? 'checked' : null).'>';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('page_views', function ($row) {
                return $row->page_views ? $row->page_views : '';
            });
            $table->editColumn('cover_photo', function ($row) {
                if ($photo = $row->cover_photo) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="auto" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }
                
                return '';
            });
            
            $table->rawColumns(['actions', 'placeholder', 'online', 'page_views', 'cover_photo']);
            
            return $table->make(true);
        }
        
        return view('admin.categories.index');
    }
    
    public function create()
    {
        abort_if(Gate::denies('category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $product_images = null;
        
        $applications = ApplicationTranslation::where('locale', app()->getLocale())->orderBy('name', 'asc')->pluck('name', 'id');
        
        return view('admin.categories.create', compact('applications', 'product_images'));
    }
    
    public function store(StoreCategoryRequest $request)
    {
        if ($request->input('cover_photo', false)) {
            $tempPath = storage_path('tmp/uploads/'.basename($request->input('cover_photo')));
            // Validate the image dimensions
            [$width, $height] = getimagesize($tempPath);
            
            if ($width != 1920 || $height != 580) {
                // Delete the temporary file if validation fails
                if (file_exists($tempPath)) {
                    unlink($tempPath);
                }
                return redirect()->back()->withInput()->withErrors([
                    'cover_photo' => __("admin.image_dimensions", [
                        'expected_width' => 1920,
                        'expected_height' => 580,
                        'uploaded_width' => $width,
                        'uploaded_height' => $height
                    ]),
                ]);
            }
        }
        
        $category = Category::create($request->all());
        
        if ($request->input('cover_photo', false)) {
            $category->addMedia($tempPath)->toMediaCollection('cover_photo');
        }
        
        
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $category->id]);
        }
        
        return redirect()->route('admin.categories.index');
    }
    
    public function edit(Category $category)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $categoryId = $category->id;
        
        $product_images = Product::whereHas('categories', function ($query) use ($categoryId) {
            $query->where('categories.id', '=', $categoryId);
        })
            ->whereHas('media', function ($query) {
                $query->where('collection_name', 'main_photo');
            })
            ->orderByTranslation('name')
            ->get();
        
        $products_online = Product::whereHas('categories', function ($query) use ($categoryId) {
            $query->where('categories.id', '=', $categoryId);
        })
            ->whereHas('media', function ($query) {
                $query->where('collection_name', 'main_photo');
            })
            ->whereHas('translations', function ($query) {
                $query->where('locale', app()->getLocale()) // Current language
                ->where('online', 1); // Filter by online = 1
            })
            ->orderByTranslation('name')
            ->get();
        
        $application = null;
        if ($products_online && count($products_online) > 1) {
            $product = $products_online->first();
            $application = $product->applications()->first();
        }
        
        $applications = ApplicationTranslation::where('locale', app()->getLocale())->pluck('name', 'application_id');
        
        //$category->load('product_image', 'applications');
        
        return view('admin.categories.edit', compact('applications', 'category', 'product_images', 'application'));
    }
    
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->all());
        
        $category->applications()->sync($request->input('applications', []));
        
        if ($request->input('cover_photo', false)) {
            if ( ! $category->cover_photo || $request->input('cover_photo') !== $category->cover_photo->file_name) {
                $tempPath = storage_path('tmp/uploads/'.basename($request->input('cover_photo')));
                // Validate the image dimensions
                [$width, $height] = getimagesize($tempPath);
                
                if ($width != 1920 || $height != 580) {
                    // Delete the temporary file if validation fails
                    unlink($tempPath);
                    
                    return redirect()->back()->withErrors([
                        'cover_photo' => __("admin.image_dimensions", [
                            'expected_width' => 1920,
                            'expected_height' => 580,
                            'uploaded_width' => $width,
                            'uploaded_height' => $height
                        ]),
                    ]);
                }
                
                if ($category->cover_photo) {
                    $category->cover_photo->delete();
                }
                
                $category->addMedia($tempPath)->toMediaCollection('cover_photo');
            }
        } elseif ($category->cover_photo) {
            $category->cover_photo->delete();
        }
        
        return redirect()->route('admin.categories.edit', $category)->withInput()->withErrors([]);
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
        
        $model = new Category();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');
        
        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
