<ul class="nav table-nav">
  <li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
      Action <span class="caret"></span>
    </a>
    <ul class="dropdown-menu dropdown-menu-right">
      @php
      $cryptid = Crypt::encrypt($id);
      @endphp
      <li role="presentation"><a title="IT will expire your session and run this user session in same window !"
          role="menuitem" tabindex="-1" href="{{route('login.as',$cryptid)}}"><i class="fa fa-lock"
            aria-hidden="true"></i> Login as this user</a></li>
      {{-- @if($wallet_system == 1)
      <li role="presentation" class="divider"></li>
      <li role="presentation">
        <a data-toggle="modal" href="#giftPoint{{ $id }}">
          <i class="fa fa-gift" aria-hidden="true"></i> Gift Point
        </a>
      </li>
      @endif --}}
      <li role="presentation" class="divider"></li>
      <li role="presentation"><a role="menuitem" tabindex="-1" href="{{url('admin/users/'.$id.'/edit')}}"><i
            class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit User</a></li>


      <li role="presentation" class="divider"></li>
      <li role="presentation">
        <a @if(env('DEMO_LOCK')==0) data-toggle="modal" href="#{{ $id }}deleteuser" @else
          title="This action is disabled in demo !" disabled="disabled" @endif>
          <i class="fa fa-trash-o" aria-hidden="true"></i>Delete
        </a>
      </li>
    </ul>
  </li>
</ul>

{{-- <!-- Gift point Modal -->
@if($wallet_system == 1)
<div class="modal fade" id="giftPoint{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Gift Point to {{ $name }}</h4>
      </div>
      <div class="modal-body">
        @php
        $wallet = App\UserWallet::where('user_id',$id)->first();
        @endphp
        @if(isset($wallet))
        @if($wallet->status == 1)
        <p>Current Point: {{ round($wallet->balance) }} Pts.</p>
          
            <form action="{{ route('admin.gift.point',$id) }}" method="POST">
              @csrf
    
              <div class="form-group">
                <label>Enter Point: <span class="required">*</span></label>
                <input name="point" type="number" min="1" class="form-control" required="">
              </div>
              <p></p>
              <div class="form-group">
                <button type="submit" class="btn btn-md btn-primary">
                  <i class="fa fa-gift" aria-hidden="true"></i> Gift
                </button>
              </div>
    
            </form>
        @else
        <p class="text-red">Wallet is not active !</p>
        @endif
        @else
        
             
            <p>Current Point: 0 Pts.</p>
            
            <form action="{{ route('admin.gift.point',$id) }}" method="POST">
              @csrf
    
              <div class="form-group">
                <label>Enter Point: <span class="required">*</span></label>
                <input name="point" type="number" min="1" class="form-control" required="">
              </div>
              <p></p>
              <div class="form-group">
                <button type="submit" class="btn btn-md btn-primary">
                  <i class="fa fa-gift" aria-hidden="true"></i> Gift
                </button>
              </div>
    
            </form>
         
       
        @endif


      </div>
    </div>
  </div>
</div>
@endif --}}