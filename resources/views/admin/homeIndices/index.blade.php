@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.homeIndex.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-HomeIndex">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.homeIndex.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.homeIndex.fields.image') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($homeIndices as $key => $homeIndex)
                        <tr data-entry-id="{{ $homeIndex->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $homeIndex->id ?? '' }}
                            </td>
                            <td>
                                @if($homeIndex->image)
                                    <a href="{{ $homeIndex->image->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $homeIndex->image->getUrl('thumb') }}">
                                    </a>
                                @endif
                            </td>
                            <td>

                                @can('home_index_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.home-indices.edit', $homeIndex->id) }}">
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
  let table = $('.datatable-HomeIndex:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection