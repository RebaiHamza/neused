$(function () {

      "use strict";

      var table = $('#country_table').DataTable({
          processing: true,
          serverSide: true,
          responsive : true,
          ajax: url,
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'nicename', name: 'nicename'},
              {data : 'iso', name: 'iso'},
              {data : 'iso3', name: 'iso3'},
              {data : 'action', name: 'action'}    
          ],
          dom : 'lBfrtip',
          buttons : [
            'csv','excel','pdf','print','colvis'
          ],
          order : [[0,'asc']]
      });
      
});