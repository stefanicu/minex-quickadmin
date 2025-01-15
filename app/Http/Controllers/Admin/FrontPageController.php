<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\UpdateFrontPageRequest;
use App\Models\FrontPage;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class FrontPageController extends Controller
{
    use MediaUploadingTrait;
    
    public function index(Request $request)
    {
        abort_if(Gate::denies('front_page_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        if ($request->ajax()) {
            $query = FrontPage::join('front_page_translations', 'front_pages.id', '=', 'front_page_translations.front_page_id')
                ->where('front_page_translations.locale', '=', app()->getLocale())
                ->select(sprintf('%s.*', (new FrontPage)->table));
            
            $table = Datatables::of($query);
            
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            
            $table->editColumn('actions', function ($row) {
                $viewGate = 'front_page_show';
                $editGate = 'front_page_edit';
                $deleteGate = 'front_page_delete';
                $crudRoutePart = 'front_pages';
                
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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('first_text', function ($row) {
                return $row->first_text ? $row->first_text : '';
            });
            $table->editColumn('button', function ($row) {
                return $row->button ? $row->button : '';
            });
            $table->editColumn('image', function ($row) {
                if ($photo = $row->image) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }
                
                return '';
            });
            
            $table->rawColumns(['actions', 'placeholder', 'image']);
            
            return $table->make(true);
        }
        
        return view('admin.frontPages.index');
    }
    
    public function edit(FrontPage $frontPage)
    {
        abort_if(Gate::denies('front_page_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        return view('admin.frontPages.edit', compact('frontPage'));
    }
    
    public function update(UpdateFrontPageRequest $request, FrontPage $frontPage)
    {
        $frontPage->update($request->all());
        
        if ($request->input('image', false)) {
            if ( ! $frontPage->image || $request->input('image') !== $frontPage->image->file_name) {
                if ($frontPage->image) {
                    $frontPage->image->delete();
                }
                $frontPage->addMedia(storage_path('tmp/uploads/'.basename($request->input('image'))))->toMediaCollection('image');
            }
        } elseif ($frontPage->image) {
            $frontPage->image->delete();
        }
        
        return redirect()->route('admin.front_pages.edit', $frontPage)->withInput()->withErrors([]);
    }
    
    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('front_page_create') && Gate::denies('front_page_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $model = new FrontPage();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');
        
        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
