<table class="table">
    <thead>
        <th> {{trans('label.bid_type')}} </th>
        <th> {{trans('label.package_name')}} </th>
        <th> {{trans('label.bid_count')}} </th>
        <th> {{trans('label.amount')}} </th>
    </thead>
    <tbody>
        @if(count($data['userPlan']))  
        @foreach($data['userPlan'] as $plan)
        <tr>
            <td>{{ $plan->type }}</td>
            <td>{{ $plan->package ? $plan->package->name : $plan->type }} </td>
            <td>{{ $plan->bid_count }} </td>
            <td>{{ $plan->amount ? CURRENCY_ICON.' '.$plan->amount : trans('label.free_text') }} </td>
        </tr>
        @endforeach             
        @endif
    </tbody>
</table>
<div class="custom-pagination">
    {{ $data['userPlan']->links('vendor.pagination.custom') }}
</div>