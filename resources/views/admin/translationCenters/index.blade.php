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
                    <th>
                        {{ trans('ID') }}
                    </th>
                    <th>
                        {{ trans('Name') }}
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
            paging: false,
            aaSorting: [],
            ajax: "{{ route('admin.translation-centers.index') }}",
            columns: [
                { data: 'id', name: 'translation_centers.id' },
                { data: 'name', name: 'translation_center_translations.name' },
                { data: 'actions', name: '{{ trans('global.actions') }}', class: 'text-nowrap text-center', sortable: false, searchable: false  }
            ],
            orderCellsTop: true,
            order: [[ 0, 'asc' ]],
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
