@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.translationCenter.title_singular') }} - <span class="font-weight-bold">DB Models</span>
        </div>

        <div class="card-body">

            <div class="table-responsive mt-4">
                <table class="table table-translations table-bordered table-striped table-hover my-4">
                    <thead>
                    <tr>
                        <th>{{ __('admin.translation_type') }}</th>
                        <th>{{ __('admin.translation_total_online_items_count') }}</th>
                        @foreach($availableLanguages as $language)
                            <th class="text-center">{{ strtoupper($language) }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @php $odd_even = 'odd'; @endphp
                    @foreach($data as $key => $counts)
                        <tr class="{{ $odd_even }}">
                            <td class="font-weight-bold">{{ ucfirst($key) }}</td>
                            <td class="text-right font-weight-bold">{{ $counts->count_total }} {{ __('admin.translation_items') }}</td>
                            @foreach($availableLanguages as $locale)
                                <td class="text-right
                                    {{ $counts->count_total > $counts->{"count_{$locale}"} && $loop->first ? ' font-weight-bold danger' : '' }}
                                    {{ $counts->count_total > $counts->{"count_{$locale}"} ? ' red' : ' green' }}">
                                    {{ $counts->{"count_{$locale}"} ?? 0 }} {{ __('admin.translation_items') }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection
@section('scripts')
    @parent
@endsection
