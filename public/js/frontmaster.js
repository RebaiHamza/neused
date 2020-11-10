

"use strict";


$(function () {
  $('.toggle-menu').click(function () {
    $('.exo-menu').fadeIn().toggleClass('display');
  });
});
$(function () {
  $(".mega-menu").hover(function () {
    $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).slideDown("400");
    $(this).toggleClass('open');
  }, function () {
    $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).slideUp("400");
    $(this).toggleClass('open');
  });
  $("form:not(#rpayform,#comment_us,#loginform,.register-form)").on('submit', function () {
    if ($(this).valid()) {
      $('.preL').fadeIn('fast');
      $('.preloader3').fadeIn('fast');
      $('.container').css({
        '-webkit-filter': 'blur(5px)'
      });
      $('body').attr('scroll', 'no');
      $('body').css('overflow', 'hidden');
    }
  });
});
$('.categoryrec').on('click', function () {
  var id = $(this).data('id');
  $('#childOpen' + id).toggle('fast');
});
$('.childrec').on('click', function () {
  var id = $(this).data('id');
  $('#childcollapseExample' + id).toggle('fast');
});

if (httpson == 1) {
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
      navigator.serviceWorker.register(sw).then(function (reg) {
        console.log('Service worker registered.', reg);
      });
    });
  }
}

if (rightclick == '1') {
  $(function () {
    $(document).bind("contextmenu", function (e) {
      return false;
    });
  });
}

if (inspect == '1') {
  document.onkeydown = function (e) {
    if (event.keyCode == 123) {
      return false;
    }

    if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
      return false;
    }

    if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
      return false;
    }

    if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
      return false;
    }

    if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
      return false;
    }
  };
}
