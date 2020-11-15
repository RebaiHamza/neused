<ul class="nav table-nav">
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
        Action <span class="caret"></span>
      </a>
      <ul class="dropdown-menu dropdown-menu-right">

        @if($main_attr_id)
       
         {{-- <li role="presentation">
          
         
        @php 

              $var_name_count = count(json_decode($main_attr_id,true));
                                     
              $name = [];
              $var_name;
              $newarr = array();

              
                for($i = 0; $i<$var_name_count; $i++){
                  
                  try{
                    $var_id = json_decode($main_attr_id,true)[$i];
                    $var_name[$i] = json_decode($main_attr_value,true)[$var_id];
                  
                    $name[$i] = \DB::table('product_attributes')->where('id',$var_id)->first();
                  }catch(\Exception $e){

                  }

                }
              
              
            try{
              $url = '../details/'.$proid.'?'.json_decode($name[0]->attr_name,true).'='.$var_name[0].'&'.$name[1]->attr_name.'='.$var_name[1];
            }catch(\Exception $e)
            {
              $url = '../details/'.$proid.'?'.json_decode($name[0]->attr_name,true).'='.$var_name[0];
            }


       @endphp
 --}}
          {{-- <a target="_blank" role="menuitem" tabindex="-1" href="{{ $url }}">
          
            <i class="fa fa-eye" aria-hidden="true"></i>View Product

          </a> --}}
          

        {{-- </li> --}}
         {{-- <li role="presentation" class="divider"></li> --}}
        
        
        
          <li>
            <a href="{{ route('pro.vars.all',$proid) }}" class=""><i class="fa fa-external-link-square" aria-hidden="true"></i>
            View All Variants</a>
          </li>

            <li role="presentation" class="divider"></li>
         @endif

          
           <li role="presentation">
            <a href="{{ route('add.var',$proid) }}">
              <i class="fa fa-plus" aria-hidden="true"></i>Add Variant
            </a>
          </li>
            <li role="presentation" class="divider"></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href="{{url('admin/products/'.$proid.'/edit')}}">
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
           <form method="post" action="{{url('admin/products/'.$proid)}}" class="pull-right">
                         {{csrf_field()}}
                         {{method_field("DELETE")}}
            <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">No</button>
            <button type="submit" class="btn btn-danger">Yes</button>
          </form>
        </div>
      </div>
    </div>
</div>