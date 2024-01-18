@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.contact.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.contacts.update", [$contact->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.contact.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $contact->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.contact.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="surname">{{ trans('cruds.contact.fields.surname') }}</label>
                <input class="form-control {{ $errors->has('surname') ? 'is-invalid' : '' }}" type="text" name="surname" id="surname" value="{{ old('surname', $contact->surname) }}" required>
                @if($errors->has('surname'))
                    <span class="text-danger">{{ $errors->first('surname') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.contact.fields.surname_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.contact.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $contact->email) }}" required>
                @if($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.contact.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="job">{{ trans('cruds.contact.fields.job') }}</label>
                <input class="form-control {{ $errors->has('job') ? 'is-invalid' : '' }}" type="text" name="job" id="job" value="{{ old('job', $contact->job) }}" required>
                @if($errors->has('job'))
                    <span class="text-danger">{{ $errors->first('job') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.contact.fields.job_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="industry">{{ trans('cruds.contact.fields.industry') }}</label>
                <input class="form-control {{ $errors->has('industry') ? 'is-invalid' : '' }}" type="text" name="industry" id="industry" value="{{ old('industry', $contact->industry) }}" required>
                @if($errors->has('industry'))
                    <span class="text-danger">{{ $errors->first('industry') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.contact.fields.industry_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="how_about">{{ trans('cruds.contact.fields.how_about') }}</label>
                <input class="form-control {{ $errors->has('how_about') ? 'is-invalid' : '' }}" type="text" name="how_about" id="how_about" value="{{ old('how_about', $contact->how_about) }}" required>
                @if($errors->has('how_about'))
                    <span class="text-danger">{{ $errors->first('how_about') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.contact.fields.how_about_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="message">{{ trans('cruds.contact.fields.message') }}</label>
                <input class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}" type="text" name="message" id="message" value="{{ old('message', $contact->message) }}" required>
                @if($errors->has('message'))
                    <span class="text-danger">{{ $errors->first('message') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.contact.fields.message_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="company">{{ trans('cruds.contact.fields.company') }}</label>
                <input class="form-control {{ $errors->has('company') ? 'is-invalid' : '' }}" type="text" name="company" id="company" value="{{ old('company', $contact->company) }}">
                @if($errors->has('company'))
                    <span class="text-danger">{{ $errors->first('company') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.contact.fields.company_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="phone">{{ trans('cruds.contact.fields.phone') }}</label>
                <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="text" name="phone" id="phone" value="{{ old('phone', $contact->phone) }}" required>
                @if($errors->has('phone'))
                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.contact.fields.phone_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="country">{{ trans('cruds.contact.fields.country') }}</label>
                <input class="form-control {{ $errors->has('country') ? 'is-invalid' : '' }}" type="text" name="country" id="country" value="{{ old('country', $contact->country) }}" required>
                @if($errors->has('country'))
                    <span class="text-danger">{{ $errors->first('country') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.contact.fields.country_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="county">{{ trans('cruds.contact.fields.county') }}</label>
                <input class="form-control {{ $errors->has('county') ? 'is-invalid' : '' }}" type="text" name="county" id="county" value="{{ old('county', $contact->county) }}" required>
                @if($errors->has('county'))
                    <span class="text-danger">{{ $errors->first('county') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.contact.fields.county_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="city">{{ trans('cruds.contact.fields.city') }}</label>
                <input class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" type="text" name="city" id="city" value="{{ old('city', $contact->city) }}">
                @if($errors->has('city'))
                    <span class="text-danger">{{ $errors->first('city') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.contact.fields.city_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="checkbox">{{ trans('cruds.contact.fields.checkbox') }}</label>
                <input class="form-control {{ $errors->has('checkbox') ? 'is-invalid' : '' }}" type="text" name="checkbox" id="checkbox" value="{{ old('checkbox', $contact->checkbox) }}">
                @if($errors->has('checkbox'))
                    <span class="text-danger">{{ $errors->first('checkbox') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.contact.fields.checkbox_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product">{{ trans('cruds.contact.fields.product') }}</label>
                <input class="form-control {{ $errors->has('product') ? 'is-invalid' : '' }}" type="number" name="product" id="product" value="{{ old('product', $contact->product) }}" step="1">
                @if($errors->has('product'))
                    <span class="text-danger">{{ $errors->first('product') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.contact.fields.product_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection