@extends('layouts.admin_home')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.frontPage.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-FrontPage">
            <thead>
                <tr>
                    <th>
                        {{ trans('cruds.frontPage.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.frontPage.fields.name') }}
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
        let dtOverrideGlobals = {
            buttons: dtButtons,
            stateSave: true,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: "{{ route('admin.front_pages.index') }}",
            columns: [
                { data: 'id', name: 'front_pages.id' },
                { data: 'name', name: 'front_page_translations.name' },
                { data: 'actions', name: '{{ trans('global.actions') }}', class: 'text-nowrap text-center', sortable: false, searchable: false  }
            ],
            orderCellsTop: true,
            order: [[ 0, 'asc' ]],
            pageLength: 25,
        };
        let table = $('.datatable-FrontPage').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endsection
