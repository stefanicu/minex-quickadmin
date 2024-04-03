@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.contact.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Contact">
            <thead>
                <tr>
{{--                    <th width="10">--}}

{{--                    </th>--}}
                    <th>
                        {{ trans('cruds.contact.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.contact.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.contact.fields.surname') }}
                    </th>
{{--                    <th>--}}
{{--                        {{ trans('cruds.contact.fields.email') }}--}}
{{--                    </th>--}}
                    <th>
                        {{ trans('cruds.contact.fields.job') }}
                    </th>
                    <th>
                        {{ trans('cruds.contact.fields.industry') }}
                    </th>
{{--                    <th>--}}
{{--                        {{ trans('cruds.contact.fields.how_about') }}--}}
{{--                    </th>--}}
{{--                    <th>--}}
{{--                        {{ trans('cruds.contact.fields.message') }}--}}
{{--                    </th>--}}
                    <th>
                        {{ trans('cruds.contact.fields.company') }}
                    </th>
{{--                    <th>--}}
{{--                        {{ trans('cruds.contact.fields.phone') }}--}}
{{--                    </th>--}}
                    <th>
                        {{ trans('cruds.contact.fields.country') }}
                    </th>
{{--                    <th>--}}
{{--                        {{ trans('cruds.contact.fields.county') }}--}}
{{--                    </th>--}}
{{--                    <th>--}}
{{--                        {{ trans('cruds.contact.fields.city') }}--}}
{{--                    </th>--}}
{{--                    <th>--}}
{{--                        {{ trans('cruds.contact.fields.checkbox') }}--}}
{{--                    </th>--}}
{{--                    <th>--}}
{{--                        {{ trans('cruds.contact.fields.product') }}--}}
{{--                    </th>--}}
                        <th>
                            {{ trans('cruds.contact.fields.created_at') }}
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
{{--@can('contact_delete')--}}
{{--  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';--}}
{{--  let deleteButton = {--}}
{{--    text: deleteButtonTrans,--}}
{{--    url: "{{ route('admin.contacts.massDestroy') }}",--}}
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
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.contacts.index') }}",
    columns: [
      // { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'name', name: 'name' },
{ data: 'surname', name: 'surname' },
// { data: 'email', name: 'email' },
{ data: 'job', name: 'job' },
{ data: 'industry', name: 'industry' },
// { data: 'how_about', name: 'how_about' },
// { data: 'message', name: 'message' },
{ data: 'company', name: 'company' },
// { data: 'phone', name: 'phone' },
{ data: 'country', name: 'country' },
// { data: 'county', name: 'county' },
// { data: 'city', name: 'city' },
// { data: 'checkbox', name: 'checkbox' },
// { data: 'product', name: 'product' },
{ data: 'created_at', name: 'created_at' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 7, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-Contact').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection
