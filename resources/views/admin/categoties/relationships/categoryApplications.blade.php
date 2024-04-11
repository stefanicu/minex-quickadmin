<div class="m-3">
    @can('application_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.applications.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.application.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.application.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-categoryApplications">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.application.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.application.fields.online') }}
                            </th>
                            <th>
                                {{ trans('cruds.application.fields.language') }}
                            </th>
                            <th>
                                {{ trans('cruds.application.fields.name') }}
                            </th>
                            <th>
                                {{ trans('cruds.application.fields.slug') }}
                            </th>
                            <th>
                                {{ trans('cruds.application.fields.image') }}
                            </th>
                            <th>
                                {{ trans('cruds.application.fields.category') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $key => $application)
                            <tr data-entry-id="{{ $application->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $application->id ?? '' }}
                                </td>
                                <td>
                                    <span style="display:none">{{ $application->online ?? '' }}</span>
                                    <input type="checkbox" disabled="disabled" {{ $application->online ? 'checked' : '' }}>
                                </td>
                                <td>
                                    {{ App\Models\Application::LANGUAGE_SELECT[$application->language] ?? '' }}
                                </td>
                                <td>
                                    {{ $application->name ?? '' }}
                                </td>
                                <td>
                                    {{ $application->slug ?? '' }}
                                </td>
                                <td>
                                    @if($application->image)
                                        <a href="{{ $application->image->getUrl() }}" target="_blank" style="display: inline-block">
                                            <img src="{{ $application->image->getUrl('thumb') }}">
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    @foreach($application->categories as $key => $item)
                                        <span class="badge badge-info">{{ $item->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @can('application_show')
                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.applications.show', $application->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('application_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.applications.edit', $application->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('application_delete')
                                        <form action="{{ route('admin.applications.destroy', $application->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                        </form>
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
@can('application_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.applications.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-categoryApplications:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection