<table>
    <thead>
     <tr>
      <th>{{ trans('label.transaction_time') }} </th>
      <th>{{ trans('label.transaction_number') }} </th>
      <th>{{ trans('label.transaction_id') }} </th>
      <th>{{ trans('label.customer_name') }} </th>
      <th>{{ trans('label.user_ID') }} </th>
      <th>{{ trans('label.order_email') }} </th>
      <th>{{ trans('label.payment_method') }} </th>
      <th>{{ trans('label.transaction_type') }} </th>
      <th>{{ trans('label.status') }} </th>
      <th>{{ trans('label.total_amount') }} </th>
      
   </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
      <tr>

        <td>{{ $order->created_at }}</td>
        <td>{{ $order->id ? trans('label.invoice').$order->id : 'N/A' }}</td>
        <td>{{ $order->transaction_id }}</td>
        <td>{{ $order->user->fullname != '' ? $order->user->fullname : 'N/A'  }}</td>
        <td>{{ $order->user->id }}</td>
        <td>{{ $order->user->email }}</td>
        <td> {!! $order->getPaymentMethodnameAttribute() !!}</td>
        <td>  {!! $order->getTypenameAttribute() !!} </td>
        <td>
          {!! $order->getStatusBadgeAttribute() !!}
        </td>
        <td>{{ $order->amount ? CURRENCY_ICON.$order->amount  : 'N/A'}}</td>
     
      </tr>

    @endforeach
    </tbody>
</table>