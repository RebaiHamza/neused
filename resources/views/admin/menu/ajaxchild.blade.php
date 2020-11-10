 <label for="name">Choose Childcategory:</label>
            <ul class="list-group list-group-root well"> 
          <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
           @foreach(App\Subcategory::where('status','1')->where('parent_cat','=',$catid)->get(); as $item)  
              <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                  <a role="button" data-parent="#accordion" aria-expanded="true" aria-controls="collapseOne">
                      <input @if(isset($catsids)) @foreach($catsids as $sub) {{ $sub == $item->id ? "checked" : ""  }}@endforeach @endif id="subcategories{{$item->id}}" type="checkbox" class="required_one categories s_cat" name="sub_id[]" value="{{$item->id}}">

                      <i data-toggle="collapse" href="#childcat{{$item->id}}" class="more-less glyphicon glyphicon-plus"></i> {{$item->title}}
                  </a>
              </h4>
            </div>
          <div id="childcat{{$item->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            @php
            $data = $item->childcategory->where('status','1')->get();

              $i = 0;
              $j = 1;
              $t = sizeof($data);

              if($t == 1){
                $single_data = $data[0];
                @endphp
                   <div class="col-md-12 sub-cat-sel">
                    <label><input @if(isset($childcats)) @foreach($childcats as $ch) {{ $ch == $single_data['id'] ? "checked" : "" }} @endforeach @endif type="checkbox" name="r[]" class="c_cat required_one sub_categories sub_categories_{{$item->id}}" parents_id = "{{$item->id}}" value="{{$single_data['id']}}"> {{$single_data['title']}}</label>
                  </div>
                @php
              }else{
                for ($j; $j < $t; $j += 2) {
                  
                  $left_row = $data[$i];
                  @endphp
                  <div class="col-md-6 sub-cat-sel">
                    <label><input @foreach($childcats as $ch) {{ $ch == $left_row['id'] ? "checked" : "" }} @endforeach type="checkbox" name="r[]" class="c_cat required_one sub_categories sub_categories_{{$item->id}}" parents_id = "{{$item->id}}" value="{{$left_row['id']}}"> {{$left_row['title']}}</label>
                  </div>
                    @php
                  $i += 2;

                  $right_row = $data[$j];
                  @endphp
                  <div class="col-md-6 sub-cat-sel">
                    <label><input @foreach($childcats as $ch) {{ $ch == $right_row['id'] ? "checked" : "" }} @endforeach type="checkbox" name="r[]" class="c_cat required_one sub_categories sub_categories_{{$item->id}}" parents_id = "{{$item->id}}" value="{{$right_row['id']}}"> {{$right_row['title']}}</label></div>
                
                 
                @php 
              }

            }

              
             @endphp


            
          </div>
          @endforeach
      </div>  
      </div>  
      </ul> 

