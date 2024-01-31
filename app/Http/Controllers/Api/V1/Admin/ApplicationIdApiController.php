<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Resources\Admin\ApplicationIdResource;
use App\Models\ApplicationId;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplicationIdApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('application_id_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ApplicationIdResource(ApplicationId::with(['categories'])->get());
    }
}
