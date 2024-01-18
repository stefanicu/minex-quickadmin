<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTranslationCenterRequest;
use App\Http\Requests\StoreTranslationCenterRequest;
use App\Http\Requests\UpdateTranslationCenterRequest;
use App\Models\TranslationCenter;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TranslationCenterController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('translation_center_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = TranslationCenter::query()->select(sprintf('%s.*', (new TranslationCenter)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'translation_center_show';
                $editGate      = 'translation_center_edit';
                $deleteGate    = 'translation_center_delete';
                $crudRoutePart = 'translation-centers';

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
                return '<input type="checkbox" disabled ' . ($row->online ? 'checked' : null) . '>';
            });
            $table->editColumn('language', function ($row) {
                return $row->language ? TranslationCenter::LANGUAGE_SELECT[$row->language] : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('slug', function ($row) {
                return $row->slug ? $row->slug : '';
            });
            $table->editColumn('section', function ($row) {
                return $row->section ? TranslationCenter::SECTION_SELECT[$row->section] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'online']);

            return $table->make(true);
        }

        return view('admin.translationCenters.index');
    }

    public function create()
    {
        abort_if(Gate::denies('translation_center_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.translationCenters.create');
    }

    public function store(StoreTranslationCenterRequest $request)
    {
        $translationCenter = TranslationCenter::create($request->all());

        return redirect()->route('admin.translation-centers.index');
    }

    public function edit(TranslationCenter $translationCenter)
    {
        abort_if(Gate::denies('translation_center_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.translationCenters.edit', compact('translationCenter'));
    }

    public function update(UpdateTranslationCenterRequest $request, TranslationCenter $translationCenter)
    {
        $translationCenter->update($request->all());

        return redirect()->route('admin.translation-centers.index');
    }

    public function show(TranslationCenter $translationCenter)
    {
        abort_if(Gate::denies('translation_center_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.translationCenters.show', compact('translationCenter'));
    }

    public function destroy(TranslationCenter $translationCenter)
    {
        abort_if(Gate::denies('translation_center_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $translationCenter->delete();

        return back();
    }

    public function massDestroy(MassDestroyTranslationCenterRequest $request)
    {
        $translationCenters = TranslationCenter::find(request('ids'));

        foreach ($translationCenters as $translationCenter) {
            $translationCenter->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
