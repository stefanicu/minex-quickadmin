@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.translationCenter.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.translation-centers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.translationCenter.fields.id') }}
                        </th>
                        <td>
                            {{ $translationCenter->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.translationCenter.fields.online') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $translationCenter->online ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.translationCenter.fields.language') }}
                        </th>
                        <td>
                            {{ App\Models\TranslationCenter::LANGUAGE_SELECT[$translationCenter->language] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.translationCenter.fields.name') }}
                        </th>
                        <td>
                            {{ $translationCenter->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.translationCenter.fields.slug') }}
                        </th>
                        <td>
                            {{ $translationCenter->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.translationCenter.fields.section') }}
                        </th>
                        <td>
                            {{ App\Models\TranslationCenter::SECTION_SELECT[$translationCenter->section] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.translation-centers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection