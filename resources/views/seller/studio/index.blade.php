@extends("admin.layouts.sellermaster")
@section('title',$auth->store['name'].' Neused Studio | ')
@section("body")

<div class="box">

  <div class="box-header with-border">
    <div class="box-title"><span class="lnr lnr-laptop"></span> Neused Studio | All Packs</div>
  </div>
  <div class="box-body">
    <div class="row dbrow">

      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-purple">
          <div class="inner">
            <center><h3 style="color: aliceblue">Pack GOLD</h3></center>

            <center>
              <p>
                this pack contains a lot of marketing tools including
                new deals, pictures for your products and making your life easier to have it box-header
                well not only this but also you have to obtain some relatives
              </p>
            </center>

            <center>
              <h4 style="color: aliceblue">
                Price: 500 QAR
              </h4>
            </center>  
          </div>
          <div class="icon">
            <i class="fa fa-bullhorn" aria-hidden="true"></i>
          </div>
          <a href="{{ url('seller/') }}" class="small-box-footer">
            Request Pack <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>

@endsection
@section('custom-script')
<script src="{{ url('front/vendor/js/highcharts.js') }}" charset="utf-8"></script>
@endsection