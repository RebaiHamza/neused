<form action="" method="POST">
  {{csrf_field()}}
  <button type="button" class="btn btn-xs {{ $status==1 ? "btn-success" : "btn-danger" }}">
    {{ $status ==1 ? 'Active' : 'Deactive' }}
  </button>
</form> 