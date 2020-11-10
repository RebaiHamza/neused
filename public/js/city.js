$(function () {
  "use strict";
  var table = $('#citytable').DataTable({
      processing: true,
      serverSide: true,
      ajax: url,
      columns: [
          {data: 'DT_RowIndex', name: 'DT_RowIndex'},
          {data: 'c', name: 'c'},
          {data : 'statename', name: 'statename'},
          {data : 'cname', name: 'cname'}    
      ],
      dom : 'lBfrtip',
      buttons : [
        'csv','excel','pdf','print','colvis'
      ],
      order : [[0,'asc']]
  });
  
});
