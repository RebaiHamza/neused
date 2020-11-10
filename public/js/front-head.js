"use strict";
// Define your library strictly...
function preloadFunc() {
  $('body').attr('scroll', 'no');
  $('body').css('overflow', 'hidden');
}
window.onpaint = preloadFunc();

function markread(id) {
  var a = $('#countNoti').text();
  $.ajax({
    url: baseUrl + '/usermarkreadsingle',
    type: "GET",
    data: {
      id1: id
    },
    success: function(data) {
      if(a > 0) {
        var b = a - 1;
        if(b > 0) {
          $('#countNoti').text(b);
          $('#' + id).css('background', 'white');
        } else {
          $('#countNoti').hide('fast');
        }
      }
    }
  });
}
