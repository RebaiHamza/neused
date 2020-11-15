<br>
<div class="alert alert-warning">
  <p><i class="fa fa-info-circle" aria-hidden="true"></i> Important</p>

  <ul>
    <li>Altleast two images are required !</li>
    <li>Default image will be <b><i>Image #1</i></b></li>
  </ul>
</div>
<form action="{{url('admin/used-products/')}}" method="POST" enctype="multipart/form-data">
  {{csrf_field()}}

  <div class="row">

    <div class="col-md-6">
      <label for="first-name">
        Product Name: <span class="required">*</span>
      </label>

      <input required="" placeholder="Please enter product name" type="text" autofocus="" name="name"
        value="{{ old('name') }}" class="form-control">
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

      <select required="" name="category_id" id="category_id" class="select2 form-control">
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

      <select required="" name="child" id="upload_id" class="select2 form-control">
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

      <select name="grand_id" id="grand" class="select2 form-control">

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
        Select Owner :
      </label>
      <select required="" name="user_id" class="form-control select2">


        @foreach($users as $user)

        <option value="{{ $user->id }}">{{ $user->name }}</option>

        @endforeach


      </select>
      <small class="txt-desc">(Please Choose Owner Name )</small>
    </div>

    <div class="margin-top-15 col-md-12">
      <label for="editor2"> Key Features :
      </label>
      <textarea class="form-control" id="editor2" name="key_features">
                         </textarea>
    </div>

    <div class="margin-top-15 col-md-12">
      <label for="editor1"> Description:
      </label>
      <textarea id="editor1" name="des">{{ old('des') }}</textarea>
    </div>
    {{-- Images --}}
    <div class="margin-top-15"><br>
      <label style="padding-top: 10px;padding-left:10px;">
        Images:
      </label><br>
      <div class="col-md-4">
        <div class="panel panel-primary bg-primary height-shadow">
          <p class="padding5-15">Select Image 1</p>

          <div align="center" class="panel-body padding-0">

            <img id="preview1" align="center" width="150" height="150" src="{{ url('images/imagechoosebg.png') }}"
              alt="" class="margin-top-5 margin-bottom-10">


          </div>

          <div class="file-upload heyx">
            <div class="file-select">
              <div class="file-select-button" id="fileName">Choose File</div>
              <div class="file-select-name" id="noFile">No file chosen...</div>
              <input required name="image1" type="file" name="chooseFile" id="image1">
            </div>
          </div>


        </div>

      </div>

      <div class="col-md-4">
        <div class="panel panel-primary bg-primary height-shadow">
          <p class="padding5-15">Select Image 2</p>

          <div align="center" class="panel-body padding-0">

            <img id="preview2" align="center" width="150" height="150" src="{{ url('images/imagechoosebg.png') }}"
              alt="" class="margin-top-5 margin-bottom-10">


          </div>

          <div class="file-upload2 heyx">
            <div class="file-select2">
              <div class="file-select-button2" id="fileName2">Choose File</div>
              <div class="file-select-name2" id="noFile2">No file chosen...</div>
              <input required name="image2" type="file" name="chooseFile" id="image2">
            </div>
          </div>


        </div>

      </div>

      <div class="col-md-4">
        <div class="panel panel-primary bg-primary height-shadow">
          <p class="padding5-15">Select Image 3</p>

          <div align="center" class="panel-body padding-0">

            <img id="preview3" align="center" width="150" height="150" src="{{ url('images/imagechoosebg.png') }}"
              alt="" class="margin-top-5">


          </div>

          <div class="file-upload3 heyx">
            <div class="file-select3">
              <div class="file-select-button3" id="fileName3">Choose File</div>
              <div class="file-select-name3" id="noFile3">No file chosen...</div>
              <input name="image3" type="file" name="chooseFile" id="image3">
            </div>
          </div>


        </div>

      </div>
    </div>
    <div class="margin-top-15">
      <div class="col-md-4">

        <div class="panel panel-primary bg-primary height-shadow">
          <p class="padding5-15">Select Image 4</p>

          <div align="center" class="panel-body padding-0">

            <img id="preview4" align="center" width="150" height="150" src="{{ url('images/imagechoosebg.png') }}"
              alt="" class="margin-top-5 margin-bottom-10">


          </div>

          <div class="file-upload4 heyx">
            <div class="file-select4">
              <div class="file-select-button4" id="fileName4">Choose File</div>
              <div class="file-select-name4" id="noFile4">No file chosen...</div>
              <input name="image4" type="file" name="chooseFile" id="image4">
            </div>
          </div>


        </div>

      </div>

      <div class="col-md-4">

        <div class="panel panel-primary bg-primary height-shadow">
          <p class="padding5-15">Select Image 5</p>

          <div align="center" class="panel-body padding-0">

            <img id="preview5" align="center" width="150" height="150" src="{{ url('images/imagechoosebg.png') }}"
              alt="" class="margin-top-5 margin-bottom-10">


          </div>

          <div class="file-upload5 heyx">
            <div class="file-select5">
              <div class="file-select-button5" id="fileName5">Choose File</div>
              <div class="file-select-name5" id="noFile5">No file chosen...</div>
              <input name="image5" type="file" name="chooseFile" id="image5">
            </div>
          </div>


        </div>

      </div>


      <div class="col-md-4">

        <div class="panel panel-primary bg-primary height-shadow">
          <p class="padding5-15">Select Image 6</p>

          <div align="center" class="panel-body padding-0">

            <img id="preview6" align="center" width="150" height="150" src="{{ url('images/imagechoosebg.png') }}"
              alt="" class="margin-top-5 margin-bottom-10">
          </div>
          <div class="file-upload6 heyx">
            <div class="file-select6">
              <div class="file-select-button6" id="fileName6">Choose File</div>
              <div class="file-select-name6" id="noFile6">No file chosen...</div>
              <input name="image6" type="file" name="chooseFile" id="image6">
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="margin-top-15 col-md-4">
      <label for="warranty_info">Warranty:</label>

      <label>(Duration)</label>
      <select class="form-control" name="w_d" id="">
        <option>None</option>
        @for($i=0;$i<=12;$i++) <option value="{{ $i }}">{{ $i }}</option>
          @endfor
      </select>
    </div>

    <div class="margin-top-15 col-md-4">
      <label>Days/Months/Year:</label>

      <select class="form-control" name="w_my" id="">
        <option>None</option>
        <option value="day">Day</option>
        <option value="month">Month</option>
        <option value="year">Year</option>
      </select>
    </div>

    <div class="margin-top-15 col-md-4">
      <label>Type:</label>
      <select class="form-control" name="w_type" id="">
        <option>None</option>
        <option value="Guarantee">Guarantee</option>
        <option value="Warranty">Warranty</option>
      </select>
    </div>

    <div class="margin-top-15 col-md-6">

      <label>
        Start Selling From:
      </label>
      <div class='input-group date' id='datetimepicker1'>
        <input value="{{ old('selling_start_at') }}" name="selling_start_at" type='text' class="form-control" />
        <span class="input-group-addon">
          <span class="glyphicon glyphicon-calendar"></span>
        </span>
      </div>

    </div>


    <div class="margin-top-15 col-md-6">
      <label>
        Tags:
      </label>

      <input value="{{ old('tags') }}" placeholder="Please enter tag seprated by Comma(,)" type="text" name="tags"
        class="form-control">
    </div>

    <div class="margin-top-15 col-md-12">
      <div class="row">
        <div class="col-md-6">
          <label>
            Model:
          </label>

          <input type="text" id="first-name" name="model" class="form-control" placeholder="Please Enter Model Number"
            value="{{ old('model') }}">
        </div>

        <div class="col-md-6">
          <label for="first-name">
            SKU:
          </label>
          <input type="text" id="first-name" name="sku" value="{{ old('sku') }}" placeholder="Please enter SKU"
            class="form-control">
        </div>



      </div>
    </div>

    <div class="margin-top-15 col-md-12">
      <label class="switch">

        <input checked type="checkbox" id="tax_manual" class="toggle-input toggle-buttons" name="tax_manual">
        <span class="knob"></span>

      </label>
      <label class="ptax">Price Including Tax ?</label>

    </div>

    <div class="margin-top-15 col-md-12">

      <label>
        Price: <span class="required">*</span>
        <span class="help-block">(Price you entering is in {{ $genrals_settings->currency_code }})</span>
      </label>

      <input pattern="[0-9]+(\.[0-9][0-9]?)?" title="Price Format must be in this format : 200 or 200.25" required=""
        type="text" id="first-name" name="price" value="{{ old('price') }}" placeholder="Please enter product price"
        class="form-control">
      <br>
      <small class="text-muted"><i class="fa fa-question-circle"></i> Don't put comma whilt entering PRICE</small>
    </div>

    {{-- <div class="margin-top-15 col-md-6">

                        <label>
                          Offer Price:
                          <span class="help-block">(Offer Price you entering is in {{ $genrals_settings->currency_code }})</span>
    </label>
    <input title="Offer price Format must be in this format : 200 or 200.25" pattern="[0-9]+(\.[0-9][0-9]?)?"
      type="text" id="first-name" name="offer_price" class="form-control" placeholder="Please enter product offer price"
      value="{{ old('offer_price') }}">
    <br>
    <small class="text-muted"><i class="fa fa-question-circle"></i> Don't put comma whilt entering OFFER price</small>
  </div> --}}

  {{-- <div id="manual_tax">

                        <div class="margin-top-15 col-md-6">
                          <label>Tax Applied (In %) <span class="required">*</span></label>
                          <div class="input-group">
                            <input title="Tax rate must without % sign" value="{{ old('tax_r') }}" id="tax_r"
  type="number" min="0" class="form-control" name="tax_r" placeholder="0">
  <span class="input-group-addon">%</span>
  </div>
  </div>

  <div class="margin-top-15 col-md-6">
    <label>Tax Name: <span class="required">*</span></label>
    <input value="{{ old('tax_name') }}" type="text" id="tax_name" class="form-control" name="tax_name"
      placeholder="Enter Tax Name">
  </div>

  </div> --}}


  <div class="margin-top-15 col-md-12">


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
      <small class="txt-desc">(Please Choose Yes Then Start Sale This Product )</small>
      <img src="{{(url('images/info.png'))}}" data-toggle="modal" data-target="#taxmodal" height="15px"><br>
    </div>


  </div>





  <div class="margin-top-15 col-md-4">


    <label>
      Free Shipping:
    </label>

    <input name="free_shipping" class="tgl tgl-skewed" id="toggle-event5" type="checkbox" />
    <label class="tgl-btn" data-tg-off="No" data-tg-on="Yes" for="toggle-event5"></label>

    <small class="txt-desc">(If Enabled Then Free Shipping will enabled for this product) </small>

  </div>

  <div class="margin-top-15 col-md-4">
    <label for="first-name">
      Featured:
    </label>


    <input class="tgl tgl-skewed" id="toggle-event2" type="checkbox" />
    <label class="tgl-btn" data-tg-off="No" data-tg-on="Yes" for="toggle-event2"></label>


    <input type="hidden" name="featured" id="featured">
    <small class="txt-desc">(If enabled than product will be featured) </small>
  </div>

  <div class="margin-top-15 col-md-4">
    <label for="first-name">
      Status:
    </label>

    <input id="toggle-event3" class="tgl tgl-skewed" type="checkbox">
    <label class="tgl-btn" data-tg-off="Deactive" data-tg-on="Active" for="toggle-event3"></label>
    <input @if(!empty($products)) value="{{ $products->status }}" @else value="0" @endif type="hidden" name="status"
      id="status3">
    <small class="txt-desc">(Please Choose Status )</small>

  </div>

  <div class="col-md-12">
    <label for="first-name">
      Cancel Available:
    </label>

    <input id="toggle-event4" class="tgl tgl-skewed" type="checkbox">
    <label class="tgl-btn" data-tg-off="Deactive" data-tg-on="Active" for="toggle-event4"></label>
    <input type="hidden" name="cancel_avl" id="status4">
    <small class="txt-desc">(Please Choose Cancel Available )</small>
  </div>

  <div class="margin-top-15 col-md-12">
    <label for="first-name">
      Cash On Delivery:
    </label>

    <input id="codcheck" name="codcheck" class="tgl tgl-skewed" type="checkbox">
    <label class="tgl-btn" data-tg-off="Disable" data-tg-on="Enable" for="codcheck"></label>

    <small class="txt-desc">(Please Choose Cash on Delivery Available On This Product or Not)</small>
  </div>



  <div class="last_btn col-md-12">

    <div class="row">

      <div class="col-md-4">
        <label for="">Return Available :</label>
        <select required id="choose_policy" class="col-md-4 form-control" name="return_avbls">
          <option value="">Please choose an option</option>
          <option value="1">Return Available</option>
          <option value="0">Return Not Available</option>
        </select>
        <br>
        <small class="text-desc">(Please choose an option that return will be available for this product or not)</small>


      </div>

      <div id="policy" class="'display-none' col-md-4">
        <label for="">Choose Return Policy: </label>
        <select class="col-md-4 form-control" id="return_policy" name="return_policy">
          <option value="">Please choose an option</option>
          @foreach(App\admin_return_product::where('created_by',Auth::user()->id)->get() as $policy)
          <option value="{{ $policy->id }}">{{ $policy->name }}</option>
          @endforeach
        </select>
        <br>
        <small class="text-desc">(Please choose an option that return will be available for this product or not)</small>


      </div>


    </div>


  </div>

  <div class="margin-top-15 scol-md-6">
    <button type="submit" class="col-md-4 btn btn-block btn-primary"><i class="fa fa-plus"></i> Add Product</button>
  </div>

  <!-- Main row End-->
  </div>



  <div class="box-footer">

  </div>
</form>

</form>