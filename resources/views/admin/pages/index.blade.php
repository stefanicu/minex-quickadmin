@extends('layouts.admin')
@section('content')
    @can('page_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.pages.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.page.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.page.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Blog">
                <thead>
                <tr>
                    {{--                    <th width="10">--}}

                    {{--                    </th>--}}
                    <th>
                        {{ trans('cruds.page.fields.online') }}
                    </th>
                    <th>
                        {{ trans('cruds.page.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.page.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.page.fields.image') }}
                    </th>
                    <th>
                        {{ trans('Date') }}
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
            {{--@can('page_delete')--}}
            {{--  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';--}}
            {{--  let deleteButton = {--}}
            {{--    text: deleteButtonTrans,--}}
            {{--    url: "{{ route('admin.pages.massDestroy') }}",--}}
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
            var currentLocale = '<?= app()->getLocale() ?>';

            let dtOverrideGlobals = {
                "createdRow": function (row, data, dataIndex) {
                    let hasCurrentLocale = false; // Track if the current locale exists

                    data['translations'].forEach(function (element) {
                        if (element['locale'] == '<?= app()->getLocale() ?>') {
                            hasCurrentLocale = true; // Mark that the locale exists
                            if (element['online'] == 0) {
                                $(row).addClass('red_row'); // Add red_row if online is 0
                            }
                        }
                    });

                    // If the current locale is missing in translations, add red_row
                    if (!hasCurrentLocale) {
                        $(row).addClass('red_row');
                    }
                },

                buttons: dtButtons,
                stateSave: true,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.pages.index') }}",
                columns: [
                    // { data: 'placeholder', name: 'placeholder' },
                    {data: 'online', name: 'online'},
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'page_translations.name'},
                    {data: 'image', name: 'image', class: 'text-center', sortable: false, searchable: false},
                    {data: 'created_at', name: 'created_at'},
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}',
                        class: 'text-nowrap text-center',
                        sortable: false,
                        searchable: false
                    }
                ],
                orderCellsTop: true,
                order: [[4, 'desc']],
                pageLength: 25,
            };

            let table = $('.datatable-Blog').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });

        });

    </script>
@endsection
