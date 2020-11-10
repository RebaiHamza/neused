@extends("admin/layouts.master")
@section('title',"Edit Advertisement #$adv->id | ")
@section("body")
  <div class="box">
    <div class="box-header with-border">
      <div class="box-title">
        <a title="Cancel and go back !" href="{{ route('adv.index') }}" class="btn btn-md btn-default"><i class="fa fa-reply"></i>
        </a> {{ $adv->layout }} - Advertisement #{{ $adv->id }}
      </div>
    </div>

    <div class="box-body">
      <form action="{{ route('adv.update',$adv->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{ method_field("PUT") }}
        <div class="row">

          <div class="col-md-6">
            <label class="h5">Select Advertise Position:</label>
            <select required name="position" id="" class="form-control">
              <option value="">Please select position of advertisement</option>
              <option {{ $adv->position == 'beforeslider' ? "selected" : "" }} value="beforeslider">Above Slider</option>
              <option {{ $adv->position == 'abovenewproduct' ? "selected" : "" }} value="abovenewproduct">Above New Product Widget</option>
              <option {{ $adv->position == 'abovetopcategory' ? "selected" : "" }} value="abovetopcategory">Above Top Category</option>
              <option {{ $adv->position == 'abovelatestblog' ? "selected" : "" }} value="abovelatestblog">Above Latest Blog Widget</option>
              <option {{ $adv->position == 'abovefeaturedproduct' ? "selected" : "" }} value="abovefeaturedproduct">Above Featured Product Widget</option>
              <option {{ $adv->position == 'afterfeaturedproduct' ? "selected" : "" }} value="afterfeaturedproduct">Below Featured Product Widget</option>
            </select>

            <small class="text-muted"><i class="fa fa-question-circle"></i> Select the advertisement position</small>
            <br><br>
            <label>Status</label>
            <br>
            <label class="switch">
                    <input {{ $adv->status == 1 ? "checked" : "" }} type="checkbox" class="quizfp toggle-input toggle-buttons" name="status">
                    <span class="knob"></span>
                 </label>
          </div>
          
          <div class="col-md-6">
            @if($adv->layout == 'Three Image Layout')
              <img class="img-responsive" title="Three Image Layout" src="{{ url('images/advLayout3.png') }}" alt="three_image_adv_layout">
              <input type="hidden" name="layout" value="{{ $adv->layout }}">
            @elseif($adv->layout == 'Two non equal image layout')
              <img class="img-responsive" title="Two Non Equal Image Layout" src="{{ url('images/advLayout2.png') }}" alt="two_non_equal_image_adv_layout">
              <input type="hidden" name="layout" value="{{ $adv->layout }}">
            @elseif($adv->layout == 'Two equal image layout')
              <img class="img-responsive" title="Two Equal Image Layout" src="{{ url('images/advLayout1.png') }}" alt="two_equal_image_adv_layout">
              <input type="hidden" name="layout" value="{{ $adv->layout }}">
            @elseif($adv->layout == 'Single image layout')
              <img class="img-responsive" title="Single Image Layout" src="{{ url('images/singleImage.png') }}" alt="single_image_adv_layout">
              <input type="hidden" name="layout" value="{{ $adv->layout }}">
            @endif
          </div>

          <div class="col-md-12">
            <br>
            @if($adv->layout == 'Three Image Layout')
              <img title="Preview" id="preview1" align="center" height="100" src="{{ url('images/adv/'.$adv->image1) }}" alt=""/>
              <img title="Preview" id="preview2" align="center" height="100" src="{{ url('images/adv/'.$adv->image2) }}" alt=""/>
              <img title="Preview" id="preview3" align="center" height="100" src="{{ url('images/adv/'.$adv->image3) }}" alt=""/>
            @elseif($adv->layout == 'Two non equal image layout')
               <img title="Preview" id="preview1" align="center" height="100" src="{{ url('images/adv/'.$adv->image1) }}" alt=""/>
              <img title="Preview" id="preview2" align="center" height="100" src="{{ url('images/adv/'.$adv->image2) }}" alt=""/>
            @elseif($adv->layout == 'Two equal image layout')
              <div class="row">
                <div class="col-md-6">
                  <img title="Preview" id="preview1" align="center" height="100" src="{{ url('images/adv/'.$adv->image1) }}" alt=""/>
                </div>
                <div class="col-md-6">
                  <img title="Preview" id="preview2" align="center" height="100" src="{{ url('images/adv/'.$adv->image2) }}" alt=""/>
                </div>
              </div>
              
              
            @elseif($adv->layout == 'Single image layout')
              <img title="Preview" id="preview1" align="center" height="100" src="{{ url('images/adv/'.$adv->image2) }}" alt=""/>
            @endif
          </div>
          
          <div class="col-md-12">
            <hr>
            @if($adv->layout == 'Three Image Layout')
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Choose Image 1: <span class="required">*</span> <small class="text-muted"><i class="fa fa-question-circle"></i>
                   Recommended image size (438 x 240px)</small></label>
                  <input id="image1" type="file" class="form-control" name="image1"/>
                  
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Image 1 Link By: <span class="required">*</span></label>
                  <select name="img1linkby" id="img1linkby" class="form-control">
                    <option {{ $adv->cat_id1 != '' ? "selected" : "" }} value="linkbycat">Link By Categories</option>
                    <option {{ $adv->pro_id1 != '' ? "selected" : "" }} value="linkbypro">Link By Product</option>
                    <option {{ $adv->url1 != '' ? "selected" : "" }} value="linkbyurl">Link By Custom URL</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                      <div id="catbox1" class="form-group {{ $adv->cat_id1 != '' ? '' : 'display-none' }}">
                        <label>Select Category:</label>
                        <select name="cat_id1" id="" class="select2 form-control">
                                  @foreach(App\Category::where('status','=','1')->get() as $cat)
                                    <option {{ $adv->cat_id1 == $cat->id ? "selected" : "" }} value="{{ $cat->id }}">{{ $cat->title }}</option>
                                  @endforeach
                            </select>
                      </div>

                      <div id="probox1" class="form-group {{ $adv->pro_id1 != '' ? '' : 'display-none' }}">
                        <label>Select Product:</label>
                        <select name="pro_id1" id="" class="select2 form-control">
                                  @foreach($p = App\Product::where('status','=','1')->get() as $pro)
                                    @if(count($pro->subvariants)>0)
                                      <option {{ $adv->pro_id1 == $pro->id ? "selected" : "" }} value="{{ $pro->id }}">{{ $pro->name }}</option>
                                    @endif
                                  @endforeach
                            </select>
                      </div>

                      <div id="urlbox1" class="form-group {{ $adv->url1 != '' ? "" : 'display-none' }}">
                        <label>Enter URL:</label>
                        <input value="{{ $adv->url1 }}" class="form-control" type="text" placeholder="http://" name="url1">
                      </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Choose Image 2: <span class="required">*</span> <small class="text-muted"><i class="fa fa-question-circle"></i>
                   Recommended image size (438 x 240px)</small></label>
                  <input id="image2" type="file" class="form-control" name="image2"/>
                  
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Image 2 Link By: <span class="required">*</span></label>
                  <select name="img2linkby" id="img2linkby" class="form-control">
                   <option {{ $adv->cat_id2 != '' ? "selected" : "" }} value="linkbycat">Link By Categories</option>
                    <option {{ $adv->pro_id2 != '' ? "selected" : "" }} value="linkbypro">Link By Product</option>
                    <option {{ $adv->url2 != '' ? "selected" : "" }} value="linkbyurl">Link By Custom URL</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div id="catbox2" class="form-group {{ $adv->cat_id2 != '' ? "" : 'display-none' }}">
                  <label>Select Category:</label>
                  <select name="cat_id2" id="" class="select2 form-control">
                            @foreach(App\Category::where('status','=','1')->get() as $cat)
                              <option {{ $adv->cat_id2 == $cat->id ? "selected" : "" }} value="{{ $cat->id }}">{{ $cat->title }}</option>
                            @endforeach
                      </select>
                </div>

                <div id="probox2" class="form-group {{ $adv->pro_id2 != '' ? "" : 'display-none' }}">
                  <label>Select Product:</label>
                  <select name="pro_id2" id="" class="select2 form-control">
                            @foreach($p = App\Product::where('status','=','1')->get() as $pro)
                              @if(count($pro->subvariants)>0)
                                <option {{ $adv->pro_id2 == $pro->id ? "selected" : "" }} value="{{ $pro->id }}">{{ $pro->name }}</option>
                              @endif
                            @endforeach
                      </select>
                </div>

                <div id="urlbox2" class="form-group {{ $adv->url2 != '' ? "" : 'display-none' }}">
                  <label>Enter URL:</label>
                  <input value="{{ $adv->url2 }}" class="form-control" type="text" placeholder="http://" name="url2">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  
                  <label>Choose Image 3: <span class="required">*</span> <small class="text-muted"><i class="fa fa-question-circle"></i>
                   Recommended image size (438 x 240px)</small></label>
                  <input id="image3" type="file" class="form-control" name="image3"/>
                  
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Image 3 Link By: <span class="required">*</span></label>
                  <select name="img3linkby" id="img3linkby" class="form-control">
                    <option {{ $adv->cat_id3 != '' ? "selected" : "" }} value="linkbycat">Link By Categories</option>
                    <option {{ $adv->pro_id3 != '' ? "selected" : "" }} value="linkbypro">Link By Product</option>
                    <option {{ $adv->url3 != '' ? "selected" : "" }} value="linkbyurl">Link By Custom URL</option>
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div id="catbox3" class="form-group {{ $adv->cat_id3 != '' ? "" : 'display-none' }}">
                  <label>Select Category:</label>
                  <select name="cat_id3" id="" class="select2 form-control">
                            @foreach(App\Category::where('status','=','1')->get() as $cat)
                              <option {{ $adv->cat_id3 == $cat->id ? "selected" : "" }} value="{{ $cat->id }}">{{ $cat->title }}</option>
                            @endforeach
                      </select>
                </div>

                <div id="probox3" class="form-group {{ $adv->pro_id3 != '' ? "" : 'display-none' }}">
                  <label>Select Product:</label>
                  <select name="pro_id3" id="" class="select2 form-control">
                            @foreach($p = App\Product::where('status','=','1')->get() as $pro)
                              @if(count($pro->subvariants)>0)
                                <option {{ $adv->pro_id3 == $pro->id ? "selected" : "" }} value="{{ $pro->id }}">{{ $pro->name }}</option>
                              @endif
                            @endforeach
                      </select>
                </div>

                <div id="urlbox3" class="form-group {{ $adv->url3 != '' ? "" : 'display-none' }}">
                  <label>Enter URL:</label>
                  <input value="{{ $adv->url3 }}" class="form-control" type="text" placeholder="http://" name="url3">
                </div>
              </div>
            </div>
              
              

            @elseif($adv->layout == 'Two non equal image layout')
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Choose Image 1: <span class="required">*</span></label>
                    <input id="image1" type="file" class="form-control" name="image1"/>
                    <small class="text-muted"><i class="fa fa-question-circle"></i>
                     Recommended image size (902 x 220px)</small>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Image 1 Link By: <span class="required">*</span></label>
                    <select name="img1linkby" id="img1linkby" class="form-control">
                      <option {{ $adv->cat_id1 != '' ? "selected" : "" }} value="linkbycat">Link By Categories</option>
                      <option {{ $adv->pro_id1 != '' ? "selected" : "" }} value="linkbypro">Link By Product</option>
                      <option {{ $adv->url1 != '' ? "selected" : "" }} value="linkbyurl">Link By Custom URL</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                      <div id="catbox1" class="form-group {{ $adv->cat_id1 != '' ? "" : 'display-none' }}">
                        <label>Select Category:</label>
                        <select name="cat_id1" id="" class="select2 form-control">
                                  @foreach(App\Category::where('status','=','1')->get() as $cat)
                                    <option {{ $adv->cat_id1 == $cat->id ? "selected" : "" }} value="{{ $cat->id }}">{{ $cat->title }}</option>
                                  @endforeach
                            </select>
                      </div>

                      <div id="probox1" class="form-group {{ $adv->pro_id1 != '' ? "" : 'display-none' }}">
                        <label>Select Product:</label>
                        <select name="pro_id1" id="" class="select2 form-control">
                                  @foreach($p = App\Product::where('status','=','1')->get() as $pro)
                                    @if(count($pro->subvariants)>0)
                                      <option {{ $adv->pro_id1 == $pro->id ? "selected" : "" }} value="{{ $pro->id }}">{{ $pro->name }}</option>
                                    @endif
                                  @endforeach
                            </select>
                      </div>

                      <div id="urlbox1" class="form-group {{ $adv->url1 != '' ? "" : 'display-none' }}">
                        <label>Enter URL:</label>
                        <input value="{{ $adv->url1 }}" class="form-control" type="text" placeholder="http://" name="url1">
                      </div>
                    </div>
              </div>
              
              <div class="row">

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Choose Image 2: <span class="required">*</span></label>
                    <input id="image2" type="file" class="form-control" name="image2"/>
                    <small class="text-muted"><i class="fa fa-question-circle"></i>
                     Recommended image size (438 x 240px)</small>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Image 2 Link By: <span class="required">*</span></label>
                    <select name="img2linkby" id="img2linkby" class="form-control">
                      <option {{ $adv->cat_id2 != '' ? "selected" : "" }} value="linkbycat">Link By Categories</option>
                      <option {{ $adv->pro_id2 != '' ? "selected" : "" }} value="linkbypro">Link By Product</option>
                      <option {{ $adv->url2 != '' ? "selected" : "" }} value="linkbyurl">Link By Custom URL</option>
                    </select>
                  </div>
                </div>

                <div class="col-md-4">
                  <div id="catbox2" class="form-group {{ $adv->cat_id2 != '' ? '' : 'display-none' }}">
                    <label>Select Category:</label>
                    <select name="cat_id2" id="" class="select2 form-control">
                              @foreach(App\Category::where('status','=','1')->get() as $cat)
                                <option {{ $adv->cat_id2 == $cat->id ? "selected" : "" }} value="{{ $cat->id }}">{{ $cat->title }}</option>
                              @endforeach
                        </select>
                  </div>

                  <div id="probox2" class="form-group {{ $adv->pro_id2 != '' ? "" : 'display-none' }}">
                    <label>Select Product:</label>
                    <select name="pro_id2" id="" class="select2 form-control">
                              @foreach($p = App\Product::where('status','=','1')->get() as $pro)
                                @if(count($pro->subvariants)>0)
                                  <option {{ $adv->pro_id2 == $pro->id ? "selected" : "" }} value="{{ $pro->id }}">{{ $pro->name }}</option>
                                @endif
                              @endforeach
                        </select>
                  </div>

                  <div id="urlbox2" class="form-group {{ $adv->url2 != '' ? "" : 'display-none' }}">
                    <label>Enter URL:</label>
                    <input value="{{ $adv->url2 }}" class="form-control" type="text" placeholder="http://" name="url2">
                  </div>
                </div>

              </div>
                  
            
            @elseif($adv->layout == 'Two equal image layout')
              <div class="row">

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Choose Image 1: <span class="required">*</span></label>
                    <input id="image1" type="file" class="form-control" name="image1"/>
                    <small class="text-muted"><i class="fa fa-question-circle"></i>
                     Recommended image size (902 x 220px)</small>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Image 1 Link By: <span class="required">*</span></label>
                    <select name="img1linkby" id="img1linkby" class="form-control">
                      <option {{ $adv->cat_id1 != '' ? "selected" : "" }} value="linkbycat">Link By Categories</option>
                      <option {{ $adv->pro_id1 != '' ? "selected" : "" }} value="linkbypro">Link By Product</option>
                      <option {{ $adv->url1 != '' ? "selected" : "" }} value="linkbyurl">Link By Custom URL</option>
                    </select>
                  </div>
                </div>

                <div class="col-md-4">
                      <div id="catbox1" class="form-group {{ $adv->cat_id1 != '' ? '' : 'display-none' }}">
                        <label>Select Category:</label>
                        <select name="cat_id1" id="" class="select2 form-control">
                                  @foreach(App\Category::where('status','=','1')->get() as $cat)
                                    <option {{ $adv->cat_id1 == $cat->id ? "selected" : "" }} value="{{ $cat->id }}">{{ $cat->title }}</option>
                                  @endforeach
                            </select>
                      </div>

                      <div id="probox1" class="form-group {{ $adv->pro_id1 != '' ? '' : 'display-none' }}">
                        <label>Select Product:</label>
                        <select name="pro_id1" id="" class="select2 form-control">
                                  @foreach($p = App\Product::where('status','=','1')->get() as $pro)
                                    @if(count($pro->subvariants)>0)
                                      <option {{ $adv->pro_id1 == $pro->id ? "selected" : "" }} value="{{ $pro->id }}">{{ $pro->name }}</option>
                                    @endif
                                  @endforeach
                            </select>
                      </div>

                      <div id="urlbox1" class="form-group {{ $adv->url1 != '' ? "" : 'display-none' }}">
                        <label>Enter URL:</label>
                        <input value="{{ $adv->url1 }}" class="form-control" type="text" placeholder="http://" name="url1">
                      </div>
                    </div>

              </div>
              

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Choose Image 2: <span class="required">*</span></label>
                    <input id="image2" type="file" class="form-control" name="image2"/>
                    <small class="text-muted"><i class="fa fa-question-circle"></i>
                     Recommended image size (902 x 220px)</small>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Image 2 Link By: <span class="required">*</span></label>
                    <select name="img2linkby" id="img2linkby" class="form-control">
                      <option {{ $adv->cat_id2 != '' ? "selected" : "" }} value="linkbycat">Link By Categories</option>
                      <option {{ $adv->pro_id2 != '' ? "selected" : "" }} value="linkbypro">Link By Product</option>
                      <option {{ $adv->url2 != '' ? "selected" : "" }} value="linkbyurl">Link By Custom URL</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div id="catbox2" class="form-group {{ $adv->cat_id2 != '' ? "" : 'display-none' }}">
                    <label>Select Category:</label>
                    <select name="cat_id2" id="" class="select2 form-control">
                              @foreach(App\Category::where('status','=','1')->get() as $cat)
                                <option {{ $adv->cat_id2 == $cat->id ? "selected" : "" }} value="{{ $cat->id }}">{{ $cat->title }}</option>
                              @endforeach
                        </select>
                  </div>

                  <div id="probox2" class="form-group {{ $adv->pro_id2 != '' ? '' : 'display-none' }}">
                    <label>Select Product:</label>
                    <select name="pro_id2" id="" class="select2 form-control">
                              @foreach($p = App\Product::where('status','=','1')->get() as $pro)
                                @if(count($pro->subvariants)>0)
                                  <option {{ $adv->pro_id2 == $pro->id ? "selected" : "" }} value="{{ $pro->id }}">{{ $pro->name }}</option>
                                @endif
                              @endforeach
                    </select>
                  </div>

                  <div id="urlbox2" class="form-group {{ $adv->url2 != '' ? '' : 'display-none' }}">
                    <label>Enter URL:</label>
                    <input value="{{ $adv->url2 }}" class="form-control" type="text" placeholder="http://" name="url2">
                  </div>
                </div>
              </div>

              
            @elseif($adv->layout == 'Single image layout')
              <div class="row">
                <div class="col-md-12">
                  <div class="row">

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Choose Image 1: <span class="required">*</span></label>
                        <input id="image1" type="file" class="form-control" name="image1"/>
                        <small class="text-muted"><i class="fa fa-question-circle"></i>
                         Recommended image size (1375 x 409px)</small>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Image 1 Link By: <span class="required">*</span></label>
                        <select name="img1linkby" id="img1linkby" class="form-control">
                          <option {{ $adv->cat_id1 != '' ? "selected" : "" }} value="linkbycat">Link By Categories</option>
                          <option {{ $adv->pro_id1 != '' ? "selected" : "" }} value="linkbypro">Link By Product</option>
                          <option {{ $adv->url1 != '' ? "selected" : "" }} value="linkbyurl">Link By Custom URL</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div id="catbox1" class="form-group {{ $adv->cat_id1 != '' ? "" : 'display-none' }}">
                        <label>Select Category:</label>
                        <select name="cat_id1" id="" class="select2 form-control">
                                  @foreach(App\Category::where('status','=','1')->get() as $cat)
                                    <option {{ $adv->cat_id1 == $cat->id ? "selected" : "" }} value="{{ $cat->id }}">{{ $cat->title }}</option>
                                  @endforeach
                            </select>
                      </div>

                      <div id="probox1" class="form-group {{ $adv->pro_id1 != '' ? "" : 'display-none' }}">
                        <label>Select Product:</label>
                        <select name="pro_id1" id="" class="select2 form-control">
                                  @foreach($p = App\Product::where('status','=','1')->get() as $pro)
                                    @if(count($pro->subvariants)>0)
                                      <option {{ $adv->pro_id1 == $pro->id ? "selected" : "" }} value="{{ $pro->id }}">{{ $pro->name }}</option>
                                    @endif
                                  @endforeach
                            </select>
                      </div>

                      <div id="urlbox1" class="form-group {{ $adv->url1 != '' ? "" : 'display-none' }}">
                        <label>Enter URL:</label>
                        <input value="{{ $adv->url1 }}" class="form-control" type="text" placeholder="http://" name="url1">
                      </div>
                    </div>
                  </div>
                  

                  
                </div>
              </div>
            @endif
          </div>
    
        </div>
        <div class="box-footer">
          <button @if(env('DEMO_LOCK') == 0) type="submit" @else disabled title="This operation is disabled in demo !" @endif class="btn btn-md btn-flat btn-primary">
            <i class="fa fa-save"></i> Update
          </button>
          <a title="Cancel and go back !" href="{{ route('adv.index') }}" class="btn btn-md btn-default">
            <i class="fa fa-reply"></i> Back
          </a>
        </div>
     </form>
    </div>
  </div>
@endsection
@section('custom-script')
 <script>var advindexurl = "<?=route('adv.index')?>"</script>
 <script src="{{ url('js/layoutadvertise.js') }}"></script>
@endsection