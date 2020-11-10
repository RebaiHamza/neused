 $(function () {

      "use strict";
      
      var table = $('#state_table').DataTable({
          processing: true,
          serverSide: true,
          responsive : true,
          ajax: url,
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'statename', name: 'statename'},
              {data : 'cname', name: 'cname'}         
          ],
          dom : 'lBfrtip',
          buttons : [
            'csv','excel','pdf','print','colvis'
          ],
          order : [[0,'asc']]
      });
      
});