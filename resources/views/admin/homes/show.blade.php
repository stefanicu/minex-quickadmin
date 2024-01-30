@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.home.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.homes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.home.fields.id') }}
                        </th>
                        <td>
                            {{ $home->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.home.fields.language') }}
                        </th>
                        <td>
                            {{ App\Models\Home::LANGUAGE_SELECT[$home->language] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.home.fields.name') }}
                        </th>
                        <td>
                            {{ $home->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.home.fields.first_text') }}
                        </th>
                        <td>
                            {{ $home->first_text }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.home.fields.seccond_text') }}
                        </th>
                        <td>
                            {!! $home->seccond_text !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.home.fields.quote') }}
                        </th>
                        <td>
                            {!! $home->quote !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.home.fields.button') }}
                        </th>
                        <td>
                            {{ $home->button }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.home.fields.image') }}
                        </th>
                        <td>
                            @if($home->image)
                                <a href="{{ $home->image->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $home->image->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.homes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection