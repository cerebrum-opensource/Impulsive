<table>
   <tr>
      <th><input name="product_all" class="checked_all" type="checkbox"> </th>
      <th>{{ trans('label.order_time') }} </th>
      <th>{{ trans('label.order_number') }} </th>
      <th>{{ trans('label.customer_name') }} </th>
  
      <th>{{ trans('label.user_ID') }} </th>
      <th>{{ trans('label.address') }} </th>
      <th>{{ trans('label.order_email') }} </th>
      <th>{{ trans('label.payment_method') }} </th>
      <th>{{ trans('label.product_title') }} </th>
      <th>{{ trans('label.article_number') }} </th>
      <th>{{ trans('label.status') }} </th>
      <th>{{ trans('label.price') }} </th>
      <th>{{ trans('label.bids_count') }} </th>

      <th>{{ trans('label.shiping_price') }} </th>

      <th>{{ trans('label.total_amount') }} </th>
      <th>{{ trans('label.type') }} </th>
      <th>{{ trans('label.setting') }} </th>
   </tr>

   @if(count($orders))
   @foreach($orders as $order)
   <tr>

      @php
      $product = $order->product;
       
      @endphp

      <td><input value="{{ $order->id }}" name="product" class="checkbox" type="checkbox"> </td>
      <td>{{ $order->created_at }}</td>
      <td>{{ $order->id }}</td>
      <td>{{ $order->user->fullname != '' ? $order->user->fullname : 'N/A'  }}</td>
      <td>{{ $order->user->id }}</td>
      <td>{!! $order->user->full_address !!}</td>
      <td>{{ $order->user->email }}</td>
      <td>{{ $order->transaction->getPaymentMethodnameAttribute() }}</td>
      <td> {{ $product->product_title }}</td>
       <td> {{ $product->article_number }}</td>
      <td>
        {!! $order->getProductStatusBadgeAttribute() !!}
      </td>
      <td>{{ $order->amount ? CURRENCY_ICON.$order->amount  : 'N/A'}}</td>
      <td>{{ $order->auction_winner ? $order->auction_winner->bid_count : 'N/A' }}</td>
      <td>{{ $order->shipping_price ? CURRENCY_ICON.$order->shipping_price  : 'N/A'  }}</td>
     
      <td>{{ CURRENCY_ICON.($order->amount+$order->shipping_price+$order->tax) }}</td>
      <td>{{ $order->type_name }}</td>
      <td>
         <button data-toggle="tooltip" title="{{ trans('label.view') }}" class="pd-setting-ed">
          <a href="{{ route('order_view', encrypt_decrypt('encrypt',$order->id))}}" > 
            <i class="fa fa-eye" aria-hidden="true"></i>
          </a>
          </button>
      </td>
   </tr>

    @endforeach
        @else
          <tr><td colspan="8">{{ trans('label.no_record_found') }}</td></tr>
      @endif
</table>

<div class="custom-pagination">
{{ $orders->links('vendor.pagination.custom') }}
</div>