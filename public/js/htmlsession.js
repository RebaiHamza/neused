"use strict";
// Define your library strictly...
var catids = sessionStorage.getItem("searchcat");
$(function() {
  
        
  if (window.location.href.indexOf('&keyword=') > 0) {
     // No code
  }else{
    sessionStorage.clear();
  }
      
  var cachesearchedValue;
  if(typeof(Storage) !== "undefined") {
    cachesearchedValue = sessionStorage.getItem("searchItem");
  }
  $('.search-field').val(cachesearchedValue);
  setinhtmlsession(catids);
  $("#searchDropMenu option").each(function() {
    if($(this).val() == catids) { // EDITED THIS LINE
      $(this).attr("selected", "selected");
    } else {
      $(this).removeAttr("selected");
    }
  });
});
$('#searchDropMenu').on('change', function() {
  catids = $('#searchDropMenu').val();
  setinhtmlsession(catids);
});

function setinhtmlsession(catids) {
  if(!catids) {
    var catids1 = $('#searchDropMenu').val();
  } else {
    var catids1 = catids;
  }
  sessionStorage.setItem("searchcat", catids1);
}
