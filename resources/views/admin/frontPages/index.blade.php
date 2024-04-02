@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.frontPage.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-FrontPage">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.frontPage.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.frontPage.fields.language') }}
                    </th>
                    <th>
                        {{ trans('cruds.frontPage.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.frontPage.fields.first_text') }}
                    </th>
                    <th>
                        {{ trans('cruds.frontPage.fields.button') }}
                    </th>
                    <th>
                        {{ trans('cruds.frontPage.fields.image') }}
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
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.frontpages.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'language', name: 'language' },
{ data: 'name', name: 'name' },
{ data: 'first_text', name: 'first_text' },
{ data: 'button', name: 'button' },
{ data: 'image', name: 'image', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-FrontPage').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection
