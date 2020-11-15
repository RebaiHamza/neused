<form action="{{ route('product.quick.update',$proid) }}" method="POST">
  {{csrf_field()}}
  <button type="submit" class="btn btn-xs {{ $status==1 ? "btn-success" : "btn-danger" }}">
    {{ $status ==1 ? 'Active' : 'Deactive' }}
  </button>
</form> 