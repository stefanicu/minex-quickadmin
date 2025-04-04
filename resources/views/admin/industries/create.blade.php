@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.industry.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("admin.industries.store") }}" enctype="multipart/form-data">
                @csrf
                {{--            <div class="form-group">--}}
                {{--                <div class="form-check {{ $errors->has('online') ? 'is-invalid' : '' }}">--}}
                {{--                    <input type="hidden" name="online" value="0">--}}
                {{--                    <input class="form-check-input" type="checkbox" name="online" id="online" value="1" {{ $industry->online || old('online', 0) === 1 ? 'checked' : '' }}>--}}
                {{--                    <label class="form-check-label" for="online">{{ trans('cruds.industry.fields.online') }}</label>--}}
                {{--                </div>--}}
                {{--                @if($errors->has('online'))--}}
                {{--                    <span class="text-danger">{{ $errors->first('online') }}</span>--}}
                {{--                @endif--}}
                {{--                <span class="help-block">{{ trans('cruds.industry.fields.online_helper') }}</span>--}}
                {{--            </div>--}}

                <div class="row">
                    <div class="form-group col-12 col-xl-6">
                        <label class="required" for="name">{{ trans('cruds.industry.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                               name="name" id="name" value="{{ old('name', '') }}" required>
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.industry.fields.name_helper') }}</span>
                    </div>
                    <div class="form-group col-12 col-xl-6">
                        <label class="required" for="slug">{{ trans('cruds.industry.fields.slug') }}</label>
                        <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text"
                               name="slug" id="slug" value="{{ old('slug', '') }}" required>
                        @if($errors->has('slug'))
                            <span class="text-danger">{{ $errors->first('slug') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.industry.fields.slug_helper') }}</span>
                    </div>
                </div>

                <div class="row align-items-center">
                    <div class="form-group col-12 align-items-center">
                        <label for="photo">{{ trans('cruds.industry.fields.photo') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('photo') ? 'is-invalid' : '' }}"
                             id="photo-dropzone"></div>
                        @if($errors->has('photo'))
                            <span class="text-danger">{{ $errors->first('photo') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.industry.fields.photo_helper') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <input type="hidden" name="locale" value="{{app()->getLocale()}}">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        Dropzone.options.photoDropzone = {
            url: '{{ route('admin.industries.storeMedia') }}',
            maxFilesize: 1, // MB
            acceptedFiles: '.svg',
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 1,
            },
            success: function (file, response) {
                $('form').find('input[name="photo"]').remove()
                $('form').append('<input type="hidden" name="photo" value="' + response.name + '">')
            },
            removedfile: function (file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="photo"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function () {
                @if(isset($industry) && $industry->photo)
                    this.on("addedfile", function (file) {
                    // Create a FileReader to check the image dimensions
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        const img = new Image();
                        img.onload = function () {
                            // Validate dimensions
                            if (img.width !== 80 || img.height !== 80) {
                                alert("Image must be exactly 80x80 pixels.");
                                this.removeFile(file); // Remove invalid file
                            }
                        };
                        img.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                });
                var file = {!! json_encode($industry->photo) !!}
                this.options.addedfile.call(this, file)
                this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                file.previewElement.classList.add('dz-complete')
                $('form').append('<input type="hidden" name="photo" value="' + file.file_name + '">')
                this.options.maxFiles = this.options.maxFiles - 1
                @endif
            },
            error: function (file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }

    </script>
@endsection
