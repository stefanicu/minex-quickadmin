<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyBlogRequest;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    use MediaUploadingTrait;
    
    public function index(Request $request)
    {
        abort_if(Gate::denies('blog_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        if ($request->ajax()) {
            $query = Blog::with('translations', 'media')
                ->leftJoin('blog_translations', function ($join) {
                    $join->on('blogs.id', '=', 'blog_translations.blog_id')
                        ->where('blog_translations.locale', '=', app()->getLocale());
                })
                ->where(function ($q) {
                    $q->where('oldarticletype', '!=', 'Page')
                        ->orWhereNull('oldarticletype');
                })
                ->selectRaw('blogs.*, COALESCE(blog_translations.name, "--- NO TRANSLATION ---") as name, COALESCE(blog_translations.online, 0) as online');
            
            $table = Datatables::of($query);
            
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            
            $table->editColumn('actions', function ($row) {
                $viewGate = 'blog_show';
                $editGate = 'blog_edit';
                $deleteGate = 'blog_delete';
                $crudRoutePart = 'blogs';
                
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
        
        return view('admin.blogs.index');
    }
    
    public function create()
    {
        abort_if(Gate::denies('blog_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        return view('admin.blogs.create');
    }
    
    public function store(StoreBlogRequest $request)
    {
        if ($request->input('image', false)) {
            $tempPath = storage_path('tmp/uploads/'.basename($request->input('image')));
            // Validate the image dimensions
            [$width, $height] = getimagesize($tempPath);
            
            if ($width != 750 || $height != 500) {
                // Delete the temporary file if validation fails
                if (file_exists($tempPath)) {
                    unlink($tempPath);
                }
                return redirect()->back()->withInput()->withErrors([
                    'image' => __("validation.image_dimensions", [
                        'expected_width' => 750,
                        'expected_height' => 500,
                        'uploaded_width' => $width,
                        'uploaded_height' => $height
                    ]),
                ]);
            }
        }
        
        // Proceed with creating the blog only if the validation passes
        $blog = Blog::create($request->all());
        
        if ($request->input('image', false)) {
            $blog->addMedia(storage_path('tmp/uploads/'.basename($request->input('image'))))->toMediaCollection('image');
        }
        
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $blog->id]);
        }
        
        return redirect()->route('admin.blogs.index');
    }
    
    public function edit(Blog $blog)
    {
        abort_if(Gate::denies('blog_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $images = Media::where('model_id', $blog->id)
//            ->where('model_type', Blog::class)
//            ->get();
//
//        if(count($images) == 0) {
//            if (file_exists(public_path().asset('uploads/images/'.$blog->oldimage))) {
//                $blog->addMediaFromUrl(url('').asset('uploads/images/'.$blog->oldimage))->toMediaCollection('image');
//            }
//        }
        return view('admin.blogs.edit', compact('blog'));
    }
    
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $blog->update($request->all());
        
        if ($request->input('image', false)) {
            if ( ! $blog->image || $request->input('image') !== $blog->image->file_name) {
                $tempPath = storage_path('tmp/uploads/'.basename($request->input('image')));
                // Validate the image dimensions
                [$width, $height] = getimagesize($tempPath);
                
                if ($width != 750 || $height != 500) {
                    // Delete the temporary file if validation fails
                    if (file_exists($tempPath)) {
                        unlink($tempPath);
                    }
                    return redirect()->back()->withErrors([
                        'photo' => __("validation.image_dimensions", [
                            'expected_width' => 750,
                            'expected_height' => 500,
                            'uploaded_width' => $width,
                            'uploaded_height' => $height
                        ]),
                    ]);
                }
                
                if ( ! $blog->image || $request->input('image') !== $blog->image->file_name) {
                    if ($blog->image) {
                        $blog->image->delete();
                    }
                    $blog->addMedia($tempPath)->toMediaCollection('image');
                }
            }
        } elseif ($blog->image) {
            $blog->image->delete();
        }
        
        return redirect()->route('admin.blogs.index');
    }
    
    public function destroy(Blog $blog)
    {
        abort_if(Gate::denies('blog_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $blog->delete();
        
        return back();
    }
    
    public function massDestroy(MassDestroyBlogRequest $request)
    {
        $blogs = Blog::find(request('ids'));
        
        foreach ($blogs as $blog) {
            $blog->delete();
        }
        
        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('blog_create') && Gate::denies('blog_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $model = new Blog();
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
