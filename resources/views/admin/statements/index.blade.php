@extends('layouts.admin')
@section('content')
@can('statement_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.statements.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.statement.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.statement.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Statement">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.statement.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.statement.fields.therapy_area') }}
                        </th>
                        <th>
                            {{ trans('cruds.statement.fields.parent') }}
                        </th>
                        <th>
                            {{ trans('cruds.statement.fields.theme') }}
                        </th>
                        <th>
                            {{ trans('cruds.statement.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.statement.fields.is_notify_all') }}
                        </th>
                        <th>
                            {{ trans('cruds.statement.fields.resource') }}
                        </th>
                        <th>
                            {{ trans('cruds.statement.fields.reference') }}
                        </th>
                        <th>
                            {{ trans('cruds.statement.fields.status') }}
                        </th>
                        <th>
                            {{ trans('cruds.statement.fields.order_by') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($therapy_areas as $key => $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($statements as $key => $item)
                                    <option value="{{ $item->title }}">{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($themes as $key => $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                        </td>
                        <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($resources as $key => $item)
                                    <option value="{{ $item->title }}">{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($references as $key => $item)
                                    <option value="{{ $item->title }}">{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($statement_statuses as $key => $item)
                                    <option value="{{ $item->status }}">{{ $item->status }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statements as $key => $statement)
                        <tr data-entry-id="{{ $statement->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $statement->id ?? '' }}
                            </td>
                            <td>
                                {{ $statement->therapy_area->name ?? '' }}
                            </td>
                            <td>
                                {{ $statement->parent->title ?? '' }}
                            </td>
                            <td>
                                {{ $statement->theme->name ?? '' }}
                            </td>
                            <td>
                                {{ $statement->title ?? '' }}
                            </td>
                            <td>
                                <span style="display:none">{{ $statement->is_notify_all ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $statement->is_notify_all ? 'checked' : '' }}>
                            </td>
                            <td>
                                @foreach($statement->resources as $key => $item)
                                    <span class="badge badge-info">{{ $item->title }}</span>
                                @endforeach
                            </td>
                            <td>
                                @foreach($statement->references as $key => $item)
                                    <span class="badge badge-info">{{ $item->title }}</span>
                                @endforeach
                            </td>
                            <td>
                                {{ $statement->status->status ?? '' }}
                            </td>
                            <td>
                                {{ $statement->order_by ?? '' }}
                            </td>
                            <td>
                                @can('statement_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.statements.show', $statement->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('statement_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.statements.edit', $statement->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('statement_delete')
                                    <form action="{{ route('admin.statements.destroy', $statement->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('statement_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.statements.massDestroy') }}",
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
    pageLength: 100,
  });
  let table = $('.datatable-Statement:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
})

</script>
@endsection