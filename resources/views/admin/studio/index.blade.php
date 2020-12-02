@extends("admin/layouts.master")
@section('title','All Blog Post')
@section("body")


      
          <div class="box" >
            <div class="box-header">
              <h3 class="box-title">
                  All Studio packs
              </h3>

              <a href=" {{url('admin/studio/create')}} " class="btn btn-success pull-right">+ Add new Pack</a> 
            </div>
        <!-- /.box-header -->
                
            <div class="box-body">

              <table id="full_detail_table" class="width100 table table-hover table-responsive">
                      <thead>
                        <tr class="table-heading-row">
                          <th>ID</th>
                          <th>Title</th>
                          <th>Description</th>
                          <th>Created at</th>
                          <th>Status</th>
						              <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i=1;?>
                        @foreach($packs as $pack)
                        <tr>

                        <td>{{$i++}}</td>

                        <td>{{$pack->title}}</td>
                        
                        <td>
                          {{substr(strip_tags($pack->description), 0, 50)}}{{strlen(strip_tags($pack->description))>50 ? '...' : ""}}
                        </td>

                        <td>{{$pack->created_at}}</td>
                        <td>
                          <form action="{{ route('studio.quick.update',$pack->id) }}" method="POST">
                              {{csrf_field()}}
                              <button type="submit" class="btn btn-xs {{ $pack->status==1 ? "btn-success" : "btn-danger" }}">
                                {{ $pack->status ==1 ? 'Active' : 'Deactive' }}
                              </button>
                          </form>
                        </td>
                        <td>
                          
                        <a href=" {{url('admin/studio/'.$pack->id.'/edit')}} " class="btn btn-sm btn-info">
                          <i class="fa fa-pencil"></i>
                        </a>
                           
                        <a data-toggle="modal" data-target="#{{$pack->id}}studio" class="btn btn-sm btn-danger">
                          <i class="fa fa-trash"></i>
                        </a>
                          
                         
                            
                        
                        </td>
    
                        </tr>
                        @endforeach
                       
                      </tbody>
                    </table>
                   </table>
                  </div>
                </div>
              </div>



 @foreach($packs as $pack)
    <div id="{{ $pack->id }}studio" class="delete-modal modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <div class="delete-icon"></div>
            </div>
            <div class="modal-body text-center">
              <h4 class="modal-heading">Are You Sure ?</h4>
              <p>Do you really want to delete this blog? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <form method="post" action="{{url('admin/studio/'.$pack->id)}}" class="pull-right">
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

        <!-- /page content -->
@endsection