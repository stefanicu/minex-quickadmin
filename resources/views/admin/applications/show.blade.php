@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.application.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.applications.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.id') }}
                        </th>
                        <td>
                            {{ $application->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.online') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $application->online ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.language') }}
                        </th>
                        <td>
                            {{ App\Models\Application::LANGUAGE_SELECT[$application->language] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.name') }}
                        </th>
                        <td>
                            {{ $application->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.slug') }}
                        </th>
                        <td>
                            {{ $application->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.image') }}
                        </th>
                        <td>
                            @if($application->image)
                                <a href="{{ $application->image->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $application->image->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.applications.index') }}">
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
            <a class="nav-link" href="#applications_categories" role="tab" data-toggle="tab">
                {{ trans('cruds.category.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="applications_categories">
            @includeIf('admin.applications.relationships.applicationsCategories', ['categories' => $application->applicationsCategories])
        </div>
    </div>
</div>

@endsection