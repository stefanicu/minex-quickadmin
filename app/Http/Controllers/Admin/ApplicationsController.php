<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyApplicationRequest;
use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Models\Application;
use App\Models\Category;
use App\Models\CategoryTranslation;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ApplicationsController extends Controller
{
    use MediaUploadingTrait;
    
    public function index(Request $request)
    {
        abort_if(Gate::denies('application_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        if ($request->ajax()) {
            $query = Application::with(['media', 'translations'])
                ->leftJoin('application_translations', function ($join) {
                    $join->on('applications.id', '=', 'application_translations.application_id')
                        ->where('application_translations.locale', '=', app()->getLocale());
                })
                ->select([
                    sprintf('%s.*', (new Application)->getTable()),
                    DB::raw("COALESCE(application_translations.name, '---NO TRANSLATION---') as name")
                ]);
            
            
            //            foreach ($query->get() as $application) {
            //                $image = Media::where('model_id', $application->id)
            //                    ->where('model_type', Application::class)
            //                    ->get();
            //
            //                if(count($image) == 0) {
            //                    if (file_exists(public_path().asset('uploads/images/'.$application->oldimage))) {
            //                        $application->addMediaFromUrl(
            //                            url('').asset('uploads/images/'.$application->oldimage)
            //                        )->toMediaCollection('image');
            //                    }
            //                }
            //            }
            
            
            $table = Datatables::of($query);
            
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            
            $table->editColumn('actions', function ($row) {
                $viewGate = 'application_show';
                $editGate = 'application_edit';
                $deleteGate = 'application_delete';
                $crudRoutePart = 'applications';
                
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
        
        return view('admin.applications.index');
    }
    
    public function create()
    {
        abort_if(Gate::denies('application_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $categories = CategoryTranslation::where('locale', app()->getLocale())->orderBy('name', 'asc')->pluck('name', 'id');
        
        return view('admin.applications.create', compact('categories'));
    }
    
    public function store(StoreApplicationRequest $request)
    {
        if ($request->input('image', false)) {
            $tempPath = storage_path('tmp/uploads/'.basename($request->input('image')));
            // Validate the image dimensions
            [$width, $height] = getimagesize($tempPath);
            
            if ($width != 1920 || $height != 580) {
                // Delete the temporary file if validation fails
                if (file_exists($tempPath)) {
                    unlink($tempPath);
                }
                return redirect()->back()->withInput()->withErrors([
                    'image' => __("admin.image_dimensions", [
                        'expected_width' => 1920,
                        'expected_height' => 580,
                        'uploaded_width' => $width,
                        'uploaded_height' => $height
                    ]),
                ]);
            }
        }
        
        $application = Application::create($request->all());
        
        if ($request->input('image', false)) {
            $application->addMedia($tempPath)->toMediaCollection('image');
        }
        
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $application->id]);
        }
        
        return redirect()->route('admin.applications.index');
    }
    
    public function edit(Application $application)
    {
        abort_if(Gate::denies('application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $categories = Category::select('categories.id as id', 'category_translations.name')
            ->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->where('category_translations.locale', app()->getLocale())
            ->where(function ($query) use ($application) {
                $query->where('categories.application_id', $application->id)
                    ->orWhereNull('categories.application_id');
            })
            ->orderBy('category_translations.name', 'asc')
            ->get();
        
        
        $application->load('categories');
        
        return view('admin.applications.edit', compact('application', 'categories'));
    }
    
    public function update(UpdateApplicationRequest $request, Application $application)
    {
        $application->update($request->all());
        
        // Get selected category IDs from the form
        $categoryIds = $request->input('categories', []);
        
        // Unassign all previous categories from this application
        Category::where('application_id', $application->id)->update(['application_id' => null]);
        
        // Assign selected categories to the application
        Category::whereIn('id', $categoryIds)->update(['application_id' => $application->id]);
        
        if ($request->input('image', false)) {
            if ( ! $application->image || $request->input('image') !== $application->image->file_name) {
                $tempPath = storage_path('tmp/uploads/'.basename($request->input('image')));
                // Validate the image dimensions
                [$width, $height] = getimagesize($tempPath);
                
                if ($width != 1920 || $height != 580) {
                    // Delete the temporary file if validation fails
                    unlink($tempPath);
                    
                    return redirect()->back()->withErrors([
                        'image' => __("admin.image_dimensions", [
                            'expected_width' => 1920,
                            'expected_height' => 580,
                            'uploaded_width' => $width,
                            'uploaded_height' => $height
                        ]),
                    ]);
                }
                
                if ($application->image) {
                    $application->image->delete();
                }
                
                $application->addMedia($tempPath)->toMediaCollection('image');
            }
        } elseif ($application->image) {
            $application->image->delete();
        }
        
        return redirect()->route('admin.applications.edit', $application)->withErrors([]);
    }
    
    public function destroy(Application $application)
    {
        abort_if(Gate::denies('application_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $application->delete();
        
        return back();
    }
    
    public function massDestroy(MassDestroyApplicationRequest $request)
    {
        $applications = Application::find(request('ids'));
        
        foreach ($applications as $application) {
            $application->delete();
        }
        
        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('application_create') && Gate::denies('application_edit'), Response::HTTP_FORBIDDEN,
            '403 Forbidden');
        
        $model = new Application();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');
        
        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
