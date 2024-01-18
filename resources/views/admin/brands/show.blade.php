@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.brand.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.brands.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.brand.fields.id') }}
                        </th>
                        <td>
                            {{ $brand->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.brand.fields.online') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $brand->online ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.brand.fields.language') }}
                        </th>
                        <td>
                            {{ App\Models\Brand::LANGUAGE_SELECT[$brand->language] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.brand.fields.name') }}
                        </th>
                        <td>
                            {{ $brand->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.brand.fields.slug') }}
                        </th>
                        <td>
                            {{ $brand->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.brand.fields.photo') }}
                        </th>
                        <td>
                            @if($brand->photo)
                                <a href="{{ $brand->photo->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $brand->photo->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.brands.index') }}">
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
            <a class="nav-link" href="#brand_products" role="tab" data-toggle="tab">
                {{ trans('cruds.product.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="brand_products">
            @includeIf('admin.brands.relationships.brandProducts', ['products' => $brand->brandProducts])
        </div>
    </div>
</div>

@endsection