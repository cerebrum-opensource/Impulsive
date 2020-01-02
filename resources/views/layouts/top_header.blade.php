@inject('userModal','App\User')
@inject('setting','App\Models\Setting')
@php
$userData = $userModal->find(Auth::user()->id);

$bidData = $userData->getUserActivity();
$bidCount = $bidData['bid_count'];
//$settingDetail = $setting->first();

@endphp

<div class="top-bar top-bar2">
    <div class="container">
        <h4>{{ trans('label.opening_hours') }} <span>9:00-23:00 Uhr</span></h4>
        <!-- purchased_bid:{{ $bidData['purchased_user_bid'] }} 
        free_bid:{{ $bidData['free_pending_bid'] }} 
        reflect_bid:{{ $bidData['reflect_bid'] }} -->
        <ul>
           <li><span class="white-sec">{{ trans('label.total_winis') }}  <span class="yellow" id="header_bid_count">{{ $bidData['reflect_bid'] }}</span></span></li>
            <li><span class="white-sec">{{ trans('label.free_winis') }}  <span class="yellow" id="header_free_bid_count">{{ $bidData['free_pending_bid'] }} </span></span></li>
            <li><span class="white-sec">{{ trans('label.buy_winis') }}  <span class="yellow" id="header_purchased_bid_count">{{ $bidData['purchased_user_bid'] }}</span></span></li>
           <!-- <li><span class="white-sec"> Aktive Deals <span class="yellow"> 05</span></span></li> -->
            <li>
                <ul class="nav-pills">
                    <li class="dropdownmenu">
                        <a href="javascript:void(0)" class="dropdown-toggle selct-top-bar">{{ Auth::user()->username }} <b class="caret"></b></a>
                        <ul class="dropdown-menu" id="menu1">
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>