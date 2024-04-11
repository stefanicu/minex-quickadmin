@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.translationCenter.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-TranslationCenter">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.translationCenter.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.translationCenter.fields.online') }}
                    </th>
                    <th>
                        {{ trans('cruds.translationCenter.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.translationCenter.fields.slug') }}
                    </th>
                    <th>
                        {{ trans('cruds.translationCenter.fields.section') }}
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
    ajax: "{{ route('admin.translation-centers.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'online', name: 'online' },
{ data: 'name', name: 'name' },
{ data: 'slug', name: 'slug' },
{ data: 'section', name: 'section' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 3, 'asc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-TranslationCenter').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection