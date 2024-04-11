@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.reference.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.references.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.reference.fields.id') }}
                        </th>
                        <td>
                            {{ $reference->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.reference.fields.online') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $reference->online ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.reference.fields.name') }}
                        </th>
                        <td>
                            {{ $reference->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.reference.fields.slug') }}
                        </th>
                        <td>
                            {{ $reference->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.reference.fields.content') }}
                        </th>
                        <td>
                            {!! $reference->content !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.reference.fields.photo_square') }}
                        </th>
                        <td>
                            @foreach($reference->photo_square as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.reference.fields.photo_wide') }}
                        </th>
                        <td>
                            @if($reference->photo_wide)
                                <a href="{{ $reference->photo_wide->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $reference->photo_wide->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.references.index') }}">
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
            <a class="nav-link" href="#references_products" role="tab" data-toggle="tab">
                {{ trans('cruds.product.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="references_products">
            @includeIf('admin.references.relationships.referencesProducts', ['products' => $reference->referencesProducts])
        </div>
    </div>
</div>

@endsection