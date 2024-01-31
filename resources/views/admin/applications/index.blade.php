@extends('layouts.admin')
@section('content')
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
            <table class=" table table-bordered table-striped table-hover datatable datatable-Application">
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
                            {{ trans('cruds.application.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.application.fields.image') }}
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
                                {{ $application->name ?? '' }}
                            </td>
                            <td>
                                @if($application->image)
                                    <a href="{{ $application->image->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $application->image->getUrl('thumb') }}">
                                    </a>
                                @endif
                            </td>
                            <td>

                                @can('application_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.applications.edit', $application->id) }}">
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
  let table = $('.datatable-Application:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection