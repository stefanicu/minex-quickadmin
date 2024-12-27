@extends('layouts.admin')
@section('content')
    @can('blog_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.blogs.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.blog.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.blog.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Blog">
                <thead>
                <tr>
                    {{--                    <th width="10">--}}

                    {{--                    </th>--}}
                    <th>
                        {{ trans('cruds.blog.fields.online') }}
                    </th>
                    <th>
                        {{ trans('cruds.blog.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.blog.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.blog.fields.image') }}
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
            {{--@can('blog_delete')--}}
            {{--  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';--}}
            {{--  let deleteButton = {--}}
            {{--    text: deleteButtonTrans,--}}
            {{--    url: "{{ route('admin.blogs.massDestroy') }}",--}}
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
                    data['translations'].forEach(
                        function (element) {

                            if (element['locale'] == currentLocale) {
                                if (element['online'] == 0) {
                                    $(row).addClass('red_row')
                                }
                            }

                        }
                    )
                },
                buttons: dtButtons,
                stateSave: true,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.blogs.index') }}",
                columns: [
                    // { data: 'placeholder', name: 'placeholder' },
                    {data: 'online', name: 'online'},
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'blog_translations.name'},
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
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });

    </script>
@endsection
