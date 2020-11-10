"use strict";
// Define your library strictly...
$(function () {
  $('.menu-ham').on('click', function () {
    $('.menu-new').animate({
      left: '0px'
    }, 100)
  });
  $('.close-menu-new').on('click', function () {
    $('.menu-new').animate({
      left: '-260px'
    }, 100);
    $('.closeNav').animate({
      left: '-260px'
    }, 100)
  });
  $('[data-toggle="popover"]').popover();



  $('.search-field').on('change', function () {
    var x = $('.search-field').val();
    keyword(x);
  });
  $('.search-field').on('keyup', function () {
    var x = $('.search-field').val();
    keyword(x);
  });
  $(".toggle-password").on('click', function () {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });
  $('#password, #confirm_password').on('keyup', function () {
    if ($('#password').val() == $('#confirm_password').val()) {
      $('#message').html('Password Matched').css('color', 'green').show();
    } else $('#message').html('Password Not Matching').css('color', 'red').show();
  });
  $('#btn_reset').on('click', function () {
    document.getElementById("form1").reset();
    $('#message').hide();
  });
  $('.changed_language').on('change', function () {
    var lang = $(this).val();
    $.ajax({
      url: baseUrl + '/changelang',
      type: 'GET',
      data: {
        lang: lang
      },
      success: function (data) {
        location.reload();
      }
    });
  });
});
$(window).on('load', function () {
  $('.front-preloader').fadeOut('slow');
});
var sticky = new Sticky('[data-sticky]');
sticky.destroy('[data-sticky]');

function val() {
  d = document.getElementById("currency").value;
  $.ajax({
    method: 'GET',
    url: baseUrl + '/currency/' + d,
    data: "currency=" + d,
    success: function (data) {
      window.location.reload();
    }
  });
}

function val2() {
  d = document.getElementById("currencySmall").value;
  $.ajax({
    method: 'GET',
    url: baseUrl + '/currency/' + d,
    data: "currency=" + d,
    success: function (data) {
      window.location.reload();
    }
  });
}

function keyword(x) {
  var test = x;
  // Check browser support
  if (typeof (Storage) !== "undefined") {
    // Store
    sessionStorage.setItem("searchItem", test);
  } else {
    document.getElementById("result").innerHTML = "Sorry, your browser does not support Web Storage...";
  }
}

function catPage(url) {
  $("#dropdown ul").hide();
  window.location.href = url;
}

(function ($) {
  $('#starRating').starRating({
    callback: function (value) {
      $('.getStar').val(+value);
    }
  })
}(jQuery));

$(function () {
  var interval = setInterval(function () {
    var momentNow = moment();
    var crDate = momentNow.format('YYYY-MM-DD') + ' ' + momentNow.format('HH:mm:ss');
    $('.timing-wrapper').each(function (index) {
      var startDate = $(this).data('startat');
      var endDate = $(this).data('countdown');
      if (endDate <= crDate) {
        var indexToRemove = index;
        var owlCarousel = jQuery(".owl-carousel").data('owlCarousel');
        owlCarousel.removeItem(index).fadeOut('slow');
      }
    });
  }, 100);
});
// Hot Deal Countdown 
var d = new Date();
var datestring = d.getFullYear() + "-" + ("0" + (d.getMonth() + 1)).slice(-2) + "-" + ("0" + d.getDate()).slice(-2) + " " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2) + ":" + ("0" + d.getSeconds()).slice(-2);
if ($('.timing-wrapper').length) {
  $('.timing-wrapper').each(function () {
    var $this = $(this),
      finalDate = $(this).data('countdown');
    var $this1 = $(this),
      finalDate1 = $(this).data('startat');
    if (datestring >= finalDate1) {
      $this.countdown(finalDate, function (event) {
        var $this = $(this).html(event.strftime('' + '<div class="box-wrapper"><div class="date box"> <span class="key">%D</span> <span class="value">DAYS</span> </div> </div> ' + '<div class="box-wrapper"><div class="hour box"> <span class="key">%H</span> <span class="value">HRS</span> </div> </div> ' + '<div class="box-wrapper"><div class="minutes box"> <span class="key">%M</span> <span class="value">MINS</span> </div> </div> ' + '<div class="box-wrapper"><div class="seconds box"> <span class="key">%S</span> <span class="value">SEC</span> </div> </div> '));
      });
    }
  });
}

function gotourl(url) {
  window.location.href = url;
}
var hidWidth;
var scrollBarWidths = 40;
var widthOfList = function () {
  var itemsWidth = 0;
  $('.list li').each(function () {
    var itemWidth = $(this).outerWidth();
    itemsWidth += itemWidth;
  });
  return itemsWidth;
};
var widthOfHidden = function () {
  return (($('.wrapper').outerWidth()) - widthOfList() - getLeftPosi()) - scrollBarWidths;
};
var reAdjust = function () {
  if (($('.wrapper').outerWidth()) < widthOfList()) {
    $('.scroller-right').show();
  } else {
    $('.scroller-right').hide();
  }
}
reAdjust();
$(window).on('resize', function (e) {
  reAdjust();
});
$('.scroller-right').on('click', function () {
  $('.scroller-left').fadeIn('slow');
  $('.scroller-right').fadeOut('slow');
  $('.list').animate({
    left: "+=" + widthOfHidden() + "px"
  }, 'slow', function () {});
});
$('.scroller-left').on('click', function () {
  $('.scroller-right').fadeIn('slow');
  $('.scroller-left').fadeOut('slow');
  $('.list').animate({
    left: "-=" + getLeftPosi() + "px"
  }, 'slow', function () {});
});

setTimeout(function () {
  $('.hideAlert').slideUp();
}, 2500);

var acc = document.getElementsByClassName("accordion");
var i;
for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function () {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    }
  });
}

$('.dropdown-cart-one').on('click', function () {
  $('.cart-checkout').removeAttr('style');
});

function logout() {
  $(".logout-form").submit();
  event.preventDefault();
}