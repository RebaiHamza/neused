@extends("admin/layouts.master")
@section('title','All Customers |')
@section("body")


<div class="box">
  <div class="box-header with-border">
    

    <div class="box-title">{{ $pagetitle }}</div>
    <div class="pull-right col-md-4 input-group">
      <a href=" {{url('admin/users/create')}} " class="btn btn-success owtbtn">+ Add Customer/Seller/Admin</a>
      <span class="input-group-addon" id="basic-addon1">
        <i class="fa fa-filter" aria-hidden="true"></i>
      </span>
      <select name="filter" id="filter" class="filter form-control select2">
        <option {{ Nav::isRoute('users.index') ? "selected" : "" }} value="all">Show All Users</option>
        <option {{ Nav::isRoute('show.allvenders') ? "selected" : "" }} value="seller">Only Sellers</a></option>
        <option {{ Nav::isRoute('show.alladmins') ? "selected" : "" }} value="admins">Only Admins</option>
        <option {{ Nav::isRoute('show.allcustomer') ? "selected" : "" }} value="customer">Only Customers</option>
      </select>
    </div>

    


  </div>

  <div class="box-body">

    <table id="userTable" class="table table-hover table-responsive width100">
      <thead>
        <tr class="table-heading-row">
          <th>ID</th>
          <th>User Image</th>
          <th>User Detail</th>
          <th>Added / Updated On</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>

      </tbody>

    </table>
  </div>
</div>
@foreach($users as $key=> $user)
<div id="{{ $user->id }}deleteuser" class="delete-modal modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <div class="delete-icon"></div>
      </div>
      <div class="modal-body text-center">
        <h4 class="modal-heading">Are You Sure ?</h4>
        <p>Do you really want to delete this user? This process cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <form method="post" action="{{url('admin/users/'.$user->id)}}" class="pull-right">
          {{csrf_field()}}
          {{method_field("DELETE")}}



          <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">No</button>
          <button type="submit" class="btn btn-danger">Yes</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endforeach

@endsection
@section('custom-script')
@include('admin.user.userscript')
@endsection