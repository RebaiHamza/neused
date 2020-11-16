@extends('admin.layouts.sellermaster')
@section('title',"Edit Product: $products->name" )
@section('body')
  <div class="display-none" class="preL">
    <div class="display-none" class="preloader3"></div>
  </div>
  
    <div class="box" >
        <div class="box-header with-border">
            <h3>Edit Product : {{$products->name ?? ''}}</h3>
        </div>
        <div class="box-body">
          
          
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" role="tablist" id="myTab">
    <li role="presentation" class="active"><a href="#lis" aria-controls="home" role="tab" data-toggle="tab">Product</a></li>
    
    <li role="presentation"><a href="#prospec" aria-controls="home" role="tab" data-toggle="tab">Product Specification</a></li>
   
    <li role="presentation"><a href="#tags" aria-controls="messages" role="tab" data-toggle="tab">Faq</a></li>

    <li role="presentation"><a href="#rel" aria-controls="messages" role="tab" data-toggle="tab">Related Product Setting</a></li>
   
    

    
  </ul>
   
        
    <div class="tab-content">
        

        <div role="tabpanel" class="tab-pane fade in active" id="lis">
            <div class="col-md-8">
               @include('seller/product/tab.edit.product') 
            </div>      
        </div>
        
        <div role="tabpanel" class="fade tab-pane" id="prospec">
            @include('seller.product.tab.edit.productspec') 
        </div>
         
         <div role="tabpanel" class="fade tab-pane" id="tags">
             @include('seller/product/tab.edit.faq') 
        </div>
        
        <div role="tabpanel" class="fade tab-pane" id="rel">
             @include('seller/product/tab.edit.show_related') 
        </div>
    
    </div>
          </div>


        </div>
    </div>

    <div class="modal fade" id="taxmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalCenterTitle">Product Tax Information(PTI)</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div id="accordion">
          @foreach(App\TaxClass::all() as $protax)
          <div class="card">
            <div class="card-header" id="headingThree">
              <h5 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#tbl{{$protax->id}}" aria-expanded="false" aria-controls="{{$protax->title}}">
                  {{$protax->title}}
                </button>
              </h5>
            </div>
            <div id="tbl{{$protax->id}}" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
              <div class="card-body">
                <table class="table table-bordered table-striped">
                  <tr>
                    <th>Tax Name
                      <img src="{{(url('images/info.png'))}}" class="height-15" data-toggle="popover" data-content="You Want to Choose Tax Class Then Apply same Tax Class And Tax Rate .">
                    </th>
                    <th>Tax Rate</th>
                    <th>Priority 
                      <img src="{{(url('images/info.png'))}}" class="height-15" data-toggle="popover" data-content="1 Priority Is Higher Priority And All Numeric Number Is Lowest Priority,
                      Priority Are Accept Is Numeric Number.">
                    </th>
                    <th>Based On <img src="{{(url('images/info.png'))}}" class="height-15" data-toggle="popover" data-content="You Want To Choose Billing address.. 
                   Then Billing Address And Zone Address Are Same Then Tax Will Be Applied,
                    And You Will Be Choose Store Address then Store Addrss And User Billing Address Is Same Then Tax Will Be Apply  ."></th>
                     <th>Zone Details<img src="{{(url('images/info.png'))}}" class="height-15" data-toggle="popover" data-content="You Want To Choose Billing address.. 
                   Then Billing Address And Zone Address Are Same Then Tax Will Be Applied,
                    And You Will Be Choose Store Address then Store Addrss And User Billing Address Is Same Then Tax Will Be Apply  ."></th>
                  </tr>
                    @foreach($protax->priority as $k=> $taxRate)
                      
                      @if(isset($protax->taxRate_id[$taxRate]))

                         <tr>
                            <td>
                              <?php  $taxs = App\Tax::where('id',$protax->taxRate_id[$taxRate])->first();?>
                              {{$taxs->name}}
                            </td>
                            <td>@if($taxs->type=='f'){{$taxs->rate}}{{'%'}}@else{{$taxs->rate}}@endif</td>
                            <td>{{$taxRate}}</td>
                             <td>{{$protax->based_on[$taxRate]}}</td>
                            <td>
                               <?php $zone = App\Zone::where('id',$taxs->zone_id)->first();?>
                               @if(!empty($zone))
                               {{$zone->state_id=='0'?'All Zone':$zone->title}}   
                               @endif
                            </td>
                        </tr>

                      @endif
                  
                    @endforeach
                </table>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
     
    </div>
  </div>
</div>
  <!-- Nav tabs -->

  <!-- Modal -->
<div data-backdrop="static" data-keyboard='false' class="modal fade" id="relProModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog model-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Related Product for <b>{{ $products->name }}</b></h4>
      </div>
      <div class="modal-body">
        
          <form action="{{ route('rel.store',$products->id) }}" method="POST">
            @csrf
            <label>Choose Products: <span class="required">*</span></label>
            <select class="js-example-basic-single width100" multiple="multiple" name="related_pro[]">
              @foreach($products->subcategory->products as $pro)
                @if($products->id != $pro->id)
                <option @if(isset($products->relproduct->related_pro)) @foreach($products->relproduct->related_pro as $c)
                                      {{$c == $pro->id ? 'selected' : ''}}
                                      @endforeach @endif value="{{ $pro->id }}">{{ $pro->name }}</option>
                @endif
              @endforeach
            </select>
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bulk Delete Modal -->
      <div id="bulk_delete" class="delete-modal modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <div class="delete-icon"></div>
            </div>
            <div class="modal-body text-center">
              <h4 class="modal-heading">Are You Sure ?</h4>
              <p>Do you really want to delete these products? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
             <form id="bulk_delete_form" method="post" action="{{ route('pro.specs.delete',$products->id) }}">
              @csrf
              {{ method_field('DELETE') }}
                <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Yes</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      @yield('tab-modal-area')

       
@endsection

