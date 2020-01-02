@if(count($liveAuctions))   
   @foreach($liveAuctions as $key => $liveAuction)

   <tr>
      <td>{{ $key+1 }}</td>
      <td>{{ $liveAuction->product->product_title }} </td>
      <td>{{ $liveAuction->auction_type == 0 ? trans('label.yes')  : trans('label.no') }} </td>
      <td>{{ seconds_to_h_m_s($liveAuction->duration_count) }} </td>
   </tr>
   @endforeach             
@endif