@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.homeId.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-HomeId">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.homeId.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.homeId.fields.image') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($homeIds as $key => $homeId)
                        <tr data-entry-id="{{ $homeId->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $homeId->id ?? '' }}
                            </td>
                            <td>
                                @if($homeId->image)
                                    <a href="{{ $homeId->image->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $homeId->image->getUrl('thumb') }}">
                                    </a>
                                @endif
                            </td>
                            <td>

                                @can('home_id_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.home-ids.edit', $homeId->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan


                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  
  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-HomeId:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection