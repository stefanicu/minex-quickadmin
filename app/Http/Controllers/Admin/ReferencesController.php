<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyReferenceRequest;
use App\Http\Requests\StoreReferenceRequest;
use App\Http\Requests\UpdateReferenceRequest;
use App\Models\Industry;
use App\Models\Reference;
use Gate;
use Illuminate\Http\Request;
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
            $query = Reference::with(['industries'])->select(sprintf('%s.*', (new Reference)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'reference_show';
                $editGate      = 'reference_edit';
                $deleteGate    = 'reference_delete';
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
            $table->addColumn('industries_online', function ($row) {
                return $row->industries ? $row->industries->online : '';
            });

            $table->editColumn('industries.name', function ($row) {
                return $row->industries ? (is_string($row->industries) ? $row->industries : $row->industries->name) : '';
            });
            $table->editColumn('online', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->online ? 'checked' : null) . '>';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('slug', function ($row) {
                return $row->slug ? $row->slug : '';
            });
            $table->editColumn('photo_square', function ($row) {
                if (! $row->photo_square) {
                    return '';
                }
                $links = [];
                foreach ($row->photo_square as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank"><img src="' . $media->getUrl('thumb') . '" width="50px" height="50px"></a>';
                }

                return implode(' ', $links);
            });
            $table->editColumn('photo_wide', function ($row) {
                if ($photo = $row->photo_wide) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
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

        $industries = Industry::pluck('online', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.references.create', compact('industries'));
    }

    public function store(StoreReferenceRequest $request)
    {
        $reference = Reference::create($request->all());

        foreach ($request->input('photo_square', []) as $file) {
            $reference->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photo_square');
        }

        if ($request->input('photo_wide', false)) {
            $reference->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo_wide'))))->toMediaCollection('photo_wide');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $reference->id]);
        }

        return redirect()->route('admin.references.index');
    }

    public function edit(Reference $reference)
    {
        abort_if(Gate::denies('reference_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $industries = Industry::pluck('online', 'id')->prepend(trans('global.pleaseSelect'), '');

        $reference->load('industries');

        return view('admin.references.edit', compact('industries', 'reference'));
    }

    public function update(UpdateReferenceRequest $request, Reference $reference)
    {
        $reference->update($request->all());

        if (count($reference->photo_square) > 0) {
            foreach ($reference->photo_square as $media) {
                if (! in_array($media->file_name, $request->input('photo_square', []))) {
                    $media->delete();
                }
            }
        }
        $media = $reference->photo_square->pluck('file_name')->toArray();
        foreach ($request->input('photo_square', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $reference->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photo_square');
            }
        }

        if ($request->input('photo_wide', false)) {
            if (! $reference->photo_wide || $request->input('photo_wide') !== $reference->photo_wide->file_name) {
                if ($reference->photo_wide) {
                    $reference->photo_wide->delete();
                }
                $reference->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo_wide'))))->toMediaCollection('photo_wide');
            }
        } elseif ($reference->photo_wide) {
            $reference->photo_wide->delete();
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

        $model         = new Reference();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
