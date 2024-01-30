@extends('layouts.admin')
@section('content')
@can('reference_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.references.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.reference.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.reference.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Reference">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.reference.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.reference.fields.industries') }}
                    </th>
                    <th>
                        {{ trans('cruds.industry.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.reference.fields.online') }}
                    </th>
                    <th>
                        {{ trans('cruds.reference.fields.language') }}
                    </th>
                    <th>
                        {{ trans('cruds.reference.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.reference.fields.slug') }}
                    </th>
                    <th>
                        {{ trans('cruds.reference.fields.photo_square') }}
                    </th>
                    <th>
                        {{ trans('cruds.reference.fields.photo_wide') }}
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
    ajax: "{{ route('admin.references.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'industries_online', name: 'industries.online' },
{ data: 'industries.name', name: 'industries.name' },
{ data: 'online', name: 'online' },
{ data: 'language', name: 'language' },
{ data: 'name', name: 'name' },
{ data: 'slug', name: 'slug' },
{ data: 'photo_square', name: 'photo_square', sortable: false, searchable: false },
{ data: 'photo_wide', name: 'photo_wide', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-Reference').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection