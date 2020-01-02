<div class="table-otr">
   @if(count($bidHistory))
   <h3> {{ trans('label.last_10_bids') }}</h3>
   <table class="table table-responsive table-condensed">
      @foreach($bidHistory as $bid)
      <tr>
         <td style="width:100px;"><a class=""  onClick="javascript:getUserActivity('{{ encrypt_decrypt('encrypt',$bid->user->id)}}')" > {{ $bid->user->username }} </a></td>
         <td style="width:100px;">{{ price_reflect_format($bid->bid_price) }} {{ CURRENCY_ICON }}</td>
         @php
         $handImage = asset('images/auto-agent_02.png');
         $text = trans('label.automate');
         if($bid->is_manual){
         $handImage = asset('images/manuel-bid_02.png');
         $text = trans('label.manual');
         }
         @endphp
         <td style="width:100px;" class="green"> <img width="25px" src="{{ $handImage }}" alt="{{ $text }}" title="{{ $text }}"></td>
      </tr>
      @endforeach
   </table>
   @else
   <h3> {{ trans('label.no_bidding_history') }} </h3>
   @endif  
</div>