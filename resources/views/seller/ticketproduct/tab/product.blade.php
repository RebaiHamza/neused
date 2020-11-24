<br>
   <form action="{{url('seller/ticketproducts/')}}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        
                    <div class="row">

                      <div class="col-md-6">
                        <label for="first-name">
                        Ticket Title: <span class="color-red" class="required">*</span>
                       </label>

                        <input required="" placeholder="Please enter ticket title" type="text" id="first-name" autofocus="" name="name" value="{{ old('name') }}" class="form-control">
                      </div>

                      <div class="col-md-6">
                        <label>
                         Select Brand: <span class="required">*</span>
                        </label>
                          <select required="" name="brand_id" class="select2 form-control col-md-7 col-xs-12">
                          <option value="">Please Select</option>
                          @if(!empty($brands))
                          @foreach($brands as $brand)
                           <option value="{{$brand->id}}">{{$brand->name}} </option>
                          @endforeach
                          @endif
                        </select>
                      </div>
                      
                     

                        <div class="last_btn col-md-4">
                          <label for="first-name">
                            Category: <span class="required">*</span>
                          </label>

                        <select required="" name="category_id" id="category_id" class="form-control select2">
                          <option value="">Please Select</option>
                         
                          @if(!empty($categorys))
                          @foreach($categorys as $category)
                          <option value="{{$category->id}}"> {{$category->title}} </option>
                          @endforeach
                          @endif
                          
                        </select>
                        </div>

                        <div class="last_btn col-md-4">
                          
                          <label>
                           Subcategory: <span class="required">*</span>
                          </label>
                          
                          <select required="" name="child" id="upload_id" class="form-control select2">
                            <option value="">Please Select</option>
                            @if(!empty($child))
                            @foreach($child as $category)
                            <option value="{{$category->id}}"> {{$category->title}} 
                            </option>
                            @endforeach
                            @endif
                          </select>
                        </div>

                        <div class="last_btn col-md-4">
                          
                          <label>
                            Child Category:
                          </label>

                        <select name="grand_id" id="grand" class="form-control select2">
                          
                          @if(!empty($child))
                          @foreach($grand as $category)
                          <option value="{{$category->id}}"> {{$category->title}} 
                          </option>
                          @endforeach
                          @endif
                          </select>

                        </div>
                      

                      <div class="last_btn col-md-12">
                         <label>
                          Store:
                       </label>
                         <select required="" name="store_id"  class="form-control">
                          
                         
                          <option value="{{Auth::user()->store->id}}">{{Auth::user()->store->name}}</option>
                         
                          
                        </select>
                        <small class="txt-desc">(Please Choose Store Name )</small>
                      </div>

                      <div class="col-md-12 margin-top-15">
                        <label for="first-name"> Key Features :
                        </label>
                         <textarea class="form-control" id="editor2" name="key_features">{{ old('key_features') }}
                         </textarea>
                      </div>
                      
                      <div class="col-md-12 margin-top-15">
                        <label for="first-name"> Description:
                        </label>
                         <textarea id="editor1" name="des">{{ old('des') }}
                         </textarea>
                      </div>

                      <div class="col-md-4 margin-top-15">
                        <label for="warranty_info">Warranty:</label>

                        <label>(Duration)</label>
                        <select class="form-control" name="w_d" id="">
                          <option>None</option>
                          @for($i=0;$i<=12;$i++)
                            <option  value="{{ $i }}">{{ $i }}</option>
                          @endfor
                        </select>
                      </div>
                      
                      <div class="col-md-4 margin-top-15">
                         <label>Days/Months/Year:</label>

                        <select class="form-control" name="w_my" id="">
                          <option>None</option>
                          <option value="day">Day</option>
                          <option value="month">Month</option>
                          <option value="year">Year</option>
                        </select>
                      </div>
                      
                      <div class="col-md-4 margin-top-15">
                         <label>Type:</label>
                        <select class="form-control" name="w_type" id="">
                          <option>None</option>
                          <option value="Guarantee">Guarantee</option>
                          <option value="Warranty">Warranty</option>
                        </select>
                      </div>

                    <div class="col-md-6 margin-top-15">
                        
                        <label>
                        Start Selling From:
                        </label>
                        <div class='input-group date' id='datetimepicker1'>
                          <input name="selling_start_at" type='text' class="form-control" />
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
           
                    </div>


                      <div class="col-md-6 margin-top-15">
                        <label>
                        Tags:
                        </label>

                        <input placeholder="Please Enter Tag Seprated By Comma" type="text" id="first-name" name="tags" data-role="tagsinput" value="{{ old('tags') }}" class="form-control">
                      </div>

                      <div class="col-md-12 margin-top-15">
                        <div class="row">
                          <div class="col-md-6">
                            <label>
                              Model:
                            </label>

                            <input type="text" id="first-name" name="model" class="form-control" placeholder="Please Enter Model Number" value="{{ old('model') }}" >
                          </div>

                          <div class="col-md-6">
                            <label for="first-name">
                              SKU:
                           </label>
                          <input type="text" id="first-name" name="sku" value="{{ old('sku') }}" placeholder="Please enter SKU" class="form-control">
                          </div>

                          

                        </div>
                      </div>

                      <div class="col-md-12 margin-top-15">
                        <label class="switch"> 
                          
                          <input checked type="checkbox" id="tax_manual" class="toggle-input toggle-buttons" name="tax_manual" >
                          <span class="knob"></span>

                        </label>
                        <label class="product-tax-include">Price Including Tax ?</label>

                      </div>

                      <div class="col-md-6 margin-top-15">

                         <label>
                            Price: <span class="required">*</span>
                            <span class="help-block">(Price you entering is in {{ $genrals_settings->currency_code }})</span>
                         </label>

                          <input title="Offer price Format must be in this format : 200 or 200.25" pattern="[0-9]+(\.[0-9][0-9]?)?" required="" type="text" id="first-name" name="price" value="{{ old('price') }}" placeholder="Please enter Ticket price" class="form-control">

                           <br>
                          <small class="text-muted"><i class="fa fa-question-circle"></i> Don't put comma whilt entering PRICE</small>
                          
                      </div>

                      <div class="col-md-6 margin-top-15">

                        <label>
                          Offer Price:
                          <span class="help-block">(Offer Price you entering is in {{ $genrals_settings->currency_code }})</span>
                        </label>
                          <input title="Offer price Format must be in this format : 200 or 200.25" pattern="[0-9]+(\.[0-9][0-9]?)?" type="text" id="first-name" name="offer_price" class="form-control" placeholder="Please enter product offer price" value="{{ old('offer_price') }}" >

                           <br>
                          <small class="text-muted"><i class="fa fa-question-circle"></i> Don't put comma whilt entering OFFER PRICE</small>

                      </div>

                      <div id="manual_tax">

                        <div class="col-md-6 margin-top-15">
                          <label>Tax Applied (In %) <span class="required">*</span></label>
                          <div class="input-group">
                            <input title="Tax rate must without % sign" value="{{ old('tax_r') }}" id="tax_r" type="number" min="0" class="form-control" name="tax_r" placeholder="0">
                            <span class="input-group-addon">%</span>
                          </div>
                        </div>

                        <div class="col-md-6 margin-top-15">
                          <label>Tax Name: <span class="required">*</span></label>
                          <input value="{{ old('tax_name') }}" type="text" id="tax_name" class="form-control" name="tax_name" placeholder="Enter Tax Name">
                        </div>

                      </div>

                    
                       <div class="col-md-12 margin-top-15">
                        

                        <div class="display-none" id="tax_class">
                          <label>
                            Tax Classes: 
                          </label>
                          <select id="tax_class_box" name="tax" class="form-control">
                            <option value="">Please Choose..</option>
                            @foreach(App\TaxClass::all() as $tax)
                              <option value="{{$tax->id}}">{{$tax->title}}</option>
                            @endforeach
                          </select>
                          <small class="txt-desc">(Please Choose Yes Then Start Sale This Ticket )</small>
                           <img src="{{(url('images/info.png'))}}" data-toggle="modal" data-target="#taxmodal" class="height-15"><br>
                        </div>
                          

                        </div>

                     

                     

                    <div class="col-md-4 margin-top-15">
                        
                          
                            <label>
                              Free Shipping:
                            </label>

                        <input name="free_shipping" class="tgl tgl-skewed" id="toggle-event5" type="checkbox"/>
                        <label class="tgl-btn" data-tg-off="No" data-tg-on="Yes" for="toggle-event5"></label>

                       <small class="txt-desc">(If Enabled Then Free Shipping will enabled for this ticket) </small>

                    </div>

            <div class="col-md-4 margin-top-15">
            <label for="first-name">
            Featured:
          </label>
                            
      
              <input class="tgl tgl-skewed" id="toggle-event2" type="checkbox"/>
              <label class="tgl-btn" data-tg-off="No" data-tg-on="Yes" for="toggle-event2"></label>


                        <input type="hidden" name="featured" id="featured">
                        <small class="txt-desc">(If enabled than ticket will be featured) </small>
                      </div>
                        
                      <div class="col-md-4 margin-top-15">
                         <label for="first-name">
                            Cancel Available:
                          </label>
                          
                        <input id="toggle-event4" class="tgl tgl-skewed" type="checkbox">
                        <label class="tgl-btn" data-tg-off="Deactive" data-tg-on="Active" for="toggle-event4"></label>
                        <input type="hidden" name="cancel_avl" id="status4">
                        <small class="txt-desc">(Please Choose  Cancel Available )</small>
                      </div>

                      <div class="col-md-12 margin-top-15">
                         <label for="first-name">
                           Cash On Delivery:
                          </label>
                          
                        <input id="codcheck" name="codcheck" class="tgl tgl-skewed" type="checkbox">
                        <label class="tgl-btn" data-tg-off="Disable" data-tg-on="Enable" for="codcheck"></label>
                        
                        <small class="txt-desc">(Please Choose Cash on Delivery Available On This Ticket or Not)</small>
                       </div>

                       

                      <div class="last_btn col-md-12">

                        <div class="row">

                           <div class="col-md-4">
                           <label for="">Return Available :</label>
                             <select required id="choose_policy" class="col-md-4 form-control" name="return_avbls">
                              <option value="">Please choose an option</option>
                              <option  value="1">Return Available</option>
                              <option  value="0">Return Not Available</option>
                            </select>
                            <br>
                              <small class="text-desc">(Please choose an option that return will be available for this ticket or not)</small>
                       

                        </div>

                         <div id="policy" class="col-md-4 'display-none'">
                           <label for="">Choose Return Policy: </label>
                             <select class="col-md-4 form-control" id="return_policy" name="return_policy">
                              <option value="">Please choose an option</option>
                              @foreach(App\admin_return_product::orderBy('id','DESC')->get() as $policy)
                                <option value="{{ $policy->id }}">{{ $policy->name }}</option>
                              @endforeach
                            </select>
                            <br>
                              <small class="text-desc">(Please choose an option that return will be available for this ticket or not)</small>
                       

                        </div>
                        
                         
                        </div>
                       
                       
                      </div>

                      <div class="col-md-6 margin-top-15">
                        <button type="submit" class="col-md-4 btn btn-block btn-primary"><i class="fa fa-plus"></i> Add Ticket</button>
                      </div>

                      <!-- Main row End-->
                    </div>
                     
                    
                      
                      <div class="box-footer">
                        
                      </div>
                    </form>
                        
   </form>

