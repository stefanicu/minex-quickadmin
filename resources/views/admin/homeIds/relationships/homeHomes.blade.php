<div class="m-3">

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.home.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-homeHomes">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.home.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.home.fields.language') }}
                            </th>
                            <th>
                                {{ trans('cruds.home.fields.name') }}
                            </th>
                            <th>
                                {{ trans('cruds.home.fields.first_text') }}
                            </th>
                            <th>
                                {{ trans('cruds.home.fields.button') }}
                            </th>
                            <th>
                                {{ trans('cruds.home.fields.image') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($homes as $key => $home)
                            <tr data-entry-id="{{ $home->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $home->id ?? '' }}
                                </td>
                                <td>
                                    {{ App\Models\Home::LANGUAGE_SELECT[$home->language] ?? '' }}
                                </td>
                                <td>
                                    {{ $home->name ?? '' }}
                                </td>
                                <td>
                                    {{ $home->first_text ?? '' }}
                                </td>
                                <td>
                                    {{ $home->button ?? '' }}
                                </td>
                                <td>
                                    @if($home->image)
                                        <a href="{{ $home->image->getUrl() }}" target="_blank" style="display: inline-block">
                                            <img src="{{ $home->image->getUrl('thumb') }}">
                                        </a>
                                    @endif
                                </td>
                                <td>

                                    @can('home_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.homes.edit', $home->id) }}">
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
</div>
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
  let table = $('.datatable-homeHomes:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection