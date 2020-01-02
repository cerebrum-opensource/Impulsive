@extends('layouts.adminapp')
@section('css')
<style>
</style>
@endsection
@section('content')
@php
$name = trans('label.auction_product_detail');
$count = explode(":",$auction->duration);
$auction_id = $auction->id;
$duration_count = $auction->auctionQueue[0]->duration_count;

$hours = $count[0] !='00' ? (int)$count[0]  : '' ;
$minutes = $count[1] !='00' ? (int)$count[1]  : '' ;
$seconds = $count[2] !='00' ? (int)$count[2]  : '' ;

if(count($auction->auctionQueue))
  $auctionData = $auction->auctionQueue[0];
if($auctionData->end_time)
{
   $end_datetime = $auctionData->end_time;
}
elseif($auctionData->updated_at)
{
   $end_datetime = $auctionData->updated_at;
  
}
else
{
   $end_datetime = "N/A";
}
  


 if($auction->product->feature_image_1)
    $image = $auction->product->feature_image_1;
 if($auction->product->feature_image_2)
    $image = $auction->product->feature_image_2;
 if($auction->product->feature_image_3)
    $image = $auction->product->feature_image_3;
 if($auction->product->feature_image_4)
    $image = $auction->product->feature_image_4;

@endphp
@include('admin.breadcome', ['name' => $name])
<div class="single-product-tab-area mg-b-30">
   <!-- Single pro tab review Start-->
   <div class="single-pro-review-area">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

               <div class="review-tab-pro-inner">
                  <button style="float: right;"href="#" class="btn btn-primary basic-btn export_record" id="deactive_export"><b> {{trans('label.export')}} </b></button>
                  <ul id="myTab3" class="tab-review-design">
                     <li class="active"><a href="#description"> {{ trans('label.auction_product_detail')}} </a></li>
                     <!--  <li><a href="#reviews"><i class="icon nalika-picture" aria-hidden="true"></i> Pictures</a></li> -->
                  </ul>
                  <div class="row">
                     <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="auction_detail_div">
                           <span class="auction-span">{{ trans('label.product_title') }}</span>  
                           <span class="auction-span2">{{ $auction->product->product_title }}</span>
                        </div>
                        <div class="auction_detail_div">
                           <span class="auction-span">{{ trans('label.live_auction') }}</span>  
                           <span class="auction-span2">@if($auction->live_auction == 1)
                           {{ trans('label.yes') }} 
                           @elseif($auction->live_auction == 0)
                           {{ trans('label.no') }} 
                           @else
                           @endif</span>
                        </div>
                        <div class="auction_detail_div">
                           <span class="auction-span">{{ trans('label.counter') }}</span> 
                           <span class="auction-span2"> {{ $auction->duration }}</span>
                        </div>
                        <div class="auction_detail_div">
                           <span class="auction-span">{{ trans('label.start_date_time') }}</span> 
                           <span class="auction-span2">{{ $auction->start_date }}  </span>
                        </div>
                        <div class="auction_detail_div">
                           <span class="auction-span">{{ trans('label.countdown') }}</span> 
                           <span class="auction-span2"> {{ $auction->final_countdown }} {{ trans('label.seconds') }}</span>
                        </div>
                        <div class="auction_detail_div">
                           <span class="auction-span">{{ trans('label.regular_price') }}</span> 
                           <span class="auction-span2">{{ CURRENCY_ICON }} {{ price_reflect_format($auction->product->product_price) }} </span>
                        </div>
                                               
                        

                        
                         <span class="img-count">
                         <img src="@if($image) {{ asset('/images/admin/products/').'/'.$auction->product->id.'/'.$image }} @else {{ asset('images/Produkt.png') }} @endif"  class="box-img2" alt="{{ $auction->product->product_title }}" title="{{ $auction->product->product_title }}">
                         </span>
                        
                     </div>
                     <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="auction_detail_div">
                           <span class="auction-span">{{ trans('label.current_price') }}</span> 
                           <span class="auction-span2">{{ $auctionData ?  CURRENCY_ICON." ".price_reflect_format($auctionData->bid_price) : '' }}</span>
                        </div>
                        <div class="auction_detail_div">
                           <span class="auction-span">{{ trans('label.auction_status') }}</span> 
                           <span class="auction-span2">{{ $auctionData ? $auctionData->status_name : '' }}  </span>
                        </div>

                     </div>
                      @php
                           $total_bids = 0;
                           foreach($automateAgent as $agentData){
                              $total_bids = $total_bids + $agentData->total_bids;
                           }
                        @endphp
                         @if($duration_count != 0)
                     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-otr auction_detail_div">
                              <table class="table table-responsive table-condensed">
                                 {{ trans('label.live_bid_agent_data') }}
                                 
                                 <tr>
                                    <th> {{ trans('label.total_active_bids_set') }} </th>
                                    <td>{{ $total_bids }}({{count($automateAgent) }})</td>
                                 </tr>
                                 <tr>
                                    <th> {{ trans('label.active_agent_username') }}</th>
                                    <th> {{ trans('label.amount_of_agent_bids') }} </th>
                                 </tr>
                                 
                                 @foreach($automateAgent as $agentData)
                                 <tr>
                                    <td>{{ $agentData->user->username }}</td>
                                    <td>{{ $agentData->total_bids }}</td>
                                 </tr>
                                 @endforeach
                                 
                                 
                           </table>
                            </div>
                        </div>
                        @endif
                        
                        @if($duration_count == 0)
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-otr auction_detail_div">
                              <table class="table table-responsive table-condensed">
                                 {{ trans('label.data_after_auction') }}
                                    <tr>
                                       <th>{{ trans('label.username') }}</th>
                                       <th>{{ trans('label.manual_bids') }}</th>
                                       <th>{{ trans('label.agent_bids') }}</th>
                                       <th>{{ trans('label.agent_bids_back') }}</th>
                                    </tr>
                                 @foreach($automateAgent as $agentData)
                                 
                                    <tr>
                                       <td>{{ $agentData->user->username }}</td>
                                       <td>
                                          @php
                                             $bidCounter = 0;
                                             foreach($bidHistory as $userBidHistory){
                                             
                                                if($userBidHistory->is_manual == 1 && $userBidHistory->user_id == $agentData->user_id)
                                                {
                                                   $bidCounter++;
                                                }
                                             }
                                          @endphp
                                          {{ $bidCounter  }}
                                       </td>
                                       <td>{{ $agentData->total_bids }}</td>
                                       <td>{{ $agentData->remaining_bid }}</td>
                                    </tr>
                                 @endforeach
                                 
                           </table>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-otr auction_detail_div">
                              <table class="table table-responsive table-condensed">
                                 {{ trans('label.total_bid_placed_on_auction') }}
                                 <tr>
                                    <th>{{ trans('label.manual_bids') }}</th>
                                    <th>{{ trans('label.agent_bids') }}</th>
                                    <th>{{ trans('label.sum') }}</th>
                                 </tr>
                                 @php
                                    $bidCounter = 0;
                                    $automateBidCounter = 0;
                                    foreach($bidHistory as $userBidHistory){
                                       if($userBidHistory->is_manual == 1)
                                       {
                                          $bidCounter++;
                                       }
                                       elseif($userBidHistory->is_manual == 0)
                                       {
                                          $automateBidCounter++;
                                       }
                                    }

                                 @endphp
                                 <tr>
                                    <td>{{ $bidCounter }}</td>
                                    <td>{{ $automateBidCounter }}</td>
                                    <td>{{ $bidCounter + $automateBidCounter }}</td>
                                 </tr>
                              </table>
                           </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-otr auction_detail_div">
                              <table class="table table-responsive table-condensed">
                                 <th>{{ trans('label.end_date_time') }}</th>
                                    <td>{{ $end_datetime }}</td>
                                 
                              </table>
                           </div>
                        </div>
                        @endif
                     <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                        <div class="table-otr auction_detail_div">
                           <h2> {{ trans('label.bids') }}</h2>
                           @if(count($bidHistory))
                          
                           <table class="table table-responsive table-condensed">
                              @foreach($bidHistory as $bid)
                              <tr>
                                 <td style="width:100px;"> {{ $bid->user->username }}</td>
                                 <td style="width:100px;">{{ price_reflect_format($bid->bid_price) }} {{ CURRENCY_ICON }}</td>
                                 <td style="width:100px;">{{ $bid->created_at_format }}</td>
                                 @php
                                 $handImage = asset('images/auto-agent.png');
                                 $text = trans('label.automate');
                                 if($bid->is_manual){
                                    $handImage = asset('images/manuel-bid.png');
                                    $text = trans('label.manual');
                                 }
                                 @endphp
                                 <td style="width:100px;" class="green"> <img width="25px" src="{{ $handImage }}" alt="{{ $text }}" title="{{ $text }}"></td>
                              </tr>
                              @endforeach
                           </table>
                           @else
                           <h3> {{ trans('label.no_bidding_history') }} </h3>
                           @endif  
                           <?php echo $bidHistory->render(); ?>

                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('javascript')
<script type="text/javascript">
   $('body').on('click', '.export_record', function(e) {
      var url = "{{ route('admin_auction_product_export', encrypt_decrypt('encrypt',$auction_id)) }}";
      window.location = url;
   });
</script>
@endsection