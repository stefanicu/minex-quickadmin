@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.homeIndex.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.home-indices.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.homeIndex.fields.id') }}
                        </th>
                        <td>
                            {{ $homeIndex->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.homeIndex.fields.image') }}
                        </th>
                        <td>
                            @if($homeIndex->image)
                                <a href="{{ $homeIndex->image->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $homeIndex->image->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.home-indices.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#idd_homes" role="tab" data-toggle="tab">
                {{ trans('cruds.home.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="idd_homes">
            @includeIf('admin.homeIndices.relationships.iddHomes', ['homes' => $homeIndex->iddHomes])
        </div>
    </div>
</div>

@endsection