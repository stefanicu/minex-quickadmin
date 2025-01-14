@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.translationCenter.title_singular') }} - <span class="font-weight-bold">DB Models</span>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif
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
                    @php
                        $odd_even = 'odd';
                        $totals = array_fill_keys($availableLanguages, 0); // Initialize totals for each language
                        $grandTotal = 0; // Initialize grand total of all rows
                    @endphp

                    @foreach($data as $key => $counts)
                        <tr class="{{ $odd_even }}">
                            <td class="font-weight-bold">{{ ucfirst($key) }}</td>
                            <td class="text-right font-weight-bold">{{ $counts->count_total }} {{ __('admin.translation_items') }}</td>
                            @foreach($availableLanguages as $locale)
                                <td class="text-right
                        {{ $counts->count_total > $counts->{"count_{$locale}"} && $loop->first ? ' font-weight-bold danger' : '' }}
                        {{ $counts->count_total > $counts->{"count_{$locale}"} ? ' red' : ' green' }}">
                                    @php
                                        $totals[$locale] += $counts->{"count_{$locale}"} ?? 0; // Add to totals
                                    @endphp
                                    {{ $counts->{"count_{$locale}"} ?? 0 }}
                                </td>
                            @endforeach
                            @php $grandTotal += $counts->count_total; @endphp
                        </tr>
                        @php $odd_even = ($odd_even === 'odd') ? 'even' : 'odd'; @endphp
                    @endforeach


                    <!-- Buttons Row -->
                    <tr>
                        <td colspan="2" class="text-right font-weight-bold">{{ __('Translate All') }}</td>
                        @foreach($availableLanguages as $locale)
                            @php
                                $toTranslateCount = $grandTotal - $totals[$locale]; // Calculate remaining translations
                            @endphp
                            <td class="text-center">
                                <form method="POST" action="{{ route('admin.translations.dbtranslate', ['locale' => $locale]) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success" @if($toTranslateCount <= 0) disabled @endif>
                                        {{ strtoupper($locale) }} ({{ $toTranslateCount }})
                                    </button>
                                </form>
                            </td>
                        @endforeach
                    </tr>


                    <!-- Totals Row -->
                    <tr class="font-weight-bold">
                        <td class="text-right">{{ __('Total') }}</td>
                        <td class="text-right">{{ $grandTotal }} {{ __('admin.translation_items') }}</td>
                        @foreach($availableLanguages as $locale)
                            <td class="text-right">{{ $totals[$locale] }}</td>
                        @endforeach
                    </tr>


                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection
@section('scripts')
    @parent
@endsection
