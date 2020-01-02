<table class="table">
    <thead>
        <th> {{trans('label.article_name')}} </th>
        <th> {{trans('label.article_number')}} </th>
        <th> {{trans('label.date')}} </th>
        <th> {{trans('label.amount')}} </th>
    </thead>
    <tbody>
        @if($winList)  
            @foreach($winList as $win)
            <tr>
                <td>{{ $win->product->product_title }}</td>
                <td>{{ $win->product->article_number }}</td>
                <td>{{ $win->created_at->format('d.m.Y') }}</td>
                <td>{{ CURRENCY_NAME }} {{ $win->amount }}</td>
            </tr>
            @endforeach             
        @endif
    </tbody>
</table>
<div class="custom-pagination">
    {{ $winList->links('vendor.pagination.custom') }}
</div>