<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyFilterRequest;
use App\Http\Requests\StoreFilterRequest;
use App\Http\Requests\UpdateFilterRequest;
use App\Models\ApplicationTranslation;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Filter;
use App\Traits\HandlesTranslatableSlug;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class FiltersController extends Controller
{
    use HandlesTranslatableSlug;
    
    public function index(Request $request)
    {
        abort_if(Gate::denies('filter_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        if ($request->ajax()) {
            $query = Filter::with(['translations'])
                ->leftJoin('filter_translations', function ($join) {
                    $join->on('filters.id', '=', 'filter_translations.filter_id')
                        ->where('filter_translations.locale', '=', app()->getLocale());
                })
                ->leftJoin('category_translations', function ($join) {
                    $join->on('filters.category_id', '=', 'category_translations.category_id')
                        ->where('category_translations.locale', '=', app()->getLocale());
                })
                ->select([
                    sprintf('%s.*', (new Filter)->table),
                    DB::raw("COALESCE(filter_translations.name, '---NO TRANSLATION---') as name"),
                    'category_translations.name as category_name',
                ]);
            
            $table = Datatables::of($query);
            
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            
            $table->editColumn('actions', function ($row) {
                $viewGate = 'filter_show';
                $editGate = 'filter_edit';
                $deleteGate = 'filter_delete';
                $crudRoutePart = 'filters';
                
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
            
            $table->rawColumns(['actions', 'placeholder', 'online']);
            
            return $table->make(true);
        }
        
        return view('admin.filters.index');
    }
    
    public function create()
    {
        abort_if(Gate::denies('filter_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $categories = CategoryTranslation::where('locale', app()->getLocale())->orderBy('name', 'asc')->pluck('name', 'category_id');
        
        return view('admin.filters.create', compact('categories'));
    }
    
    public function store(StoreFilterRequest $request)
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
        
        $filter = new Filter();
        $this->saveWithSlug($request, $filter);
        
        if ($request->form_category) {
            return redirect()->route('admin.categories.edit', ['category' => $request->category_id]);
        }
        
        return redirect()->route('admin.filters.edit', ['filter' => $filter->id]);
    }
    
    public function edit(Filter $filter)
    {
        abort_if(Gate::denies('filter_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $categories = CategoryTranslation::where('locale', app()->getLocale())->pluck('name', 'category_id');
        
        $category = CategoryTranslation::where('locale', app()->getLocale())->where('category_id', $filter->category_id)->first();
        
        $application_id = Category::where('id', $filter->category_id)->first()->application_id;
        
        $application = ApplicationTranslation::where('locale', app()->getLocale())->where('application_id', $application_id)->first();
        
        return view('admin.filters.edit', compact('filter', 'application', 'categories', 'category'));
    }
    
    public function update(UpdateFilterRequest $request, Filter $filter)
    {
        $this->saveWithSlug($request, $filter);
        
        return redirect()->route('admin.filters.edit', $filter)
            ->withInput(array_merge($request->all(), ['slug' => $filter->slug]))
            ->withErrors([]);
    }
    
    public function destroy(Filter $filter)
    {
        abort_if(Gate::denies('filter_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $filter->delete();
        
        return back();
    }
    
    public function massDestroy(MassDestroyFilterRequest $request)
    {
        $filters = Filter::find(request('ids'));
        
        foreach ($filters as $filter) {
            $filter->delete();
        }
        
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
