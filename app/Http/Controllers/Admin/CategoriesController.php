<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\ApplicationTranslation;
use App\Models\Category;
use App\Models\Filter;
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
            $query = Category::with(['media', 'translations'])
                ->leftJoin('category_translations', function ($join) {
                    $join->on('categories.id', '=', 'category_translations.category_id')
                        ->where('category_translations.locale', '=', app()->getLocale());
                })
                ->leftJoin('application_translations', function ($join) {
                    $join->on('categories.application_id', '=', 'application_translations.application_id')
                        ->where('application_translations.locale', '=', app()->getLocale());
                })
                ->select([
                    sprintf('%s.*', (new Category)->table),
                    DB::raw("COALESCE(category_translations.name, '---NO TRANSLATION---') as name"),
                    'application_translations.name as application_name',
                ]);
            
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
        
        $applications = ApplicationTranslation::where('locale', app()->getLocale())->orderBy('name', 'asc')->pluck('name', 'application_id');
        
        return view('admin.categories.create', compact('applications'));
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
        
        if ($request->form_application) {
            return redirect()->route('admin.applications.edit', ['application' => $request->application_id]);
        }
        
        return redirect()->route('admin.categories.index');
    }
    
    public function edit(Category $category)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $filters = Filter::select('filters.id as id', 'filter_translations.name')
            ->join('filter_translations', 'filters.id', '=', 'filter_translations.filter_id')
            ->where('filter_translations.locale', app()->getLocale())
            ->where(function ($query) use ($category) {
                $query->where('filters.category_id', $category->id)
                    ->orWhereNull('filters.category_id');
            })
            ->orderBy('filter_translations.name', 'asc')
            ->get();
        
        $applications = ApplicationTranslation::where('locale', app()->getLocale())->pluck('name', 'application_id');
        
        $application = ApplicationTranslation::where('locale', app()->getLocale())->where('application_id', $category->application_id)->first();
        
        $category->load('filters');
        
        return view('admin.categories.edit', compact('category', 'filters', 'applications', 'application'));
    }
    
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->all());
        
        // Get selected category IDs from the form
        $filterIds = $request->input('filters', []);
        
        // Unassign all previous categories from this application
        Filter::where('category_id', $category->id)->update(['category_id' => null]);
        
        // Assign selected categories to the category
        Filter::whereIn('id', $filterIds)->update(['category_id' => $category->id]);
        
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
