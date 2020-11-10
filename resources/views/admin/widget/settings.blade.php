@extends('admin.layouts.master')
@section('title','Widget Settings | ')
@section('body')
<div class="box">
    <div class="box-header with-border">
        <div class="box-title">
            Widget Settings
        </div>
    </div>

    <div class="box-body">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" role="tablist" id="myTab">
                <li role="presentation" class="active"><a href="#sidebarwid" role="tab" data-toggle="tab">Sidebar
                        Widgets</a></li>
                <li><a href="#frontwid" role="tab" data-toggle="tab">Main Page Widgets</a></li>
            </ul>
            <div class="tab-content">

                <div role="tabpanel" class="tab-pane fade in active" id="sidebarwid">
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <th>
                                Widget Example:

                            </th>
                            <th>
                                Widget Name
                            </th>
                            <th>
                                Widget Place
                            </th>
                        </thead>

                        <tbody>

                            @foreach($widgets as $widget)
                            <tr>
                                <td>
                                    @if($widget->name == 'category')
                                    <img height="300px" class="img-fluid"
                                        src="{{ url('images/widgetpreview/category.png') }}" alt="{{ $widget->name }}"
                                        title="{{ ucfirst($widget->name) }}">
                                    @elseif($widget->name == 'hotdeals')
                                    <img height="300px" class="img-fluid"
                                        src="{{ url('images/widgetpreview/hotdeal.png') }}" alt="{{ $widget->name }}"
                                        title="{{ ucfirst($widget->name) }}">
                                    @elseif($widget->name == 'specialoffer')
                                    <img height="200px" class="img-fluid"
                                        src="{{ url('images/widgetpreview/spoffer.png') }}" alt="{{ $widget->name }}"
                                        title="{{ ucfirst($widget->name) }}">
                                    @elseif($widget->name == 'testimonial')
                                    <img height="200px" class="img-fluid"
                                        src="{{ url('images/widgetpreview/testimonial.png') }}"
                                        alt="{{ $widget->name }}" title="{{ ucfirst($widget->name) }}">
                                    @elseif($widget->name == 'newsletter')
                                    <img height="150px" class="img-fluid"
                                        src="{{ url('images/widgetpreview/newsletter.png') }}" alt="{{ $widget->name }}"
                                        title="{{ ucfirst($widget->name) }}">
                                    @elseif($widget->name == 'slider')
                                    <img height="150px" class="img-fluid"
                                        src="{{ url('images/widgetpreview/slider.png') }}" alt="{{ $widget->name }}"
                                        title="{{ ucfirst($widget->name) }}">
                                    @endif
                                </td>
                                <td>{{ ucfirst($widget->name) }}</td>
                                <td>

                                    <div class="row">

                                        <div class="col-md-6">
                                            @if($widget->name == 'testimonial' || $widget->name == 'specialoffer' ||
                                            $widget->name == 'slider' || $widget->name == 'category' || $widget->name ==
                                            'hotdeals' || $widget->name == 'newsletter')
                                            <p><b>Show On Home Page:</b></p>
                                            @endif

                                            <form action="{{ route('widget.home.quick.update',$widget->id) }}"
                                                method="POST">
                                                {{csrf_field()}}
                                                <button type="submit"
                                                    class="btn btn-xs {{ $widget->home==1 ? "btn-success" : "btn-warning" }}">
                                                    {{ $widget->home ==1 ? 'Yes' : 'No' }}
                                                </button>
                                            </form>
                                        </div>

                                        <div class="col-md-6">

                                            @if($widget->name == 'hotdeals' || $widget->name == 'newsletter')
                                            <p><b>Show On Product Detail Page:</b></p>
                                            @endif

                                            @if($widget->name == 'newsletter' || $widget->name == 'hotdeals')
                                            <form action="{{ route('widget.shop.quick.update',$widget->id) }}"
                                                method="POST">
                                                {{csrf_field()}}
                                                <button @if(env("DEMO_LOCK")==0) type="submit" @else
                                                    title="This action is disabled in demo !" disabled="disabled" @endif
                                                    class="btn btn-xs {{ $widget->shop==1 ? "btn-success" : "btn-warning" }}">
                                                    {{ $widget->shop ==1 ? 'Yes' : 'No' }}
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>




                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>

                <div role="tabpanel" class="tab-pane fade" id="frontwid">
                    <h4>Widget Example:</h4>
                    <img class="img-responsive" src="{{ url('images/widgetpreview/newpro.png') }}" alt="">
                    <form id="demo-form2" method="post" enctype="multipart/form-data"
                        action="{{url('admin/NewProCat')}}" data-parsley-validate
                        class="form-horizontal form-label-left">
                        {{csrf_field()}}

                        <div class="form-group">
                            <label class="col-md-2">Select Categories to show:</label>
                            <ul class="col-md-8">
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default">
                                        <div class="row">
                                            @foreach(App\Category::where('status','1')->get(); as $item)
                                            @php
                                                if(!empty($NewProCat)){
                                                    $parents = explode(",",$NewProCat->name);
                                                }   
                                            @endphp
                                            
                                            <div class="col-md-6">
                                                <div class="panel-heading" role="tab" id="headingOne">
                                                    <h4 class="panel-title">
                                                        <a role="button" data-parent="#accordion" aria-expanded="true"
                                                            aria-controls="collapseOne">
                                                            <input id="categories_{{$item->id}}" @if(!empty($parents))
                                                                @foreach($parents as $p)
                                                                {{ $p == $item->id ? "checked" : "" }} @endforeach @endif
                                                                type="checkbox" class=" required_one categories"
                                                                name="name[]" value="{{$item->id}}">
    
                                                            {{$item->title}}
                                                        </a>
                                                    </h4>
                                                </div>
                                            </div>

                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </ul>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                                Status:
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="toggle-event3" @if(!empty($NewProCat))
                                    {{ $NewProCat->status ==1 ? "checked" : "" }} @endif type="checkbox"
                                    class="tgl tgl-skewed">
                                <label class="tgl-btn" data-tg-off="Deactive" data-tg-on="Active"
                                    for="toggle-event3"></label>
                                <input type="hidden" name="status"
                                    value="@if(!empty($NewProCat)) {{ $NewProCat->status }} @endif" id="status3">
                                <small class="txt-desc">(Please Choose Status )</small>
                            </div>
                        </div>
                        <div>

                            <div class="box-footer">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection