<ul class="nav table-nav">
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
        Action <span class="caret"></span>
      </a>
      <ul class="dropdown-menu dropdown-menu-right">

        @if($main_attr_id)
       
       
          <li>
            <a href="{{ route('seller.pro.vars.all',$proid) }}" class=""><i class="fa fa-external-link-square" aria-hidden="true"></i>
            View All Variants</a>
          </li>

            <li role="presentation" class="divider"></li>
         @endif
           <li role="presentation">
              <a href="{{ route('seller.add.var',$proid) }}">
                <i class="fa fa-plus" aria-hidden="true"></i>Add Variant
              </a>
           </li>
            <li role="presentation" class="divider"></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href="{{ route('my.products.edit',$proid) }}">
          <i class="fa fa-pencil-square-o" aria-hidden="true"></i>Edit Product</a></li>
          
          
          <li role="presentation" class="divider"></li>
          <li role="presentation">
            <a data-toggle="modal" href="#{{ $proid}}pro">
              <i class="fa fa-trash-o" aria-hidden="true"></i>Delete
            </a>
          </li>
      </ul>
    </li>
  </ul>

  <div id="{{ $proid }}pro" class="delete-modal modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <div class="delete-icon"></div>
        </div>
        <div class="modal-body text-center">
          <h4 class="modal-heading">Are You Sure ?</h4>
          <p>Do you really want to delete this product <b>{{ $productname }}</b>? This process cannot be undone.</p>
        </div>
        <div class="modal-footer">
           <form method="post" action="{{route('my.products.destroy',$proid)}}" class="pull-right">
                         @csrf
                         @method('DELETE')
            <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">No</button>
            <button type="submit" class="btn btn-danger">Yes</button>
          </form>
        </div>
      </div>
    </div>
</div>