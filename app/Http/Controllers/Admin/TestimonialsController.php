<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTestimonialRequest;
use App\Http\Requests\StoreTestimonialRequest;
use App\Http\Requests\UpdateTestimonialRequest;
use App\Models\Testimonial;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TestimonialsController extends Controller
{
    use MediaUploadingTrait;
    
    public function index(Request $request)
    {
        abort_if(Gate::denies('testimonial_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        if ($request->ajax()) {
            $query = Testimonial::with(['media', 'translations'])
                ->leftjoin('testimonial_translations', function ($join) {
                    $join->on('testimonials.id', '=', 'testimonial_translations.testimonial_id')
                        ->where('testimonial_translations.locale', '=', app()->getLocale());
                })
                ->select([
                    'testimonials.*',
                    DB::raw("COALESCE(testimonial_translations.company, '--- NO TRANSLATION ---') as company")
                ]);
            
            $table = Datatables::of($query);
            
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            
            $table->editColumn('actions', function ($row) {
                $viewGate = 'testimonial_show';
                $editGate = 'testimonial_edit';
                $deleteGate = 'testimonial_delete';
                $crudRoutePart = 'testimonials';
                
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
            //                return $row->language ? Testimonial::LANGUAGE_SELECT[$row->language] : '';
            //            });
            $table->editColumn('company', function ($row) {
                return $row->company ? $row->company : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('job', function ($row) {
                return $row->job ? $row->job : '';
            });
            $table->editColumn('logo', function ($row) {
                if ($photo = $row->logo) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="auto" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }
                
                return '';
            });
            
            $table->rawColumns(['actions', 'placeholder', 'online', 'logo']);
            
            return $table->make(true);
        }
        
        return view('admin.testimonials.index');
    }
    
    public function create()
    {
        abort_if(Gate::denies('testimonial_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        return view('admin.testimonials.create');
    }
    
    public function store(StoreTestimonialRequest $request)
    {
        if ($request->input('logo', false)) {
            $tempPath = storage_path('tmp/uploads/'.basename($request->input('logo')[0]));
            // Validate the image dimensions
            [$width, $height] = getimagesize($tempPath);
            
            if ($width != 360 || $height != 240) {
                // Delete the temporary file if validation fails
                if (file_exists($tempPath)) {
                    unlink($tempPath);
                }
                return redirect()->back()->withInput()->withErrors([
                    'logo' => __("admin.image_dimensions", [
                        'expected_width' => 360,
                        'expected_height' => 240,
                        'uploaded_width' => $width,
                        'uploaded_height' => $height
                    ]),
                ]);
            }
        }
        
        // Proceed with creating the industry only if the validation passes
        $testimonial = Testimonial::create($request->all());
        
        if ($request->input('logo', false)) {
            $testimonial->addMedia($tempPath)->toMediaCollection('logo');
        }
        
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $testimonial->id]);
        }
        
        return redirect()->route('admin.testimonials.index');
    }
    
    public function edit(Testimonial $testimonial)
    {
        abort_if(Gate::denies('testimonial_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        return view('admin.testimonials.edit', compact('testimonial'));
    }
    
    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial)
    {
        $testimonial->update($request->all());
        
        if ($request->input('logo', false)) {
            if ( ! $testimonial->logo || $request->input('logo') !== $testimonial->logo->file_name) {
                $tempPath = storage_path('tmp/uploads/'.basename($request->input('logo')));
                // Validate the image dimensions
                [$width, $height] = getimagesize($tempPath);
                
                if ($width != 360 || $height != 240) {
                    // Delete the temporary file if validation fails
                    unlink($tempPath);
                    
                    return redirect()->back()->withErrors([
                        'logo' => __("admin.image_dimensions", [
                            'expected_width' => 360,
                            'expected_height' => 240,
                            'uploaded_width' => $width,
                            'uploaded_height' => $height
                        ]),
                    ]);
                }
                
                if ($testimonial->logo) {
                    $testimonial->logo->delete();
                }
                
                $testimonial->addMedia($tempPath)->toMediaCollection('logo');
            }
        } elseif ($testimonial->logo) {
            $testimonial->logo->delete();
        }
        
        return redirect()->route('admin.testimonials.edit', $testimonial)->withInput()->withErrors([]);
    }
    
    public function destroy(Testimonial $testimonial)
    {
        abort_if(Gate::denies('testimonial_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $testimonial->delete();
        
        return back();
    }
    
    public function massDestroy(MassDestroyTestimonialRequest $request)
    {
        $testimonials = Testimonial::find(request('ids'));
        
        foreach ($testimonials as $testimonial) {
            $testimonial->delete();
        }
        
        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('testimonial_create') && Gate::denies('testimonial_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $model = new Testimonial();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');
        
        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
