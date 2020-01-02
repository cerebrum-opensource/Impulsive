@extends('layouts.adminapp')
@section('content')
@php
  $name = trans('label.user_detail');

  $salutation_array = salutation_title();
  //print_r($salutation_array);
@endphp
@include('admin.breadcome', ['name' => $name])
<div class="single-product-tab-area mg-b-30">
    <!-- Single pro tab review Start-->
    <div class="single-pro-review-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="review-tab-pro-inner">
                        <ul id="myTab3" class="tab-review-design">
                            <li class="active"><a href="#description">{{ trans('label.user_detail') }} </a></li>
                        </ul>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-8">
                                <div class="review-content-section review-content-section2">
                                  
                                    <p> <label> {{ trans('label.title') }} </label> <span> {{ $salutation_array[$user->salutation] ?? 'N/A'}} </span></p>
                                    <p> <label> {{ trans('label.first_name') }} </label> <span> {{ $user->first_name ?? 'N/A'}} </span></p>
                                     <p> <label> {{ trans('label.last_name') }} </label> <span> {{ $user->last_name ??  'N/A'}} </span></p>
                                     <p> <label> {{ trans('label.username') }} </label> <span> {{ $user->username ??  'N/A'}} </span></p>
                                    <p> <label> {{ trans('label.customer_no') }} </label> <span> {{ $user->id }} </span></p>
                                    <p> <label> {{ trans('label.email') }} </label> <span> {{ $user->email ?? 'N/A'}} </span></p>
                                    <br>
                                    <p> <label> {{ trans('label.street') }}  </label>  <span> {{ $user->street ?? 'N/A'}} </span></p>
                                    <p> <label> {{ trans('label.house_number') }}  </label>  <span> {{ $user->house_number ?? 'N/A'}} </span></p>
                                    <p> <label> {{ trans('label.postcode') }}  </label> <span> {{ $user->postal_code ?? 'N/A'}} </span></p>
                                    <p> <label> {{ trans('label.city') }} </label> <span> {{ $user->city ?? 'N/A'}} </span></p>
                                    <p> <label> {{ trans('label.state') }} </label> <span> {{ $user->state ?? 'N/A'}} </span></p>
                                    <p> <label> {{ trans('label.country') }} </label> <span> {{ $user->country ?? 'N/A'}} </span></p>
                                     <p> <label> {{ trans('label.dob') }}  </label> <span> {{ $user->dob ?? 'N/A'}} </span></p>
                                     <p> <label> {{ trans('label.date_time_registration') }}  </label> <span> {{ $user->created_at ?? 'N/A'}} </span></p>
                                </div>
                            </div>
                             <div class="col-lg-3 col-md-3 col-sm-3 col-xs-8">
                                <div class="review-content-section review-content-section2">
                                    <p> <label>  {{ trans('label.status') }} </label> <span> {{ $user->status_name }} </span></p>

                                    <p> <label>  {{ trans('label.bid_count') }} </label> <span> @if(count($user->userBid))
                                        {{ $user->userBid[0]->bid_count }}
                                        @else
                                        N/A
                                        @endif </span>
                                    </p>
                                    <p> <label>  {{ trans('label.win_auction') }} </label> <span>{{ $data['winAuctionCount'] }}</span></p>
                                    <p> <label>  {{ trans('label.lose_auction') }}  </label> <span>{{ $data['lossAuctionCount'] }}</span></p>
                                    <p> <label>  {{ trans('label.total_bid_agent_count') }} </label> <span>{{ $data['bidAgentCount'] }}</span></p>
                                    <p> <label>  {{ trans('label.total_bid_placed_on_auction') }} </label> <span>{{ $data['total_bid_placed'] }}</span></p>
                                    <p> <label>  {{ trans('label.total_auction') }} </label> <span>  {{ $data['winAuctionCount']+$data['lossAuctionCount'] }}</span></p>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-8">
                                <h2> {{ trans('label.packages') }}</h2>
                                <div class="review-content-section package_table">
                                    @include('admin.users.user_package_table')
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
   
 $('body').on('click', '.package_table .pagination a', function(e) {
   e.preventDefault();
   var url = $(this).attr('href');  
    $.ajax({
      url:url,
      type:"GET",
      processData: false,
      contentType: false,
      dataType: "html",
      success:function(data){
          $(".package_table").html(data);
      },
      error:function(data){
          $(".package_table").html(data);
      }
   });

});

</script>
@endsection