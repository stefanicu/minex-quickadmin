@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.frontPage.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.frontpages.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.frontPage.fields.id') }}
                        </th>
                        <td>
                            {{ $frontPage->id }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ App\Models\FrontPage::LANGUAGE_SELECT[$frontPage->language] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.frontPage.fields.name') }}
                        </th>
                        <td>
                            {{ $frontPage->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.frontPage.fields.first_text') }}
                        </th>
                        <td>
                            {{ $frontPage->first_text }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.frontPage.fields.second_text') }}
                        </th>
                        <td>
                            {!! $frontPage->second_text !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.frontPage.fields.quote') }}
                        </th>
                        <td>
                            {!! $frontPage->quote !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.frontPage.fields.button') }}
                        </th>
                        <td>
                            {{ $frontPage->button }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.frontPage.fields.image') }}
                        </th>
                        <td>
                            @if($frontPage->image)
                                <a href="{{ $frontPage->image->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $frontPage->image->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.frontpages.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
