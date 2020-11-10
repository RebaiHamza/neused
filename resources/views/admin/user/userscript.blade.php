<script>
    $(function () {

      'use strict';
      
      var table = $('#userTable').DataTable({
          processing: true,
          serverSide: true,
          responsive : true,
          @if(Nav::isRoute('show.allvenders'))
          ajax : {url: "{{ route('show.allvenders') }}"},
          @elseif(Nav::isRoute('onlyshowuser'))
          ajax : {url: "{{ route('onlyshowuser') }}"},
          @endif
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'image', name: 'image'},
              {data : 'detail', name: 'detail'},
              {data : 'timestamp', name : 'timestamp', searchable : false},
              {data : 'status', name: 'status'},
              {data : 'action', data : 'action'}       
          ],
          dom : 'lBfrtip',
          buttons : [
            'csv','excel','pdf','print','colvis'
          ],
          order : [[0,'desc']]
      });
      
    });
  </script>
  <script>
    $('.filter').on('change',function(){
      if($(this).val() == 'seller'){
         location.href = "{{ route('show.allvenders') }}";
      }else if($(this).val() == 'all'){
         location.href = "{{route('users.index')}}";
      }else if($(this).val() == 'customer'){
         location.href = "{{route('show.allcustomer')}}";
      }else if($(this).val() == 'admins'){
         location.href = "{{route('show.alladmins')}}";
      }
    });
  </script>