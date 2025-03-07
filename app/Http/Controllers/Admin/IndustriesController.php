<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyIndustryRequest;
use App\Http\Requests\StoreIndustryRequest;
use App\Http\Requests\UpdateIndustryRequest;
use App\Models\Industry;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class IndustriesController extends Controller
{
    use MediaUploadingTrait;
    
    public function index(Request $request)
    {
        abort_if(Gate::denies('industry_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        if ($request->ajax()) {
            //            $query = Industry::with(['translations', 'media'])
            //                ->leftjoin('industry_translations', 'industries.id', '=', 'industry_translations.industry_id')
            //                ->where('industry_translations.locale', '=', app()->getLocale())
            //                ->select(sprintf('%s.*', (new Industry)->table));
            
            $query = Industry::with(['translations', 'media'])
                ->leftJoin('industry_translations', function ($join) {
                    $join->on('industries.id', '=', 'industry_translations.industry_id')
                        ->where('industry_translations.locale', '=', app()->getLocale());
                })
                ->select(
                    'industries.*', 'slug',
                    DB::raw("COALESCE(industry_translations.name, '--- NO TRANSLATION ---') as name")
                );
            
            $table = Datatables::of($query);
            
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            
            $table->editColumn('actions', function ($row) {
                $viewGate = 'industry_show';
                $editGate = 'industry_edit';
                $deleteGate = 'industry_delete';
                $crudRoutePart = 'industries';
                
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
            //            $table->editColumn('language', function ($row) {
            //                return $row->language ? Industry::LANGUAGE_SELECT[$row->language] : '';
            //            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('slug', function ($row) {
                return $row->slug ? $row->slug : '';
            });
            $table->editColumn('photo', function ($row) {
                if ($photo = $row->photo) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }
                
                return '';
            });
            
            $table->rawColumns(['actions', 'placeholder', 'online', 'photo']);
            
            return $table->make(true);
        }
        
        return view('admin.industries.index');
    }
    
    public function create()
    {
        abort_if(Gate::denies('industry_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        return view('admin.industries.create');
    }
    
    public function store(StoreIndustryRequest $request)
    {
        if ($request->input('photo', false)) {
            $tempPath = storage_path('tmp/uploads/'.basename($request->input('photo')));
            // Validate the image dimensions
            [$width, $height] = getSvgDimensions($tempPath);
            
            if ($width != 80 || $height != 80) {
                // Delete the temporary file if validation fails
                if (file_exists($tempPath)) {
                    unlink($tempPath);
                }
                return redirect()->back()->withInput()->withErrors([
                    'photo' => __("admin.image_dimensions", [
                        'expected_width' => 80,
                        'expected_height' => 80,
                        'uploaded_width' => $width,
                        'uploaded_height' => $height
                    ]),
                ]);
            }
        }
        
        // Proceed with creating the industry only if the validation passes
        $industry = Industry::create($request->all());
        
        if ($request->input('photo', false)) {
            $industry->addMedia(storage_path('tmp/uploads/'.basename($request->input('photo'))))->toMediaCollection('photo');
        }
        
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $industry->id]);
        }
        
        return redirect()->route('admin.industries.index');
    }
    
    public function edit(Industry $industry)
    {
        abort_if(Gate::denies('industry_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        return view('admin.industries.edit', compact('industry'));
    }
    
    public function update(UpdateIndustryRequest $request, Industry $industry)
    {
        $industry->update($request->all());
        
        if ($request->input('photo', false)) {
            if ( ! $industry->photo || $request->input('photo') !== $industry->photo->file_name) {
                $tempPath = storage_path('tmp/uploads/'.basename($request->input('photo')));
                // Validate the image dimensions
                [$width, $height] = getSvgDimensions($tempPath);
                
                if ($width != 80 || $height != 80) {
                    // Delete the temporary file if validation fails
                    if (file_exists($tempPath)) {
                        unlink($tempPath);
                    }
                    return redirect()->back()->withErrors([
                        'photo' => __("admin.image_dimensions", [
                            'expected_width' => 80,
                            'expected_height' => 80,
                            'uploaded_width' => $width,
                            'uploaded_height' => $height
                        ]),
                    ]);
                }
                
                if ($industry->photo) {
                    $industry->photo->delete();
                }
                $industry->addMedia($tempPath)->toMediaCollection('photo');
            }
        } elseif ($industry->photo) {
            $industry->photo->delete();
        }
        
        return redirect()->route('admin.industries.edit', $industry)->withInput()->withErrors([]);
    }
    
    public function destroy(Industry $industry)
    {
        abort_if(Gate::denies('industry_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $industry->delete();
        
        return back();
    }
    
    public function massDestroy(MassDestroyIndustryRequest $request)
    {
        $industries = Industry::find(request('ids'));
        
        foreach ($industries as $industry) {
            $industry->delete();
        }
        
        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('industry_create') && Gate::denies('industry_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $model = new Industry();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');
        
        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
