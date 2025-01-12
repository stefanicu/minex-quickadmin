@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.translationCenter.title_singular') }}
        </div>

        <div class="card-body">

            <ul class="nav nav-tabs" id="reportTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="report1-tab" data-toggle="tab" href="#report1" role="tab" aria-controls="report1" aria-selected="true">Data Bases</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="report2-tab" data-toggle="tab" href="#report2" role="tab" aria-controls="report2" aria-selected="false">Report 2</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="report3-tab" data-toggle="tab" href="#report3" role="tab" aria-controls="report3" aria-selected="false">Report 3</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="report4-tab" data-toggle="tab" href="#report4" role="tab" aria-controls="report4" aria-selected="false">Report 4</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="report5-tab" data-toggle="tab" href="#report5" role="tab" aria-controls="report5" aria-selected="false">Report 5</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content" id="reportTabsContent">
                <div class="tab-pane fade show active mt-4" id="report1" role="tabpanel" aria-labelledby="report1-tab">

                    <div class="table-responsive">
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
                            @endphp
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



                            {{--                            <tr class="odd">--}}
                            {{--                                <td class="">{{ __('admin.translation_home') }}</td>--}}
                            {{--                                @php--}}
                            {{--                                    $firstSection = $sections->count_total; // Assuming $sections is an array and has at least one element.--}}
                            {{--                                @endphp--}}
                            {{--                                @foreach($sections as $section)--}}
                            {{--                                    <td class="text-right--}}
                            {{--                                        {{ $loop->first ? ' font-weight-bold' : '' }}--}}
                            {{--                                        {{ $firstSection > $section && $loop->iteration == 2 ? ' font-weight-bold' : '' }}--}}
                            {{--                                        {{ $firstSection > $section ? ' red' : '' }}">--}}
                            {{--                                        {{ $section }} {{ __('admin.translation_items') }}--}}
                            {{--                                    </td>--}}
                            {{--                                @endforeach--}}
                            {{--                            </tr>--}}

                            {{--                            <tr class="even">--}}
                            {{--                                <td class="">{{ __('admin.translation_applications') }}</td>--}}
                            {{--                                @php--}}
                            {{--                                    $firstApplication = $applications->count_total; // Assuming $sections is an array and has at least one element.--}}
                            {{--                                @endphp--}}
                            {{--                                @foreach($applications as $application)--}}
                            {{--                                    <td class="text-right--}}
                            {{--                                        {{ $loop->first ? ' font-weight-bold' : '' }}--}}
                            {{--                                        {{ $firstApplication > $application && $loop->iteration == 2 ? ' font-weight-bold' : '' }}--}}
                            {{--                                        {{ $firstApplication > $application ? ' red' : '' }}">--}}
                            {{--                                        {{ $application }} {{ __('admin.translation_items') }}--}}
                            {{--                                    </td>--}}
                            {{--                                @endforeach--}}
                            {{--                            </tr>--}}

                            {{--                            <tr class="odd">--}}
                            {{--                                <td class="">{{ __('admin.translation_categories') }}</td>--}}
                            {{--                                @php--}}
                            {{--                                    $firstCategory = $categories->count_total; // Assuming $sections is an array and has at least one element.--}}
                            {{--                                @endphp--}}
                            {{--                                @foreach($categories as $category)--}}
                            {{--                                    <td class="text-right--}}
                            {{--                                        {{ $loop->first ? ' font-weight-bold' : '' }}--}}
                            {{--                                        {{ $firstCategory > $category && $loop->iteration == 2 ? ' font-weight-bold' : '' }}--}}
                            {{--                                        {{ $firstCategory > $category ? ' red' : '' }}">--}}
                            {{--                                        {{ $category }} {{ __('admin.translation_items') }}--}}
                            {{--                                    </td>--}}
                            {{--                                @endforeach--}}
                            {{--                            </tr>--}}

                            {{--                            <tr class="even">--}}
                            {{--                                <td class="">{{ __('admin.translation_brands') }}</td>--}}
                            {{--                                @php--}}
                            {{--                                    $firstBrand = $brands->count_total; // Assuming $sections is an array and has at least one element.--}}
                            {{--                                @endphp--}}
                            {{--                                @foreach($brands as $brand)--}}
                            {{--                                    <td class="text-right--}}
                            {{--                                        {{ $loop->first ? ' font-weight-bold' : '' }}--}}
                            {{--                                        {{ $firstBrand > $brand && $loop->iteration == 2 ? ' font-weight-bold' : '' }}--}}
                            {{--                                        {{ $firstBrand > $brand ? ' red' : '' }}">--}}
                            {{--                                        {{ $brand }} {{ __('admin.translation_items') }}--}}
                            {{--                                    </td>--}}
                            {{--                                @endforeach--}}
                            {{--                            </tr>--}}
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="tab-pane fade" id="report2" role="tabpanel" aria-labelledby="report2-tab">
                    <h3>Report 2</h3>
                    <p>Content for Report 2 goes here.</p>
                </div>
                <div class="tab-pane fade" id="report3" role="tabpanel" aria-labelledby="report3-tab">
                    <h3>Report 3</h3>
                    <p>Content for Report 3 goes here.</p>
                </div>
                <div class="tab-pane fade" id="report4" role="tabpanel" aria-labelledby="report4-tab">
                    <h3>Report 4</h3>
                    <p>Content for Report 4 goes here.</p>
                </div>
                <div class="tab-pane fade" id="report5" role="tabpanel" aria-labelledby="report5-tab">
                    <h3>Report 5</h3>
                    <p>Content for Report 5 goes here.</p>
                </div>
            </div>

        </div>
    </div>

@endsection
@section('scripts')
    @parent
@endsection
