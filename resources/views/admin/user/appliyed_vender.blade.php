@extends("admin/layouts.master")
@section('title','Store Requests | ')
@section("body")
<div class="box">
  <div class="box-header with-border">
    <div class="box-title">
      All Store Requests ({{ $list }})
    </div>
  </div>

  <div class="box-body">
    <table id="tableStoreList" class="width100 table table-bordered table-striped table-hover">
      <thead>
        <th>
          #
        </th>

        <th>
          Store Details
        </th>

        <th>
          Requested at
        </th>

        <th>
          Action
        </th>
      </thead>

      <tbody>

      </tbody>
    </table>
  </div>
</div>
@endsection
@section('custom-script')
<script>
  $(function () {
    "use strict";
    var table = $('#tableStoreList').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('get.store.request') }}",
      columns: [{
          data: 'DT_RowIndex',
          name: 'DT_RowIndex',
          searchable: false
        },
        {
          data: 'detail',
          name: 'detail'
        },
        {
          data: 'requested_at',
          name: 'requested_at',
          searchable : false
        },
        {
          data: 'action',
          name: 'action',
          searchable : false,
          orderable : false
        }
      ],
      dom: 'lBfrtip',
      buttons: [
        'csv', 'excel', 'pdf', 'print', 'colvis'
      ],
      order: [
        [0, 'DESC']
      ]
    });

  });
</script>
@endsection