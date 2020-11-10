<p> <i class="fa fa-calendar-plus-o" aria-hidden="true"></i> 
  <span class="font-weight500" >{{ date('M jS Y',strtotime($createdat)) }},</span></p>
<p ><i class="fa fa-clock-o" aria-hidden="true"></i> <span class="font-weight500" >{{ date('h:i A',strtotime($createdat)) }}</span></p>

<p class="border-grey"></p>

<p>
   <i class="fa fa-calendar-check-o" aria-hidden="true"></i> 
   <span class="font-weight500">{{ date('M jS Y',strtotime($updateat)) }}</span>
</p>

<p><i class="fa fa-clock-o" aria-hidden="true"></i> <span class="font-weight500">{{ date('h:i A',strtotime($updateat)) }}</span></p>