<pre>
<table>
    <thead>
     <tr>
      <th>{{ trans('label.id') }} </th>
      
      <th>{{ trans('label.auction_type') }} </th>
      <th>{{ trans('label.product_title') }} </th>
      <th>{{ trans('label.start_date_time') }} </th>
      <th>{{ trans('label.counter') }} </th>
      <th>{{ trans('label.final_countdown') }} </th>
      <th>{{ trans('label.price') }} </th>
      <th>{{ trans('label.bid_price') }} </th>
      <th>{{ trans('label.status') }} </th>
      
   </tr>
    </thead>
    <tbody>
      @php
    
      @endphp
   

        <tr>

      @php
      $product = $auction->product;
      
      $auction_type = $auction->type;
      if($auction_type == 0)
      {
        $type = trans('label.live');
      }
      elseif($auction_type == 1)
      {
        $type = trans('label.queue');
      }
      elseif($auction_type == 2)
      {
        $type = trans('label.planned');
      }
       
      @endphp

      <td>{{ $auction->id }}</td>
      
      <td> {{ $type }}</td>
      <td> {{ $product->product_title }}</td>
      <td>{{ $auction->start_time }}</td>
      <td>{{ $auction->duration }}</td>
      <td>{{ $auction->final_countdown }}</td>
      <td>{{ price_reflect_format($product->product_price) }} {{ CURRENCY_ICON }} </td>
      <td>{{ price_reflect_format($auction->auctionQueue[0]->bid_price) }} {{ CURRENCY_ICON }}</td>
      <td>{{ $auction->auctionQueue[0]->status_name }}</td>
      </tr>
      <tr>
      </tr>
      <tr>
        <th>{{ trans('label.bid_count') }} </th>
        <th>{{ trans('label.bid_price') }} </th>
        <th>{{ trans('label.username') }} </th>
      </tr>
      @foreach($user_bid_history as $bid_history)
      <tr>
        <td> {{ $bid_history->bid_count }} </td>
        <td> {{ price_reflect_format($bid_history->bid_price) }} {{ CURRENCY_ICON }} </td>
        <td> {{ $bid_history->user->username }} </td>
      <tr>
      @endforeach
    
    </tbody>
</table>