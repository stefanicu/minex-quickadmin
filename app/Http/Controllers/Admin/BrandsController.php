<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyBrandRequest;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BrandsController extends Controller
{
    use MediaUploadingTrait;
    
    public function index(Request $request)
    {
        abort_if(Gate::denies('brand_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        if ($request->ajax()) {
            $query = Brand::with(['media', 'translations'])->select(sprintf('%s.*', (new Brand)->table));
            
            $table = Datatables::of($query);
            
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            
            $table->editColumn('actions', function ($row) {
                $viewGate = 'brand_show';
                $editGate = 'brand_edit';
                $deleteGate = 'brand_delete';
                $crudRoutePart = 'brands';
                
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
            $table->editColumn('slug', function ($row) {
                return $row->slug ? $row->slug : '';
            });
            $table->editColumn('photo', function ($row) {
                if ($photo = $row->photo) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="auto" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }
                
                return '';
            });
            
            $table->rawColumns(['actions', 'placeholder', 'online', 'photo']);
            
            return $table->make(true);
        }
        
        return view('admin.brands.index');
    }
    
    public function create()
    {
        abort_if(Gate::denies('brand_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        return view('admin.brands.create');
    }
    
    public function store(StoreBrandRequest $request)
    {
        if ($request->input('photo', false)) {
            $tempPath = storage_path('tmp/uploads/'.basename($request->input('photo')[0]));
            // Validate the image dimensions
            [$width, $height] = getimagesize($tempPath);
            
            if ($width != 360 || $height != 240) {
                // Delete the temporary file if validation fails
                if (file_exists($tempPath)) {
                    unlink($tempPath);
                }
                return redirect()->back()->withInput()->withErrors([
                    'photo' => __("validation.image_dimensions", [
                        'expected_width' => 360,
                        'expected_height' => 240,
                        'uploaded_width' => $width,
                        'uploaded_height' => $height
                    ]),
                ]);
            }
        }
        
        // Proceed with creating the brand only if the validation passes
        $brand = Brand::create($request->all());
        
        if ($request->input('photo', false)) {
            $brand->addMedia($tempPath)->toMediaCollection('photo');
        }
        
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $brand->id]);
        }
        
        return redirect()->route('admin.brands.index');
    }
    
    public function edit(Brand $brand)
    {
        abort_if(Gate::denies('brand_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        return view('admin.brands.edit', compact('brand'));
    }
    
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $brand->update($request->all());
        
        if ($request->input('photo', false)) {
            if ( ! $brand->photo || $request->input('photo') !== $brand->photo->file_name) {
                $tempPath = storage_path('tmp/uploads/'.basename($request->input('photo')));
                // Validate the image dimensions
                [$width, $height] = getimagesize($tempPath);
                
                if ($width != 360 || $height != 240) {
                    // Delete the temporary file if validation fails
                    unlink($tempPath);
                    
                    return redirect()->back()->withErrors([
                        'photo' => __("validation.image_dimensions", [
                            'expected_width' => 360,
                            'expected_height' => 240,
                            'uploaded_width' => $width,
                            'uploaded_height' => $height
                        ]),
                    ]);
                }
                
                if ($brand->photo) {
                    $brand->photo->delete();
                }
                
                $brand->addMedia($tempPath)->toMediaCollection('photo');
            }
        } elseif ($brand->photo) {
            $brand->photo->delete();
        }
        
        return redirect()->route('admin.brands.index');
    }
    
    public function destroy(Brand $brand)
    {
        abort_if(Gate::denies('brand_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $brand->delete();
        
        return back();
    }
    
    public function massDestroy(MassDestroyBrandRequest $request)
    {
        $brands = Brand::find(request('ids'));
        
        foreach ($brands as $brand) {
            $brand->delete();
        }
        
        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('brand_create') && Gate::denies('brand_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $model = new Brand();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');
        
        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
