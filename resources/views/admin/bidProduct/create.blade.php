@extends('admin/layouts.master')
@section('title','Add New Used Product |')
 @section('body')
    <div class="box" >
        <div class="box-header">
            <h3>Add new Product</h3>
        </div>
        <div class="box-body">
            
        <div class="nav-tabs-custom">

          <ul class="nav nav-tabs" role="tablist" id="myTab">
            
            <li role="presentation" class="active"><a href="#lis" aria-controls="home" role="tab" data-toggle="tab">Product</a></li>
       
          </ul>
   
        
        <div class="tab-content">
            

            <div role="tabpanel" class="tab-pane fade in active" id="lis">
                <div class="col-md-8">
                   @include('admin/usedProduct/tab.product') 
                </div>      
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
                   @if($protax->priority)

                   @foreach($protax->priority as $k => $taxRate)
                   
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

                   @endif
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
@endsection

@section('custom-script')
	<script src="{{ url('js/subvar.js') }}"></script>
@endsection


    
