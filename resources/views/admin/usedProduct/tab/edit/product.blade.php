<br>
<form id="demo-form2" method="post" enctype="multipart/form-data" @if(!empty($products))
  action="{{url('admin/used-products/'.$products->id)}}" @endif data-parsley-validate
  class="form-horizontal form-label-left">
  {{csrf_field()}}
  {{ method_field('PUT') }}

  <div class="row">
    <div class="col-md-6">
      <label for="first-name">
        Product Name: <span class="required">*</span>
      </label>
      <input required="" placeholder="Please enter product name" type="text" id="first-name" name="name"
        value="{{$products->name ?? ''}}" class="form-control">
    </div>

    <div class="col-md-6">
      <label>
        Select Brand: <span class="required">*</span>
      </label>
      <select required="" name="brand_id" class="form-control col-md-7 col-xs-12">
        <option value="">Please Select</option>
        @if(!empty($brands))
        @foreach($brands as $brand)
        <option value="{{$brand->id}}" {{ $brand->id == $products->brand_id ? 'selected="selected"' : '' }}>
          {{$brand->name}} </option>
        @endforeach
        @endif
      </select>
    </div>

    <div class="margin-top-15 col-md-4">
      <label for="first-name">
        Category: <span class="required">*</span>
      </label>
      <select required="" name="category_id" id="category_id" class="form-control select2">
        <option value="">Please Select</option>
        @if(!empty($categorys))
        @foreach($categorys as $category)
        <option value="{{$category->id}}" {{ $products->category_id == $category->id ? 'selected="selected"' : '' }}>
          {{$category->title}} </option>
        @endforeach
        @endif
      </select>
    </div>

    <div class="margin-top-15 col-md-4">
      <label>
        Subcategory: <span class="required">*</span>
      </label>
      <select required="" name="child" id="upload_id" class="form-control select2">
        <option value="">Please Select</option>
        @if(!empty($child))
        @foreach($child as $category)
        <option value="{{$category->id}}" {{ $products->child == $category->id ? 'selected="selected"' : '' }}>
          {{$category->title}}
        </option>
        @endforeach
        @endif
      </select>
    </div>

    <div class="margin-top-15 col-md-4">
      <label>
        Childcategory:
      </label>
      <select name="grand_id" id="grand" class="form-control select2">
        <option value="">Please choose</option>
        @if(!empty($child))
        @foreach($products->subcategory->childcategory as $category)
        <option value="{{$category->id}}" {{ $products->grand_id == $category->id ? 'selected="selected"' : '' }}>
          {{$category->title}}
        </option>
        @endforeach
        @endif
      </select>
    </div>

    <div class="last_btn col-md-12">
      <label>
        Select Store:
      </label>
      <select required="" name="store_id" class="form-control select2">


        @foreach($stores as $store)
        <optgroup label="Store Owner • {{ $store->owner }}">
          <option {{ $products->store_id == $store->storeid ? "selected" : "" }} value="{{ $store->storeid }}">
            {{ $store->storename }}</option>
        </optgroup>
        @endforeach


      </select>
      <small class="txt-desc">(Please Choose Store Name )</small>
    </div>


    <div class="margin-top-15 col-md-12">
      <label for="first-name"> Key Features :
      </label>
      <textarea class="form-control" id="editor2" name="key_features">{!! $products->key_features !!} 
                         </textarea>
    </div>

    <div class="margin-top-15 col-md-12">
      <label for="first-name">Description:</label>
      <textarea id="editor1" value="{{old('des' ?? '')}}" name="des" class="form-control">{!! $products->des !!} 
                         </textarea>
      <small class="txt-desc">(Please Enter Product Description)</small>
    </div>

    <div class="margin-top-15 col-md-4">
      <label for="warranty_info">Warranty:</label>

      <label>(Duration)</label>
      <select class="form-control" name="w_d" id="">
        <option>None</option>
        @for($i=1;$i<=12;$i++) <option {{ $products->w_d == $i ? "selected" : "" }} value="{{ $i }}">{{ $i }}</option>
          @endfor
      </select>
    </div>

    <div class="margin-top-15 col-md-4">
      <label>Days/Months/Year:</label>
      <select class="form-control" name="w_my" id="">
        <option>None</option>
        <option {{ $products->w_my == 'day' ? "selected" : "" }} value="day">Day</option>
        <option {{ $products->w_my == 'month' ? "selected" : "" }} value="month">Month</option>
        <option {{ $products->w_my == 'year' ? "selected" : "" }} value="year">Year</option>
      </select>
    </div>

    <div class="margin-top-15 col-md-4">
      <label>Type:</label>
      <select class="form-control" name="w_type" id="">
        <option>None</option>
        <option {{ $products->w_type == 'Guarantee' ? "selected" : "" }} value="Guarantee">Guarantee</option>
        <option {{ $products->w_type == 'Warranty' ? "selected" : "" }} value="Warranty">Warranty</option>
      </select>
    </div>

    <div class="margin-top-15 col-md-6">

      <label>
        Start Selling From:
      </label>
      <div class='input-group date' id='datetimepicker1'>
        <input value="{{ $products->selling_start_at }}" name="selling_start_at" type='text' class="form-control" />
        <span class="input-group-addon">
          <span class="glyphicon glyphicon-calendar"></span>
        </span>
      </div>

    </div>


    <div class="margin-top-15 col-md-6">
      <label>
        Tags:
      </label>
      <input value="{{ $products->tags }}" placeholder="Please enter tag seprated by Comma(,)" type="text" name="tags"
        class="form-control">

    </div>

    <div class="margin-top-15 col-md-12">
      <div class="row">
        <div class="col-md-6">
          <label>
            Model:
          </label>

          <input type="text" id="first-name" name="model" class="form-control" placeholder="Please Enter Model Number"
            value="{{ $products->model }}">
        </div>

        <div class="col-md-6">
          <label for="first-name">
            SKU:
          </label>
          <input type="text" id="first-name" name="sku" value="{{ $products->sku }}" placeholder="Please enter SKU"
            class="form-control">
        </div>



      </div>
    </div>

    <div class="margin-top-15 col-md-12">
      <label class="switch">

        <input {{ $products->tax_r != '' ? "checked" : "" }} type="checkbox" id="tax_manual"
          class="toggle-input toggle-buttons" name="tax_manual">
        <span class="knob"></span>

      </label>
      <label class="ptax">Price Including Tax ?</label>

    </div>


    <div class="margin-top-15 col-md-6">

      <label>
        Price: <span class="required">*</span>
        <span class="help-block">(Price you entering is in {{ $genrals_settings->currency_code }})</span>
      </label>
      <input pattern="[0-9]+(\.[0-9][0-9]?)?" title="Price Format must be in this format : 200 or 200.25" required=""
        type="text" id="first-name" name="price" value="{{$products->vender_price ?? ''}}" class="form-control">
      <br>
      <small class="text-muted"><i class="fa fa-question-circle"></i> Don't put comma whilt entering PRICE</small>

    </div>

    <div class="margin-top-15 col-md-6">

      <label>
        Offer Price:
        <span class="help-block">(Offer Price you entering is in {{ $genrals_settings->currency_code }})</span>
      </label>
      <input title="Offer price Format must be in this format : 200 or 200.25" pattern="[0-9]+(\.[0-9][0-9]?)?"
        type="text" id="first-name" name="offer_price" class="form-control"
        value="{{$products->vender_offer_price ?? ''}}">
      <br>
      <small class="text-muted"><i class="fa fa-question-circle"></i> Don't put comma whilt entering OFFER PRICE</small>

    </div>

    <div class="{{ $products->tax_r !='' ? "" : 'display-none' }}" id="manual_tax">

      <div class="margin-top-15 col-md-6">
        <label>Tax Applied (In %) <span class="required">*</span></label>
        <div class="input-group">
          <input {{ $products->tax_r != '' ? "required" : "" }} value="{{ $products->tax_r }}" id="tax_r" type="number"
            min="0" class="form-control" name="tax_r" placeholder="0">
          <span class="input-group-addon">%</span>
        </div>
      </div>

      <div class="margin-top-15 col-md-6">
        <label>Tax Name: <span class="required">*</span></label>
        <input {{ $products->tax_r != '' ? "required" : "" }} type="text" id="tax_name" class="form-control"
          name="tax_name" title="Tax rate must without % sign" placeholder="Enter Tax Name"
          value="{{ $products->tax_name }}">
      </div>

    </div>


    <div class="margin-top-15 col-md-12">
      <div class="{{ $products->tax_r != ''? 'display-none' : "" }}" id="tax_class">
        <label>
          Tax Classes:
        </label>
        <select {{ $products->tax_r == '' ? "required" : "" }} name="tax" class="form-control">
          <option value="">Please Choose..</option>
          @foreach(App\TaxClass::all() as $tax)
          <option value="{{$tax->id}}"
            @if(!empty($products)){{ $tax->id == $products->tax ? 'selected="selected"' : '' }}@endif>{{$tax->title}}
          </option>
          @endforeach
        </select>
        <small class="txt-desc">(Please Choose Yes Then Start Sale This Product )</small>
        <img src="{{(url('images/info.png'))}}" data-toggle="modal" data-target="#taxmodal" class="height-15"><br>

      </div>
    </div>


    <div class="margin-top-15 col-md-4">


      <label>
        Free Shipping:
      </label>

      <input {{ $products->free_shipping == "0" ? '' : "checked" }} class="tgl tgl-skewed" name="free_shipping"
        id="frees" type="checkbox">
      <label class="tgl-btn" data-tg-off="No" data-tg-on="Yes" for="frees"></label>

      <small class="txt-desc">(If Choose Yes Then Free Shipping Start) </small>

    </div>

    <div class="margin-top-15 col-md-4">
      <label for="first-name">
        Featured:
      </label>

      <input class="tgl tgl-skewed" id="toggle-event2" @if(!empty($products))
        <?php echo ($products->featured=='1')?'checked':'' ?> @endif type="checkbox" name="featured">
      <label class="tgl-btn" data-tg-off="No" data-tg-on="Yes" for="toggle-event2"></label>

      <small class="txt-desc">(If enable than Product will be featured )</small>
    </div>

    <div class="margin-top-15 col-md-4">
      <label for="first-name">
        Status:
      </label>

      <input class="tgl tgl-skewed" id="toggle-event3" type="checkbox" @if(!empty($products))
        <?php echo ($products->status=='1')?'checked':'' ?> @endif>
      <label class="tgl-btn" data-tg-off="Deactive" data-tg-on="Active" for="toggle-event3"></label>
      <input type="hidden" name="status" value="{{$products->status}}" id="status3">

      <small class="txt-desc">(Please Choose Status) </small>
    </div>

    <div class="margin-top-15 col-md-12">
      <label for="first-name">
        Cancel Available:
      </label>

      <input id="toggle-event4" class="tgl tgl-skewed" type="checkbox" @if(!empty($products))
        <?php echo ($products->cancel_avl=='1')?'checked':'' ?> @endif>
      <label class="tgl-btn" data-tg-off="Deactive" data-tg-on="Active" for="toggle-event4"></label>
      <input @if(!empty($products)) value="{{ $products->cancel_avl }}" @else value="0" @endif type="hidden"
        name="cancel_avl" id="status4">
      <small class="txt-desc">(Please Choose Cancel Available )</small>
    </div>

    <div class="margin-top-15 col-md-12">
      <label for="first-name">
        Cash On Delivery:
      </label>

      <input id="codcheck" name="codcheck" class="tgl tgl-skewed" type="checkbox" @if(!empty($products))
        <?php echo ($products->codcheck=='1')?'checked':'' ?> @endif>
      <label class="tgl-btn" data-tg-off="Disable" data-tg-on="Enable" for="codcheck"></label>

      <small class="txt-desc">(Please Choose Cash on Delivery Available On This Product or Not)</small>
    </div>

    <div class="last_btn col-md-6">

      <label for="">Return Available :</label>
      <select required="" class="col-md-4 form-control" id="choose_policy" name="return_avbls">
        <option value="">Please choose an option</option>
        <option {{ $products->return_avbl=='1' ? "selected" : "" }} value="1">Return Available</option>
        <option {{ $products->return_avbl=='0' ? "selected" : "" }} value="0">Return Not Available</option>
      </select>
      <br>
      <small class="text-desc">(Please choose an option that return will be available for this product or not)</small>


    </div>

    <div id="policy"
      class="@if(!empty($products)) {{ $products->return_avbl==1 ? '' : 'display-none' }}  @else 'display-none' @endif last_btn col-md-6">
      <label>
        Select Return Policy: <span class="required">*</span>
      </label>
      <select name="return_policy" class="form-control col-md-7 col-xs-12">
        <option value="">Please choose an option</option>

        @foreach(App\admin_return_product::where('created_by',Auth::user()->id)->get() as $policy)
        <option @if(!empty($products)) {{ $products->return_policy == $policy->id ? "selected" : "" }} @endif
          value="{{ $policy->id }}">{{ $policy->name }}</option>
        @endforeach
      </select>
    </div>








    <div class="margin-top-15 col-md-12">
      <button type="submit" class="col-md-4 btn btn-block btn-primary"><i class="fa fa-save"></i> Update Product</button>
    </div>

    <!-- Main Row end-->
  </div>




  <div class="box-footer">

  </div>
</form>