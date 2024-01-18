@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.industry.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.industries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.industry.fields.id') }}
                        </th>
                        <td>
                            {{ $industry->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.industry.fields.online') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $industry->online ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.industry.fields.language') }}
                        </th>
                        <td>
                            {{ App\Models\Industry::LANGUAGE_SELECT[$industry->language] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.industry.fields.name') }}
                        </th>
                        <td>
                            {{ $industry->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.industry.fields.slug') }}
                        </th>
                        <td>
                            {{ $industry->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.industry.fields.photo') }}
                        </th>
                        <td>
                            @if($industry->photo)
                                <a href="{{ $industry->photo->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $industry->photo->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.industries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection