@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.contact.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.contacts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.contact.fields.id') }}
                        </th>
                        <td>
                            {{ $contact->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contact.fields.name') }}
                        </th>
                        <td>
                            {{ $contact->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contact.fields.surname') }}
                        </th>
                        <td>
                            {{ $contact->surname }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contact.fields.email') }}
                        </th>
                        <td>
                            {{ $contact->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contact.fields.job') }}
                        </th>
                        <td>
                            {{ $contact->job }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contact.fields.industry') }}
                        </th>
                        <td>
                            {{ $contact->industry }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contact.fields.how_about') }}
                        </th>
                        <td>
                            {{ $contact->how_about }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contact.fields.message') }}
                        </th>
                        <td>
                            {{ $contact->message }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contact.fields.company') }}
                        </th>
                        <td>
                            {{ $contact->company }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contact.fields.phone') }}
                        </th>
                        <td>
                            {{ $contact->phone }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contact.fields.country') }}
                        </th>
                        <td>
                            {{ $contact->country }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contact.fields.county') }}
                        </th>
                        <td>
                            {{ $contact->county }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contact.fields.city') }}
                        </th>
                        <td>
                            {{ $contact->city }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contact.fields.checkbox') }}
                        </th>
                        <td>
                            {{ $contact->checkbox }}
                        </td>
                    </tr>
                    @if($product)
                        <tr>
                            <th>
                                {{ trans('cruds.contact.fields.product') }}
                            </th>
                            <td>
                                {{ $product->id }}: <a href="{{ url('') }}/{{ trans('pages_slug.product') }}/{{ $product->slug }}" target="_blank">{{ $product->name }}</a>
                            </td>
                        </tr>
                        @if($product->getMainPhotoAttribute())
                            <tr>
                                <td colspan="2">
                                    <a href="{{ url('') }}/{{ trans('pages_slugs.product') }}/{{ $product->slug }}" target="_blank">
                                        <img srcset="{{ $product->getMainPhotoAttribute()->getUrl('preview') }}" class="mx-auto lozad img-fluid" alt="No Image">
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td colspan="2">
                                HOME PAGE
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.contacts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
