@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.translationCenter.title_singular') }} -
            <span class="font-weight-bold">DB Models statistical info</span>
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
                            <th class="text-center">{{ languageToCountryCode($language) }}</th>
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
                            <td class="font-weight-bold">{{ $key=='sections' ? 'Home Page Sections' : ucfirst($key) }}</td>
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
                    {{--                    <tr>--}}
                    {{--                        <td colspan="2" class="text-right font-weight-bold">{{ __('Translate All') }}</td>--}}
                    {{--                        @foreach($availableLanguages as $locale)--}}
                    {{--                            @php--}}
                    {{--                                $toTranslateCount = $grandTotal - $totals[$locale]; // Calculate remaining translations--}}
                    {{--                            @endphp--}}
                    {{--                            <td class="text-center">--}}
                    {{--                                <form method="POST" action="{{ route('admin.translations.dbtranslate', ['locale' => $locale]) }}">--}}
                    {{--                                    @csrf--}}
                    {{--                                    <button type="submit" class="btn btn-success" @if($toTranslateCount <= 0) disabled @endif>--}}
                    {{--                                        {{ strtoupper($locale) }} ({{ $toTranslateCount }})--}}
                    {{--                                    </button>--}}
                    {{--                                </form>--}}
                    {{--                            </td>--}}
                    {{--                        @endforeach--}}
                    {{--                    </tr>--}}

                    @if(auth()->check() && auth()->user()->id === 1)
                        {{--                        <tr><td colspan="13">{{ auth()->user()->id }}</td></tr>--}}
                        <tr>
                            <td colspan="2" class="text-right font-weight-bold">{{ __('Reset All') }}</td>
                            @foreach($availableLanguages as $locale)
                                @if(!in_array($locale,['en','ro','bg']))
                                    @php
                                        $toTranslateCount = $totals[$locale]; // Calculate remaining translations
                                    @endphp
                                    <td class="text-center">
                                        <form method="POST" action="{{ route('admin.translations.dbreset', ['locale' => $locale]) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger" @if($toTranslateCount <= 0) disabled @endif>
                                                {{ languageToCountryCode($locale) }} ({{ $toTranslateCount }})
                                            </button>
                                        </form>
                                    </td>
                                @else
                                    <td></td>
                                @endif
                            @endforeach
                        </tr>

                    @endif


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
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        // Initialize Pusher and Laravel Echo
        Pusher.logToConsole = true;

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true
        });

        // Listen for TranslationProgress events
        window.Echo.channel('translation-progress').listen('.TranslationProgress', (e) => {
            const logContainer = document.getElementById('progress-log');
            const message = document.createElement('div');
            message.textContent = e.message;
            logContainer.appendChild(message);

            // Auto-scroll to the latest message
            logContainer.scrollTop = logContainer.scrollHeight;
        });
    </script>
@endsection
