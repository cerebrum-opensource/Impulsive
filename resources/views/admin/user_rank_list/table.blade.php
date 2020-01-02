<table id='myTable'>
  <thead>
    <tr>
    
      <th>{{ trans('label.name') }} </th>
      <th>{{ trans('label.email') }} </th>
      <th>{{ trans('label.login_count') }} </th>
      <th>{{ trans('label.filter_ranking') }}</th>
      <th>{{ trans('label.total_auctions') }}</th>
      <th>{{ trans('label.won_auctions') }}</th>
      <th>{{ trans('label.lost_auctions') }}</th>
    </tr>
  </thead>
  <tbody>
   @if(count($users))
   @foreach($users as $user)
   
     <tr>

        <td> {{ $user->full_name }}</td>
        <td> {{ $user->email }}</td>
        <td> {{ $user->login_count }}</td>

        <td> {!! (array_key_exists($user->id, $topBidderData))? $topBidderData[$user->id] :0 !!}</td>
        <td> {!! (array_key_exists($user->id, $totalAuctionData))? $totalAuctionData[$user->id] :0 !!}</td>
        <td> {!! (array_key_exists($user->id, $auctionWinner))? $auctionWinner[$user->id] :0 !!}</td>
        <td> {!! (array_key_exists($user->id, $userLossAuctionData))? $userLossAuctionData[$user->id] :0 !!}</td>
       
     </tr>
  

    @endforeach
     @endif
    </tbody>
</table>
