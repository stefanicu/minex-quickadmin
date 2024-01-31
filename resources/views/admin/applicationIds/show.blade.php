@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.applicationId.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.application-ids.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationId.fields.id') }}
                        </th>
                        <td>
                            {{ $applicationId->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationId.fields.online') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $applicationId->online ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationId.fields.language') }}
                        </th>
                        <td>
                            {{ App\Models\ApplicationId::LANGUAGE_SELECT[$applicationId->language] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationId.fields.name') }}
                        </th>
                        <td>
                            {{ $applicationId->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationId.fields.slug') }}
                        </th>
                        <td>
                            {{ $applicationId->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationId.fields.image') }}
                        </th>
                        <td>
                            @if($applicationId->image)
                                <a href="{{ $applicationId->image->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $applicationId->image->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationId.fields.categories') }}
                        </th>
                        <td>
                            @foreach($applicationId->categories as $key => $categories)
                                <span class="label label-info">{{ $categories->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.application-ids.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection