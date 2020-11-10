<script>
     function addRow(){
  
         var rowCount = $('.xyz tr').length+1;
         var chck = '';
         var rate = '';
        if(rowCount == 1){
          chck = 'checked';
          rate = 1;
        }
         var n = ''
            var markup = "<tr id='"+rowCount+"'><td class=\"sl\">"+rowCount+"</td><td class='fake-input'><select required id=\"cs"+rowCount+"\" name=\"cs"+rowCount+"\" class=\"js-example-responsive\" avl=\"yes\" rowCount=\""+rowCount+"\"><option value=\"\">Select Currency Code</option><?php $currencys = DB::table('currency_list')->get(); ?> @foreach($currencys as $cur)<option value=\"{{($cur->id)}}\">{{trans($cur->code)}}{{'-'}}{{trans($cur->country)}}</option>@endforeach</select></td><td class='fake-input'><select required name=\"Position"+rowCount+"\" id=\"Position"+rowCount+"\" class=\"js-example-responsive\"><option value=\"l\">Left without space $99</option><option value=\"r\">Right without space 99$</option><option value=\"ls\">Left With Space $ 99<option value=\"rs\">Right With Space 99 $</select></td><td class='fake-input'><input required class='exchange-rate-amount form-control' type=\"text\" name=\"rate\" id=\"rate"+rowCount+"\" value=\""+rate+"\" disabled><img class='rate-img' src='{{ url("images/load.gif") }}' width=20 /></td><td class='fake-input'><input required class='exchange-amount form-control' placeholder='0.00' step='0.1' type=\"number\" name=\"add_amount\" id=\"add_amount"+rowCount+"\" value=\"\"></td><td class='fake-input'><input required type=\"text\" class=\"form-control showcur icp icp-auto action-create input-amount\" name=\"currency_symbol\" id=\"currency_symbol"+rowCount+"\" value=\"\"></td><td class='fake-input'><a class=\"btn btn-sm btn-info\" onclick=\"addNewCurrency('"+rowCount+"')\"> <i class=\"fa fa-save\"></i></a></td></tr>";
            $(".xyz").append(markup);
           
            testing();

            $('.icp-auto').iconpicker({

             icons: ['fa fa-inr', 'fa fa-eur' , 'fa fa-bitcoin', 'fa fa-btc', 'fa fa-cny','fa fa-dollar', 'fa fa-gg-circle','fa fa-gg','fa fa-rub','fa fa-ils','fa fa-try','fa fa-krw','fa fa-gbp','fa fa-zar','fa fa-rs','fa fa-pula','fa fa-aud','fa fa-egy','fa fa-taka','fa fa-mal','fa fa-rub','fa fa-brl','fa fa-idr','fa fa-zwl','fa fa-ngr','fa fa-eutho','fa fa-sgd'],
             selectedCustomClass: 'label label-success',
             mustAccept: true,
             placement: 'right',
             hideOnSelect: true,
           });
                  
      
  }
</script>