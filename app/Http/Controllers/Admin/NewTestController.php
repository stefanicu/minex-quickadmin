<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyNewTestRequest;
use App\Http\Requests\StoreNewTestRequest;
use App\Http\Requests\UpdateNewTestRequest;
use App\Models\NewTest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NewTestController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('new_test_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $newTests = NewTest::all();

        return view('admin.newTests.index', compact('newTests'));
    }

    public function create()
    {
        abort_if(Gate::denies('new_test_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.newTests.create');
    }

    public function store(StoreNewTestRequest $request)
    {
        $newTest = NewTest::create($request->all());

        return redirect()->route('admin.new-tests.index');
    }

    public function edit(NewTest $newTest)
    {
        abort_if(Gate::denies('new_test_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.newTests.edit', compact('newTest'));
    }

    public function update(UpdateNewTestRequest $request, NewTest $newTest)
    {
        $newTest->update($request->all());

        return redirect()->route('admin.new-tests.index');
    }

    public function show(NewTest $newTest)
    {
        abort_if(Gate::denies('new_test_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.newTests.show', compact('newTest'));
    }

    public function destroy(NewTest $newTest)
    {
        abort_if(Gate::denies('new_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $newTest->delete();

        return back();
    }

    public function massDestroy(MassDestroyNewTestRequest $request)
    {
        $newTests = NewTest::find(request('ids'));

        foreach ($newTests as $newTest) {
            $newTest->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
