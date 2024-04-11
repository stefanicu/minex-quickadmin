<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFronTestRequest;
use App\Http\Requests\UpdateFronTestRequest;
use App\Http\Resources\Admin\FronTestResource;
use App\Models\FronTest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FronTestApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('fron_test_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new FronTestResource(FronTest::all());
    }

    public function store(StoreFronTestRequest $request)
    {
        $fronTest = FronTest::create($request->all());

        return (new FronTestResource($fronTest))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(FronTest $fronTest)
    {
        abort_if(Gate::denies('fron_test_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new FronTestResource($fronTest);
    }

    public function update(UpdateFronTestRequest $request, FronTest $fronTest)
    {
        $fronTest->update($request->all());

        return (new FronTestResource($fronTest))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(FronTest $fronTest)
    {
        abort_if(Gate::denies('fron_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fronTest->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
