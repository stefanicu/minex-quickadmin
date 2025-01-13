<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyReferenceRequest;
use App\Http\Requests\StoreReferenceRequest;
use App\Http\Requests\UpdateReferenceRequest;
use App\Models\IndustryTranslation;
use App\Models\Reference;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ReferencesController extends Controller
{
    use MediaUploadingTrait;
    
    public function index(Request $request)
    {
        abort_if(Gate::denies('reference_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        if ($request->ajax()) {
            $query = Reference::with(['translations', 'media'])
                ->leftJoin('reference_translations', function ($join) {
                    $join->on('references.id', '=', 'reference_translations.reference_id')
                        ->where('reference_translations.locale', '=', app()->getLocale());
                })
                ->select([
                    'references.*',
                    DB::raw("COALESCE(reference_translations.name, '--- NO TRANSLATION ---') as name")
                ]);
            
            $table = Datatables::of($query);
            
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            
            $table->editColumn('actions', function ($row) {
                $viewGate = 'reference_show';
                $editGate = 'reference_edit';
                $deleteGate = 'reference_delete';
                $crudRoutePart = 'references';
                
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
            //            $table->addColumn('industries_online', function ($row) {
            //                return $row->industries ? $row->industries->online : '';
            //            });
            //
            //            $table->editColumn('industries.name', function ($row) {
            //                return $row->industries ? (is_string($row->industries) ? $row->industries : $row->industries->name) : '';
            //            });
            $table->editColumn('online', function ($row) {
                return '<input type="checkbox" disabled '.($row->online ? 'checked' : null).'>';
            });
            //            $table->editColumn('language', function ($row) {
            //                return $row->language ? Reference::LANGUAGE_SELECT[$row->language] : '';
            //            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('slug', function ($row) {
                return $row->slug ? $row->slug : '';
            });
            $table->editColumn('photo_square', function ($row) {
                if ( ! $row->photo_square) {
                    return '';
                }
                $links = [];
                foreach ($row->photo_square as $media) {
                    $links[] = '<a href="'.$media->getUrl().'" target="_blank"><img src="'.$media->getUrl('thumb').'" width="50px" height="50px"></a>';
                }
                
                return implode(' ', $links);
            });
            $table->editColumn('photo_wide', function ($row) {
                if ($photo = $row->photo_wide) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="auto" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }
                
                return '';
            });
            
            $table->rawColumns(['actions', 'placeholder', 'industries', 'online', 'photo_square', 'photo_wide']);
            
            return $table->make(true);
        }
        
        return view('admin.references.index');
    }
    
    public function create()
    {
        abort_if(Gate::denies('reference_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $industries = IndustryTranslation::where('locale', '=', app()->getLocale())->orderBy('name')->pluck('name', 'industry_id')->prepend(trans('global.pleaseSelect'), '');
        
        return view('admin.references.create', compact('industries'));
    }
    
    public function store(StoreReferenceRequest $request)
    {
        if ($request->input('photo_wide', false)) {
            $tempPath = storage_path('tmp/uploads/'.basename($request->input('photo_wide')));
            // Validate the image dimensions
            [$width, $height] = getimagesize($tempPath);
            
            if ($width != 750 || $height != 300) {
                // Delete the temporary file if validation fails
                if (file_exists($tempPath)) {
                    unlink($tempPath);
                }
                return redirect()->back()->withInput()->withErrors([
                    'photo_wide' => __("admin.image_dimensions", [
                        'expected_width' => 750,
                        'expected_height' => 300,
                        'uploaded_width' => $width,
                        'uploaded_height' => $height
                    ]),
                ]);
            }
        }
        
        if ($request->input('photo_square', false)) {
            $index = 1;
            foreach ($request->input('photo_square', []) as $file) {
                $tempPath = storage_path('tmp/uploads/'.basename($file));
                // Validate the image dimensions
                [$width, $height] = getimagesize($tempPath);
                
                if ($width != 360 || $height != 300) {
                    foreach ($request->input('photo_square', []) as $file_delete) {
                        $tempPath = storage_path('tmp/uploads/'.basename($file_delete));
                        
                        // Delete the temporary file if validation fails
                        if (file_exists($tempPath)) {
                            unlink($tempPath);
                        }
                    }
                    
                    return redirect()->back()->withInput()->withErrors([
                        'photo_square' => __("admin.multi_image_dimensions", [
                            'expected_width' => 360,
                            'expected_height' => 300,
                            'uploaded_width' => $width,
                            'uploaded_height' => $height,
                            'index' => $index
                        ]),
                    ]);
                }
                $index++;
            }
        }
        
        $reference = Reference::create($request->all());
        
        if ($request->input('photo_wide', false)) {
            $reference->addMedia(storage_path('tmp/uploads/'.basename($request->input('photo_wide'))))->toMediaCollection('photo_wide');
        }
        
        if ($request->input('photo_square', false)) {
            foreach ($request->input('photo_square', []) as $file) {
                $reference->addMedia(storage_path('tmp/uploads/'.basename($file)))->toMediaCollection('photo_square');
            }
        }
        
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $reference->id]);
        }
        
        return redirect()->route('admin.references.index');
    }
    
    public function edit(Reference $reference)
    {
        abort_if(Gate::denies('reference_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $industries = IndustryTranslation::where('locale', '=', app()->getLocale())->orderBy('name')->pluck('name', 'industry_id')->prepend(trans('global.pleaseSelect'), '');
        
        $reference->load('industries');
        
        return view('admin.references.edit', compact('industries', 'reference'));
    }
    
    public function update(UpdateReferenceRequest $request, Reference $reference)
    {
        $reference->update($request->all());
        
        if ($request->input('photo_wide', false)) {
            if ( ! $reference->photo_wide || $request->input('photo_wide') !== $reference->photo_wide->file_name) {
                if ($reference->photo_wide) {
                    $reference->photo_wide->delete();
                }
                
                $tempPath = storage_path('tmp/uploads/'.basename($request->input('photo_wide')));
                // Validate the image dimensions
                [$width, $height] = getimagesize($tempPath);
                
                if ($width != 750 || $height != 300) {
                    // Delete the temporary file if validation fails
                    if (file_exists($tempPath)) {
                        unlink($tempPath);
                    }
                    return redirect()->back()->withInput()->withErrors([
                        'photo_wide' => __("admin.image_dimensions", [
                            'expected_width' => 750,
                            'expected_height' => 300,
                            'uploaded_width' => $width,
                            'uploaded_height' => $height
                        ]),
                    ]);
                }
                
                $reference->addMedia($tempPath)->toMediaCollection('photo_wide');
            }
        } elseif ($reference->photo_wide) {
            $reference->photo_wide->delete();
        }
        
        if (count($reference->photo_square) > 0) {
            foreach ($reference->photo_square as $media) {
                if ( ! in_array($media->file_name, $request->input('photo_square', []))) {
                    $media->delete();
                }
            }
        }
        
        $media = $reference->photo_square->pluck('file_name')->toArray();
        
        $index = 1;
        foreach ($request->input('photo_square', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $tempPath = storage_path('tmp/uploads/'.basename($file));
                // Validate the image dimensions
                [$width, $height] = getimagesize($tempPath);
                
                if ($width != 360 || $height != 300) {
                    // Delete the temporary file if validation fails
                    if (file_exists($tempPath)) {
                        unlink($tempPath);
                    }
                    return redirect()->back()->withErrors([
                        'photo_square' => __("admin.multi_image_dimensions", [
                            'expected_width' => 360,
                            'expected_height' => 300,
                            'uploaded_width' => $width,
                            'uploaded_height' => $height,
                            'index' => $index
                        ]),
                    ]);
                }
            }
            
            $tempPath = storage_path('tmp/uploads/'.basename($file));
            if (file_exists($tempPath)) {
                $reference->addMedia(storage_path('tmp/uploads/'.basename($file)))->toMediaCollection('photo_square');
            }
            
            $index++;
        }
        
        return redirect()->route('admin.references.index');
    }
    
    public function destroy(Reference $reference)
    {
        abort_if(Gate::denies('reference_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $reference->delete();
        
        return back();
    }
    
    public function massDestroy(MassDestroyReferenceRequest $request)
    {
        $references = Reference::find(request('ids'));
        
        foreach ($references as $reference) {
            $reference->delete();
        }
        
        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('reference_create') && Gate::denies('reference_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $model = new Reference();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');
        
        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
