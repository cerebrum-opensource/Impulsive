<table>
    <thead>
     <tr>
      <th>{{ trans('label.order_time') }} </th>
      <th>{{ trans('label.type') }} </th>
      <th>{{ trans('label.product_title') }} </th>
      <th>{{ trans('label.article_number') }} </th>
      <th>{{ trans('label.name') }} </th>
      <th>{{ trans('label.address') }} </th>
      <th>{{ trans('label.user_ID') }} </th>
      <th>{{ trans('label.order_email') }} </th>
      <th>{{ trans('label.payment_method') }} </th>
      <th>{{ trans('label.status') }} </th>
      <th>{{ trans('label.price') }} </th>
      <th>{{ trans('label.shiping_price') }} </th>
      <th>{{ trans('label.total_amount') }} </th>
      
   </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>

      @php
      $product = $order->product;
       
      @endphp

      <td>{{ $order->created_at }}</td>
      <td>{{ $order->type_name }}</td>
      <td> {{ $product->product_title }}</td>
      <td> {{ $product->article_number }}</td>
      <td>{{ $order->user->first_name ? $order->user->first_name  : 'N/A'  }} {{ $order->user->last_name ? $order->user->last_name  : 'N/A'  }}</td>
      <td>{!! $order->user->full_address !!}</td>
      <td>{{ $order->user->id }}</td>
      <td>{{ $order->user->email }}</td>
      <td>{{ $order->transaction ? $order->transaction->getPaymentMethodnameAttribute() : 'N/A' }}</td>
     
      <td>
        {!! $order->getProductStatusBadgeAttribute() !!}
      </td>
      <td>{{ $order->amount ? CURRENCY_ICON.$order->amount  : 'N/A'}}</td>
      <td>{{ $order->shipping_price ? CURRENCY_ICON.$order->shipping_price  : 'N/A'  }}</td>
      <td>{{ CURRENCY_ICON.($order->amount+$order->shipping_price+$order->tax) }}</td>
     
      </tr>

    @endforeach
    </tbody>
</table>