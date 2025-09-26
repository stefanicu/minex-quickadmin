<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPageRequest;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Models\Page;
use App\Traits\HandlesTranslatableSlug;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
    use MediaUploadingTrait, HandlesTranslatableSlug;
    
    public function index(Request $request)
    {
        abort_if(Gate::denies('page_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        if ($request->ajax()) {
            $query = Page::with('translations', 'media')
                ->leftJoin('page_translations', function ($join) {
                    $join->on('pages.id', '=', 'page_translations.page_id')
                        ->where('page_translations.locale', '=', app()->getLocale());
                })
                ->selectRaw('pages.*, COALESCE(page_translations.name, "--- NO TRANSLATION ---") as name, COALESCE(page_translations.online, 0) as online');
            
            $table = Datatables::of($query);
            
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            
            $table->editColumn('actions', function ($row) {
                $viewGate = 'page_show';
                $editGate = 'page_edit';
                $deleteGate = 'page_delete';
                $crudRoutePart = 'pages';
                
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
            $table->editColumn('image', function ($row) {
                if ($photo = $row->image) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="auto" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }
                
                return '';
            });
            
            $table->rawColumns(['actions', 'placeholder', 'online', 'image']);
            
            return $table->make(true);
        }
        
        return view('admin.pages.index');
    }
    
    public function create()
    {
        abort_if(Gate::denies('page_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        return view('admin.pages.create');
    }
    
    public function store(StorePageRequest $request)
    {
        if ($request->input('image', false)) {
            $tempPath = storage_path('tmp/uploads/'.basename($request->input('image')));
            // Validate the image dimensions
            [$width, $height] = getimagesize($tempPath);
            
            if ($width != 1920 || $height != 540) {
                // Delete the temporary file if validation fails
                if (file_exists($tempPath)) {
                    unlink($tempPath);
                }
                return redirect()->back()->withInput()->withErrors([
                    'image' => __("admin.image_dimensions", [
                        'expected_width' => 1920,
                        'expected_height' => 540,
                        'uploaded_width' => $width,
                        'uploaded_height' => $height
                    ]),
                ]);
            }
        }
        
        // Proceed with creating the page only if the validation passes
        $page = new Page();
        $this->saveWithSlug($request, $page);
        
        if ($request->input('image', false)) {
            $page->addMedia(storage_path('tmp/uploads/'.basename($request->input('image'))))->toMediaCollection('image');
        }
        
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $page->id]);
        }
        
        return redirect()->route('admin.pages.edit', ['page' => $page->id]);
    }
    
    public function edit(Page $page)
    {
        abort_if(Gate::denies('page_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        //        $images = Media::where('model_id', $page->id)
        //            ->where('model_type', Page::class)
        //            ->get();
        //
        //        if(count($images) == 0) {
        //            if (file_exists(public_path().asset('uploads/images/'.$page->oldimage))) {
        //                $page->addMediaFromUrl(url('').asset('uploads/images/'.$page->oldimage))->toMediaCollection('image');
        //            }
        //        }
        return view('admin.pages.edit', compact('page'));
    }
    
    public function update(UpdatePageRequest $request, Page $page)
    {
        $this->saveWithSlug($request, $page);
        
        if ($request->input('image', false)) {
            if ( ! $page->image || $request->input('image') !== $page->image->file_name) {
                $tempPath = storage_path('tmp/uploads/'.basename($request->input('image')));
                // Validate the image dimensions
                [$width, $height] = getimagesize($tempPath);
                
                if ($width != 1920 || $height != 580) {
                    // Delete the temporary file if validation fails
                    if (file_exists($tempPath)) {
                        unlink($tempPath);
                    }
                    return redirect()->back()->withErrors([
                        'photo' => __("admin.image_dimensions", [
                            'expected_width' => 1920,
                            'expected_height' => 580,
                            'uploaded_width' => $width,
                            'uploaded_height' => $height
                        ]),
                    ]);
                }
                
                if ( ! $page->image || $request->input('image') !== $page->image->file_name) {
                    if ($page->image) {
                        $page->image->delete();
                    }
                    $page->addMedia($tempPath)->toMediaCollection('image');
                }
            }
        } elseif ($page->image) {
            $page->image->delete();
        }
        
        return redirect()->route('admin.pages.edit', $page)
            ->withInput(array_merge($request->all(), ['slug' => $page->slug]))
            ->withErrors([]);
    }
    
    public function destroy(Page $page)
    {
        abort_if(Gate::denies('page_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $page->delete();
        
        return back();
    }
    
    public function massDestroy(MassDestroyPageRequest $request)
    {
        $pages = Page::find(request('ids'));
        
        foreach ($pages as $page) {
            $page->delete();
        }
        
        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('page_create') && Gate::denies('page_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $model = new Page();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        
        // Add the uploaded media to the collection
        $media = $model->addMediaFromRequest('upload')
            ->toMediaCollection('ck-media');
        
        // Generate conversions for the uploaded media
        $media->refresh(); // Refresh media instance to ensure conversions are available
        
        $resizedPath = $media->getPath('ckeditor'); // Get the resized image's path
        
        // Ensure the resized image exists, then delete the original
        $originalPath = $media->getPath(); // Path to the original image
        if (file_exists($resizedPath) && file_exists($originalPath)) {
            unlink($originalPath); // Delete the original image
        }
        
        return response()->json([
            'id' => $media->id,
            'url' => $media->getUrl('ckeditor') // Return resized image URL
        ], Response::HTTP_CREATED);
    }
}
