<div class="m-3">
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
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-industriesReferences">
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
                    <tbody>
                        @foreach($references as $key => $reference)
                            <tr data-entry-id="{{ $reference->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $reference->id ?? '' }}
                                </td>
                                <td>
                                    {{ $reference->industries->online ?? '' }}
                                </td>
                                <td>
                                    {{ $reference->industries->name ?? '' }}
                                </td>
                                <td>
                                    <span style="display:none">{{ $reference->online ?? '' }}</span>
                                    <input type="checkbox" disabled="disabled" {{ $reference->online ? 'checked' : '' }}>
                                </td>
                                <td>
                                    {{ App\Models\Reference::LANGUAGE_SELECT[$reference->language] ?? '' }}
                                </td>
                                <td>
                                    {{ $reference->name ?? '' }}
                                </td>
                                <td>
                                    {{ $reference->slug ?? '' }}
                                </td>
                                <td>
                                    @foreach($reference->photo_square as $key => $media)
                                        <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                            <img src="{{ $media->getUrl('thumb') }}">
                                        </a>
                                    @endforeach
                                </td>
                                <td>
                                    @if($reference->photo_wide)
                                        <a href="{{ $reference->photo_wide->getUrl() }}" target="_blank" style="display: inline-block">
                                            <img src="{{ $reference->photo_wide->getUrl('thumb') }}">
                                        </a>
                                    @endif
                                </td>
                                <td>

                                    @can('reference_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.references.edit', $reference->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('reference_delete')
                                        <form action="{{ route('admin.references.destroy', $reference->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('reference_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.references.massDestroy') }}",
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
  let table = $('.datatable-industriesReferences:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection