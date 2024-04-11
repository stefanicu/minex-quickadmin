<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyFronTestRequest;
use App\Http\Requests\StoreFronTestRequest;
use App\Http\Requests\UpdateFronTestRequest;
use App\Models\FronTest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class FronTestController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('fron_test_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = FronTest::query()->select(sprintf('%s.*', (new FronTest)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'fron_test_show';
                $editGate      = 'fron_test_edit';
                $deleteGate    = 'fron_test_delete';
                $crudRoutePart = 'fron-tests';

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

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.fronTests.index');
    }

    public function create()
    {
        abort_if(Gate::denies('fron_test_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.fronTests.create');
    }

    public function store(StoreFronTestRequest $request)
    {
        $fronTest = FronTest::create($request->all());

        return redirect()->route('admin.fron-tests.index');
    }

    public function edit(FronTest $fronTest)
    {
        abort_if(Gate::denies('fron_test_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.fronTests.edit', compact('fronTest'));
    }

    public function update(UpdateFronTestRequest $request, FronTest $fronTest)
    {
        $fronTest->update($request->all());

        return redirect()->route('admin.fron-tests.index');
    }

    public function show(FronTest $fronTest)
    {
        abort_if(Gate::denies('fron_test_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.fronTests.show', compact('fronTest'));
    }

    public function destroy(FronTest $fronTest)
    {
        abort_if(Gate::denies('fron_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fronTest->delete();

        return back();
    }

    public function massDestroy(MassDestroyFronTestRequest $request)
    {
        $fronTests = FronTest::find(request('ids'));

        foreach ($fronTests as $fronTest) {
            $fronTest->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
