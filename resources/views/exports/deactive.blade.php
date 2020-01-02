<table>
    <thead>
     <tr>
      <th>{{ trans('label.id') }} </th>
      <th>{{ trans('label.product_id') }} </th>
      <th>{{ trans('label.product_title') }} </th>
      <th>{{ trans('label.end_time') }} </th>
      <th>{{ trans('label.bid_count') }} </th>
      <th>{{ trans('label.bid_price') }} </th>
      <th>{{ trans('label.regular_price') }} </th>
      <th>{{ trans('label.winner') }} </th>
      <th>{{ trans('label.duration') }} </th>
      <th>{{ trans('label.auction_type') }} </th>
      <th>{{ trans('label.final_countdown') }} </th>
      <th>{{ trans('label.discount') }} </th>
      
   </tr>
    </thead>
    <tbody>
      @php
      @endphp
      <pre>
    @foreach($deactive as $deactive_auction)
        <tr>

      @php
      $product = $deactive_auction->product;
      foreach($deactive_auction->auctionWinner as $auctionWinner){
          if($auctionWinner->user_id != 0)
          {

            if($auctionWinner->user)
            {
              $user = $auctionWinner->user;
             $user = $user->username;

            }
            else
            {
              $user = '';
            }
          }
          else
          {
            $user = '';
          }
      }
      @endphp

      <td>{{ $deactive_auction->auction_id }}</td>
      <td>{{ $deactive_auction->product_id }}</td>
      <td> {{ $product->product_title }}</td>
      <td> {{ $deactive_auction->end_time }}</td>
      <td>{{ $deactive_auction->bid_count }}</td>
      <td>{{ $deactive_auction->bid_price }}</td>
      <td>{{ $product->product_price }}</td>
      <td>{{ $user ? $user : "N/A" }}</td>
      <td>{{ $deactive_auction->duration }}</td>
      <td>{{ $deactive_auction->auction_type }}</td>
      <td>{{ $deactive_auction->final_countdown }}</td>
      <td>{{ $deactive_auction->discount }}</td>
     
      </tr>

    @endforeach
    </tbody>
</table>