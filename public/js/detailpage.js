"use strict";
// Define your library strictly...
$(function() {
  $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
    localStorage.setItem('activeTab', $(e.target).attr('href'));
  });
  var activeTab = localStorage.getItem('activeTab');
  if(activeTab) {
    $('#myTabs a[href="' + activeTab + '"]').tab('show');
  }
  $(".single-product-gallery-item").mouseover(function() {
    $('#details-container').css('z-index', '9999');
  });
  $(".single-product-gallery-item").mouseout(function() {
    $('#details-container').css('z-index', '0');
  });
});
$(document).on('click', '.removeFrmWish', function() {
  var wc = $('#wishcount').text();
  wc = Number(wc);
  if(wc == 1) {
    wc = 0;
  } else {
    wc = Number(wc) - 1;
  }
  $('#wishcount').text(wc);
  $(this).parent().removeClass('active');
  $('body').append('<div class="preL"><div class="loaderT"></div></div>');
  var id = $(this).attr('mainid');
  var url = baseUrl + '/AddToWishList/' + id;
  var ajaxremoveurl = $(this).attr('data-remove');
  $(this).parent().html('<a mainid="' + id + '" data-add="' + url + '" class="btn btn-primary addtowish" data-toggle="tooltip" data-placement="right" title="Add to Wishlist" href="#"><i class="fa fa-heart"></i></a>');
  setTimeout(function() {
    $.ajax({
      type: 'GET',
      url: ajaxremoveurl,
      success: function(response) {
        if(response == 'deleted') {
          swal({
            title: "Removed ",
            text: 'Product is removed from wishlist !',
            icon: 'error'
          });
        } else {
          swal({
            title: "Error",
            text: 'Please try again later !',
            icon: 'error'
          });
        }
      }
    });
    $('.preL').fadeOut('fast');
    $('.loaderT').fadeOut('fast');
    $('body').attr('scroll', 'yes');
    $('body').css('overflow', 'auto');
  }, 1500);
});
$(document).on('click', '.addtowish', function() {
  var wc = $('#wishcount').text();
  wc = Number(wc);
  if(wc == 0) {
    wc = 1;
  } else {
    wc = Number(wc) + 1;
  }
  $('#wishcount').text(wc);
  $('body').append('<div class="preL"><div class="loaderT"></div></div>');
  var id = $(this).attr('mainid');
  var url = baseUrl + '/removeWishList/' + id;
  var ajaxaddurl = $(this).attr('data-add');
  $(this).parent().html('<a mainid="' + id + '" data-remove="' + url + '" class="abg bg-primary btn btn-primary removeFrmWish" data-toggle="tooltip" data-placement="right" title="Remove From Wishlist" href="#"><i class="fa fa-heart"></i></a>');
  setTimeout(function() {
    $.ajax({
      type: 'GET',
      url: ajaxaddurl,
      success: function(response) {
        if(response == 'success') {
          swal({
            title: "Added",
            text: 'Added to your wishlist !',
            icon: 'success'
          });
        } else {
          swal({
            title: "Oops !",
            text: 'Product is already in your wishlist !',
            icon: 'warning'
          });
        }
      }
    });
    $('.preL').fadeOut('fast');
    $('.loaderT').fadeOut('fast');
    $('body').attr('scroll', 'yes');
    $('body').css('overflow', 'auto');
  }, 1500);
});
$(function() {
  $(document).on('click', '.btn-more', function() {
    var id = $(this).data('id');
    var proid = $(this).data('proid');
    $(".btn-more").html("Loading....");
    $.ajax({
      url: baseUrl + '/load/more/product/comment',
      method: "GET",
      data: {
        proid: proid,
        id: id
      },
      dataType: "text",
      success: function(data) {
        if(data != '') {
          $('.remove-row').remove();
          $('.appendComment').append(data);
        } else {
          $('.btn-more').html("No More Comments...");
        }
      }
    });
  });
});
$(window).scroll(function() {
  var y = $(window).scrollTop();
  var d = $(document).height();
  var z = $(window).height();
  var w = $(window).width();
  if(w > 1024) {
    if(y > 440 && y < 800) {
      $('.stickCartNav').fadeIn();
      $('.stickCartNav').removeClass('fixed-top');
      $('.stickCartNav').addClass('fixed-bottom');
    } else if(y > 820) {
      $('.stickCartNav').fadeIn();
      $('.stickCartNav').removeClass('fixed-bottom');
      $('.stickCartNav').addClass('fixed-top');
    } else {
      $('.stickCartNav').fadeOut();
    }
  } else {
    if(y > 5) {
      $('.stickCartNav').fadeIn();
      $('.stickCartNav').removeClass('fixed-top');
      $('.stickCartNav').addClass('fixed-bottom');
    } else {
      $('.stickCartNav').fadeOut();
    }
  }
});

