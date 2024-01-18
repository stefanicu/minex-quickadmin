<div class="m-3">
    @can('category_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.categories.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.category.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.category.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-applicationsCategories">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.category.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.category.fields.online') }}
                            </th>
                            <th>
                                {{ trans('cruds.category.fields.language') }}
                            </th>
                            <th>
                                {{ trans('cruds.category.fields.name') }}
                            </th>
                            <th>
                                {{ trans('cruds.category.fields.slug') }}
                            </th>
                            <th>
                                {{ trans('cruds.category.fields.cover_photo') }}
                            </th>
                            <th>
                                {{ trans('cruds.category.fields.product_image') }}
                            </th>
                            <th>
                                {{ trans('cruds.category.fields.applications') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $key => $category)
                            <tr data-entry-id="{{ $category->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $category->id ?? '' }}
                                </td>
                                <td>
                                    <span style="display:none">{{ $category->online ?? '' }}</span>
                                    <input type="checkbox" disabled="disabled" {{ $category->online ? 'checked' : '' }}>
                                </td>
                                <td>
                                    {{ App\Models\Category::LANGUAGE_SELECT[$category->language] ?? '' }}
                                </td>
                                <td>
                                    {{ $category->name ?? '' }}
                                </td>
                                <td>
                                    {{ $category->slug ?? '' }}
                                </td>
                                <td>
                                    @if($category->cover_photo)
                                        <a href="{{ $category->cover_photo->getUrl() }}" target="_blank" style="display: inline-block">
                                            <img src="{{ $category->cover_photo->getUrl('thumb') }}">
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    {{ $category->product_image->name ?? '' }}
                                </td>
                                <td>
                                    @foreach($category->applications as $key => $item)
                                        <span class="badge badge-info">{{ $item->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @can('category_show')
                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.categories.show', $category->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('category_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.categories.edit', $category->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('category_delete')
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('category_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.categories.massDestroy') }}",
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
    order: [[ 4, 'asc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-applicationsCategories:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection