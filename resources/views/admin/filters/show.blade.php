@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.filter.title') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.filters.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.filter.fields.id') }}
                        </th>
                        <td>
                            {{ $filter->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.filter.fields.online') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $filter->online ? 'checked' : '' }}>
                        </td>
                    </tr>

                    <tr>
                        <th>
                            {{ trans('cruds.filter.fields.name') }}
                        </th>
                        <td>
                            {{ $filter->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.filter.fields.slug') }}
                        </th>
                        <td>
                            {{ $filter->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.filter.fields.product_image') }}
                        </th>
                        <td>
                            {{ $filter->product_image->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.filter.fields.categories') }}
                        </th>
                        <td>
                            @foreach($filter->categories as $key => $categories)
                                <span class="label label-info">{{ $categories->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.filters.index') }}">
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
                <a class="nav-link" href="#filters_products" role="tab" data-toggle="tab">
                    {{ trans('cruds.product.title') }}
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane" role="tabpanel" id="filters_products">
                @includeIf('admin.filters.relationships.filtersProducts', ['filter' => $filter->filtersProducts])
            </div>
        </div>
    </div>

@endsection
