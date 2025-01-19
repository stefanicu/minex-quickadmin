<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\ApplicationTranslation;
use App\Models\Brand;
use App\Models\CategoryTranslation;
use App\Models\Product;
use App\Models\ReferenceTranslation;
use App\Services\ChatGPTService;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;


class ProductsController extends Controller
{
    use MediaUploadingTrait;
    
    protected $chatGptService;
    
    public function __construct(ChatGPTService $chatGptService)
    {
        $this->chatGptService = $chatGptService;
    }
    
    public function index(Request $request)
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        if ($request->ajax()) {
            $currentLocale = app()->getLocale();
            
            $query = Product::with('translations', 'media', 'brand', 'brand.translations', 'applications.translations',
                'applications.media', 'categories.translations', 'categories.media',)->leftJoin('product_translations',
                function ($join) use ($currentLocale) {
                    $join->on('products.id', '=',
                        'product_translations.product_id')->where('product_translations.locale', $currentLocale);
                })->leftJoin('category_product', 'products.id', '=',
                'category_product.product_id')->leftJoin('categories', 'categories.id', '=',
                'category_product.category_id')->leftJoin('category_translations',
                function ($join) use ($currentLocale) {
                    $join->on('categories.id', '=',
                        'category_translations.category_id')->where('category_translations.locale', $currentLocale);
                })->leftJoin('application_product', 'products.id', '=',
                'application_product.product_id')->leftJoin('applications', 'applications.id', '=',
                'application_product.application_id')->leftJoin('application_translations',
                function ($join) use ($currentLocale) {
                    $join->on('applications.id', '=',
                        'application_translations.application_id')->where('application_translations.locale', $currentLocale);
                })->leftJoin('brands', function ($join) use ($currentLocale) {
                $join->on('products.brand_id', '=', 'brands.id')->whereNull('brands.deleted_at');
            })->select('products.id', 'brands.name as brand_name',
                DB::raw("COALESCE(product_translations.name, '--- NO TRANSLATION ---') as name"),
                DB::raw("GROUP_CONCAT(DISTINCT category_translations.name ORDER BY category_translations.name ASC SEPARATOR ', ') as category_names"))->groupBy('products.id',
                'product_translations.name', 'brands.name');
            
            $table = Datatables::of($query);
            
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            
            $table->editColumn('actions', function ($row) {
                $viewGate = 'product_show';
                $editGate = 'product_edit';
                $deleteGate = 'product_delete';
                $crudRoutePart = 'products';
                
                return view('partials.datatablesActions',
                    compact('viewGate', 'editGate', 'deleteGate', 'crudRoutePart', 'row'));
            });
            
            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('online', function ($row) {
                return '<input type="checkbox" disabled '.($row->online ? 'checked' : null).'>';
            });
            $table->addColumn('brands.name', function ($row) {
                return $row->brand ? $row->brand->name : '';
            });
            
            $table->editColumn('brand.slug', function ($row) {
                return $row->brand ? (is_string($row->brand) ? $row->brand : $row->brand->slug) : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('applications', function ($row) {
                $labels = [];
                foreach ($row->applications as $applicaiton) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $applicaiton->name);
                }
                
                return implode(' ', $labels);
            });
            $table->editColumn('categories', function ($row) {
                $labels = [];
                foreach ($row->categories as $category) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $category->name);
                }
                
                return implode(' ', $labels);
            });
            //            $table->editColumn('photo', function ($row) {
            //                if (! $row->photo) {
            //                    return '';
            //                }
            //                $links = [];
            //                foreach ($row->photo as $media) {
            //                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank"><img src="' . $media->getUrl('thumb') . '" width="50" height="50px"></a>';
            //                }
            //
            //                return implode(' ', $links);
            //            });
            //
            //            $table->editColumn('main_photo', function ($row) {
            //                if ($photo = $row->main_photo) {
            //                    return sprintf(
            //                        '<a href="%s" target="_blank"><img src="%s" width="auto" height="50px"></a>',
            //                        $photo->url,
            //                        $photo->thumbnail
            //                    );
            //                }
            //
            //                return '';
            //            });
            
            $table->rawColumns([
                'actions', 'placeholder', 'online', 'brand', 'applications', 'categories', 'photo', 'main_photo'
            ]);
            
            return $table->make(true);
        }
        
        return view('admin.products.index');
    }
    
    public function create()
    {
        $currentLocale = app()->getLocale();
        
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        
        $applications = ApplicationTranslation::where('locale', $currentLocale)->orderBy('name', 'asc')->pluck('name',
            'application_id');
        
        $categories = CategoryTranslation::where('locale', $currentLocale)->orderBy('name', 'asc')->pluck('name',
            'category_id');
        
        $references = ReferenceTranslation::where('locale', $currentLocale)->orderBy('name', 'asc')->pluck('name',
            'reference_id');
        
        
        return view('admin.products.create', compact('applications', 'brands', 'categories', 'references'));
    }
    
    public function store(StoreProductRequest $request)
    {
        if ($request->input('main_photo', false)) {
            $tempPath = storage_path('tmp/uploads/'.basename($request->input('main_photo')));
            // Validate the image dimensions
            [$width, $height] = getimagesize($tempPath);
            
            if ($width != 600 || $height != 600) {
                // Delete the temporary file if validation fails
                if (file_exists($tempPath)) {
                    unlink($tempPath);
                }
                return redirect()->back()->withInput()->withErrors([
                    'main_photo' => __("admin.image_dimensions", [
                        'expected_width' => 600, 'expected_height' => 600, 'uploaded_width' => $width,
                        'uploaded_height' => $height
                    ]),
                ]);
            }
        }
        
        if ($request->input('photo', false)) {
            $index = 1;
            foreach ($request->input('photo', []) as $file) {
                $tempPath = storage_path('tmp/uploads/'.basename($file));
                // Validate the image dimensions
                [$width, $height] = getimagesize($tempPath);
                
                if ($width != 600 || $height != 600) {
                    foreach ($request->input('photo', []) as $file_delete) {
                        $tempPath = storage_path('tmp/uploads/'.basename($file_delete));
                        
                        // Delete the temporary file if validation fails
                        if (file_exists($tempPath)) {
                            unlink($tempPath);
                        }
                    }
                    
                    return redirect()->back()->withInput()->withErrors([
                        'photo' => __("admin.multi_image_dimensions", [
                            'expected_width' => 600, 'expected_height' => 600, 'uploaded_width' => $width,
                            'uploaded_height' => $height, 'index' => $index
                        ]),
                    ]);
                }
                $index++;
            }
        }
        
        $product = Product::create($request->all());
        
        $product->applications()->sync($request->input('applications', []));
        $product->categories()->sync($request->input('categories', []));
        $product->references()->sync($request->input('references', []));
        
        if ($request->input('main_photo', false)) {
            $product->addMedia(storage_path('tmp/uploads/'.basename($request->input('main_photo'))))->toMediaCollection('main_photo');
        }
        
        if ($request->input('photo', false)) {
            foreach ($request->input('photo', []) as $file) {
                $product->addMedia(storage_path('tmp/uploads/'.basename($file)))->toMediaCollection('photo');
            }
        }
        
        
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $product->id]);
        }
        
        return redirect()->route('admin.products.index');
    }
    
    public function edit(Product $product)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $currentLocale = app()->getLocale();
        
        $brand = Brand::find($product->brand_id);
        
        $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        
        $applications = ApplicationTranslation::where('locale', $currentLocale)->orderBy('name', 'asc')->pluck('name',
            'application_id');
        
        $categories = CategoryTranslation::where('locale', $currentLocale)->orderBy('name', 'asc')->pluck('name',
            'category_id');
        
        $references = ReferenceTranslation::where('locale', $currentLocale)->orderBy('name', 'asc')->pluck('name',
            'reference_id');
        
        //$product->load('brand', 'applications', 'categories', 'references');
        
        
        if (empty($product->name)) {
            $product->name = $this->chatGptService->translate($product->translate('en')->name, $currentLocale, 'en');
        }
        
        return view('admin.products.edit', compact('product', 'brands', 'applications', 'categories', 'references', 'brand'));
    }
    
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->all());
        
        $product->applications()->sync($request->input('applications', []));
        $product->categories()->sync($request->input('categories', []));
        $product->references()->sync($request->input('references', []));
        
        
        if ($request->input('main_photo', false)) {
            if ( ! $product->main_photo || $request->input('main_photo') !== $product->main_photo->file_name) {
                if ($product->main_photo) {
                    $product->main_photo->delete();
                }
                
                $tempPath = storage_path('tmp/uploads/'.basename($request->input('main_photo')));
                // Validate the image dimensions
                [$width, $height] = getimagesize($tempPath);
                
                if ($width != 600 || $height != 600) {
                    // Delete the temporary file if validation fails
                    if (file_exists($tempPath)) {
                        unlink($tempPath);
                    }
                    return redirect()->back()->withInput()->withErrors([
                        'main_photo' => __("admin.image_dimensions", [
                            'expected_width' => 600, 'expected_height' => 600, 'uploaded_width' => $width,
                            'uploaded_height' => $height
                        ]),
                    ]);
                }
                
                $product->addMedia($tempPath)->toMediaCollection('main_photo');
            }
        } elseif ($product->main_photo) {
            $product->main_photo->delete();
        }
        
        if (count($product->photo) > 0) {
            foreach ($product->photo as $media) {
                if ( ! in_array($media->file_name, $request->input('photo', []))) {
                    $media->delete();
                }
            }
        }
        
        $media = $product->photo->pluck('file_name')->toArray();
        $index = 1;
        foreach ($request->input('photo', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $tempPath = storage_path('tmp/uploads/'.basename($file));
                // Validate the image dimensions
                [$width, $height] = getimagesize($tempPath);
                
                if ($width != 600 || $height != 600) {
                    // Delete the temporary file if validation fails
                    if (file_exists($tempPath)) {
                        unlink($tempPath);
                    }
                    return redirect()->back()->withErrors([
                        'photo' => __("admin.multi_image_dimensions", [
                            'expected_width' => 600, 'expected_height' => 600, 'uploaded_width' => $width,
                            'uploaded_height' => $height, 'index' => $index
                        ]),
                    ]);
                }
            }
            
            $tempPath = storage_path('tmp/uploads/'.basename($file));
            if (file_exists($tempPath)) {
                $product->addMedia(storage_path('tmp/uploads/'.basename($file)))->toMediaCollection('photo');
            }
            
            $index++;
        }
        
        return redirect()->route('admin.products.edit', $product)->withErrors([]);
    }
    
    public function destroy(Product $product)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $product->delete();
        
        return back();
    }
    
    public function massDestroy(MassDestroyProductRequest $request)
    {
        $products = Product::find(request('ids'));
        
        foreach ($products as $product) {
            $product->delete();
        }
        
        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('product_create') && Gate::denies('product_edit'), Response::HTTP_FORBIDDEN,
            '403 Forbidden');
        
        $model = new Product();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');
        
        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
