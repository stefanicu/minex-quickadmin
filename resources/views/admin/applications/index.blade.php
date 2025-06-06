@extends('layouts.admin')
@section('content')
    @can('application_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.applications.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.application.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.application.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Application">
                <thead>
                <tr>
                    {{--                    <th width="10">--}}

                    {{--                    </th>--}}
                    <th>
                        {{ trans('cruds.application.fields.id') }}
                    </th>
                    {{--                    <th>--}}
                    {{--                        {{ trans('cruds.application.fields.online') }}--}}
                    {{--                    </th>--}}
                    <th>
                        {{ trans('cruds.application.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.application.fields.image') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            {{--@can('application_delete')--}}
            {{--  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';--}}
            {{--  let deleteButton = {--}}
            {{--    text: deleteButtonTrans,--}}
            {{--    url: "{{ route('admin.applications.massDestroy') }}",--}}
            {{--    className: 'btn-danger',--}}
            {{--    action: function (e, dt, node, config) {--}}
            {{--      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {--}}
            {{--          return entry.id--}}
            {{--      });--}}

            {{--      if (ids.length === 0) {--}}
            {{--        alert('{{ trans('global.datatables.zero_selected') }}')--}}

            {{--        return--}}
            {{--      }--}}

            {{--      if (confirm('{{ trans('global.areYouSure') }}')) {--}}
            {{--        $.ajax({--}}
            {{--          headers: {'x-csrf-token': _token},--}}
            {{--          method: 'POST',--}}
            {{--          url: config.url,--}}
            {{--          data: { ids: ids, _method: 'DELETE' }})--}}
            {{--          .done(function () { location.reload() })--}}
            {{--      }--}}
            {{--    }--}}
            {{--  }--}}
            {{--  dtButtons.push(deleteButton)--}}
            {{--@endcan--}}

            let dtOverrideGlobals = {
                buttons: dtButtons,
                stateSave: true,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.applications.index') }}",
                columns: [
                    // { data: 'placeholder', name: 'placeholder' },
                    {data: 'id', name: 'applications.id'},
                    {data: 'name', name: 'application_translations.name'},
                    {data: 'image', name: 'image', class: 'text-center', sortable: false, searchable: false},
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}',
                        class: 'text-nowrap text-center',
                        sortable: false,
                        searchable: false
                    }
                ],
                orderCellsTop: false,
                order: [[1, 'asc']],
                pageLength: 25,
            };
            let table = $('.datatable-Application').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });

        });

    </script>
@endsection
