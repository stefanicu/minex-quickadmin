@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.translationCenter.title_singular') }} - <span class="font-weight-bold">Static Strings</span>
        </div>

        <div class="card-body">
            <div class="mt-4">
                <!-- Show success message if any -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <ul class="nav nav-tabs" id="languageTabs">
                    @foreach($languages as $lang)
                        <li class="nav-item">
                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" href="#{{ $lang }}" data-toggle="tab" data-tab-id="{{ $lang }}">
                                {{ strtoupper($lang) }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="form-group my-4 flex">
                    <label for="file-select mt-4">Select File:</label>
                    <select id="file-select" class="form-control" onchange="location.href='?file='+this.value;">
                        <option value="">-- Select File --</option>
                        @foreach($translations[$languages[0]] as $file => $keys)
                            <option value="{{ $file }}"
                                    {{ request('file') == $file || (!request('file') && $loop->first) ? 'selected' : '' }}>
                                {{ $file }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if(request('file'))
                    <div class="tab-content mt-3">
                        @foreach($languages as $lang)
                            <div class="tab-pane fade @if($loop->first) show active @endif" id="{{ $lang }}">
                                <h4>{{ ucfirst($lang) }}</h4>
                                <form method="POST" action="{{ route('admin.translation.strings.update', $lang) }}">
                                    @csrf
                                    <input type="hidden" name="file" value="{{ request('file') }}">

                                    @php
                                        // Count empty values for the current language
                                        $emptyCount = 0;
                                    @endphp
                                    @foreach($translations[$lang][request('file')] as $key => $value)
                                        <div class="form-group">
                                            <label>{{ $key }}</label>
                                            <input type="text" name="translations[{{ $key }}]" value="{{ $value }}" class="form-control">
                                        </div>
                                        @php
                                            if($value == ''){
                                                $emptyCount++;
                                            }
                                        @endphp
                                    @endforeach

                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                                @if($lang !== 'en')
                                    {{-- Skip English --}}
                                    <form id="translateButtonForm" method="POST" action="{{ route('admin.translations.translate', $lang) }}" class="absolute top-1">
                                        @csrf
                                        <input type="hidden" name="file" id="file" value="{{ $_GET['file'] }}">
                                        <button type="submit" class="btn btn-success" onclick="location.href='{{ route('admin.translations.translate', $lang) }}'" @if($emptyCount === 0) disabled @endif >
                                            Translate to {{ strtoupper($lang) }} ({{ $emptyCount }} empty {{ $emptyCount === 1 ? 'field' : 'fileds' }})
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach

                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const languageTabs = document.querySelectorAll("#languageTabs .nav-link");
            const translateButtonForm = document.getElementById('translateButtonForm');
            const loadingSpinner = document.getElementById('loading-spinner');

            // Redirect to the first file if no file is selected and translations exist
            @if(!request('file') && count($translations[$languages[0]]) > 0)
                window.location.href = '?file={{ array_key_first($translations[$languages[0]]) }}';
            @endif

            // Restore the active tab from localStorage
            const activeTabId = localStorage.getItem("activeTabId");
            if (activeTabId) {
                languageTabs.forEach(tab => {
                    tab.classList.remove("active");
                    document.querySelector(tab.getAttribute("href")).classList.remove("show", "active");
                });
                const activeTab = document.querySelector(`[href="#${activeTabId}"]`);
                const activeTabPane = document.querySelector(`#${activeTabId}`);
                if (activeTab && activeTabPane) {
                    activeTab.classList.add("active");
                    activeTabPane.classList.add("show", "active");
                }
            }

            // Save the active tab to localStorage on click
            languageTabs.forEach(tab => {
                tab.addEventListener("click", function () {
                    const tabId = this.getAttribute("href").substring(1); // Remove the # from the ID
                    localStorage.setItem("activeTabId", tabId);
                });
            });

            // Handle translate form submission with loading spinner and AJAX
            if (translateButtonForm) {
                translateButtonForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    if (loadingSpinner) {
                        // Show the loading spinner
                        loadingSpinner.style.display = 'block';
                    }

                    // Use Ajax to submit the form asynchronously
                    const form = new FormData(this);
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', this.action, true);

                    xhr.onload = function () {
                        if (loadingSpinner) {
                            // Hide the loading spinner once the server responds
                            loadingSpinner.style.display = 'none';
                        }

                        // Refresh the page or handle the response
                        if (xhr.status === 200) {
                            location.reload(); // Reload the page after the server response
                        }
                    };

                    xhr.send(form);
                });
            }
        });
    </script>
@endsection