function test(id) {
  // Define your library strictly...
  var x = $('#show' + id).html();
  $('#show' + id).show();
  if($("#contact_us" + id).length > 0) {
    $("#contact_us" + id).validate({
      rules: {
        comment: {
          required: true,
          minlength: 5,
          maxlength: 100,
        },
      },
      messages: {
        comment: {
          required: "Please enter Comment ",
          minlength: "The Text Should Be 5 Characters",
          maxlength: "The Text Should Be Less Then 100 Characters",
        },
      },
      submitHandler: function(form) {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $('#send_form' + id).html('Sending..');
        var urlLike = baseUrl + '/save-form';
        $.ajax({
          url: urlLike,
          type: "POST",
          data: $('#contact_us' + id).serialize(),
          success: function(response) {
            $('#message' + id).append('<br><br><small class="margin-left-15">' + response.msg + '</small>');
            $('#send_form').html('Submit');
            $('#res_message').show();
            $('#res_message').html(response.success);
            $('#msg_div').removeClass('d-none');
            document.getElementById("contact_us" + id).reset();
            setTimeout(function() {
              $('#res_message').hide();
              $('#msg_div').hide();
            }, 10000);
          }
        });
      }
    })
  }
}

function changeImage(url) {
  $('.img').attr('src', url);
  $('.img').attr('data-zoom', url);
  var h = $('.img').height();
  var w = $('.img').width();
}
if($("#comment_us").length > 0) {
  // Define your library strictly...
  $("#comment_us").validate({
    rules: {
      comment: {
        required: true,
        minlength: 5,
        maxlength: 2000,
      },
    },
    messages: {
      comment: {
        required: "Please enter Comment ",
        minlength: "The Text Should Be 5 Characters",
        maxlength: "The Text Should Be Less Then 100 Characters",
      },
    },
    submitHandler: function(form) {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $('#submit_form').html('Sending..');
      var urlLike = baseUrl + '/comment';
      $.ajax({
        url: urlLike,
        type: "GET",
        data: $('#comment_us').serialize(),
        dataType: 'html',
        success: function(data) {
          var animateIn = "lightSpeedIn";
          var animateOut = "lightSpeedOut";
          swal({
            title: "Added",
            text: 'Comment added successfully !',
            icon: 'success'
          });
          var cmcount = $('#countComment').text();
          cmcount = Number(cmcount) + 1;
          $('#countComment').html(cmcount);
          //append a view
          $(data).prependTo('.appendComment').show().slideDown();
          document.getElementById('comment_us').reset();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          console.log(XMLHttpRequest);
        }
      });
    }
  });
}
$("input#deliveryPinCode").on('keyup', function() {
  var p = $('#deliveryPinCode').val();
  $('#deliveryPinCode').attr('value', p);
});

function SubmitFormData() {

  

  var name = $('#deliveryPinCode').val();
  var urlLike = baseUrl + '/pincode-check';
  $.ajax({
    type: 'GET',
    url: urlLike,
    data: {
      name: name
    },
    success: function(data) {
      //$('#myForm')[0].reset();
      if(name == "") {
        $("#pincodeResponce").html("<span class='required'>Please Enter Pincode</span>");
      }
      if(data == "Delivery is Available") {
        $("#pincodeResponce").html("<span class='pincoderesponsesuccess'>" + data + "</span>");
        $('#marker-map').css('color', '#108BEA');
        $('.quantity-block').show();
        $('.quant-input').show();
        $('#cartForm').show();
      } else {
        $("#pincodeResponce").html("<span class='pincodeinvalid'>" + data + "</span>");
        $('#marker-map').css('color', 'red');
        $('.quantity-block').hide();
        $('.quant-input').hide();
        $('#cartForm').hide();
      }
    }
  });
}
var globalPin;
// equipment search function

$(document).on("keydown", "#deliveryPinCode", function(event) { 
    return event.key != "Enter";
});

$(function() {
  var urlLike = baseUrl + '/pincode-check';

  $("#deliveryPinCode").autocomplete({



    source: baseUrl + '/pincodeforaddress',
    minLength: 1,
    select: function(event, ui) {
      if(ui.item.value == "Invalid Pincode") {
        this.value = "";
        return false;
      } else {
        var x = $('#deliveryPinCode').val();
        if(ui.item.pincode != undefined) {
          var name = ui.item.pincode;
          this.value = name;
        } else {
          this.value = x;
        }
        $.ajax({
          type: 'GET',
          url: urlLike,
          data: {
            name: name
          },
          success: function(data) {
            if(name == "") {
              $("#pincodeResponce").html("<span class='pincodeinvalid'>Please Enter Pincode</span>");
              return false;
            }
            if(data == "Delivery is Available") {
              $("#pincodeResponce").html("<span class='pincoderesponsesuccess'>" + data + "</span>");
              $('#marker-map').css('color', '#108BEA');
              $('.quantity-block').show();
              $('.quant-input').show();
              $('#cartForm').show();
            } else if(ui.item.pincode == undefined) {
              $("#pincodeResponce").html("<span class='pincodeinvalid'>Delivery Not Available</span>");
              $('#marker-map').css('color', 'red');
            } else {
              $("#pincodeResponce").html("<span class='pincodeinvalid'>" + data + "</span>");
              $('#marker-map').css('color', 'red');
              $('.quantity-block').hide();
              $('.quant-input').hide();
              $('#cartForm').hide();
            }
          }
        });
      }
      return false;
    }
  });
});