<table>
   <tr>
      <th><input name="product_all" class="checked_all" type="checkbox"> </th>
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
      <th>{{ trans('label.setting') }} </th>
   </tr>

   @if(count($orders))
   @foreach($orders as $order)

   @php
    $transactionId = '';
      if($order->payer_id)
        $transactionId = $order->payer_id;
      else if($order->transaction_id)
        $transactionId = $order->transaction_id;
   @endphp


   <tr>
      <td><input value="{{ $order->id }}" name="product" class="checkbox" type="checkbox"> </td>
      <td>{{ $order->created_at }}</td>
      <td>{{ $order->id ? trans('label.invoice').$order->id : 'N/A' }}</td>
      <td>{{ $transactionId }}</td>
      <td>{{ $order->user->fullname != '' ? $order->user->fullname : 'N/A'  }}</td>
      <td>{{ $order->user->id }}</td>
      <td>{{ $order->user->email }}</td>
      <td> {!! $order->getPaymentMethodnameAttribute() !!}</td>
      <td>  {!! $order->getTypenameAttribute() !!} </td>
      <td>
        {!! $order->getStatusBadgeAttribute() !!}
      </td>
      <td>{{ $order->amount ? $order->amount.CURRENCY_ICON  : 'N/A'}}</td>
      <td>
         <button data-toggle="tooltip" title="{{ trans('label.view') }}" class="pd-setting-ed">
          <a href="{{ route('transaction_view', encrypt_decrypt('encrypt',$order->id))}}" > 
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