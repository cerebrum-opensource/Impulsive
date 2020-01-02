@inject('userModal','App\User')
@php
$userData = $userModal->find(Auth::user()->id);

$bidData = $userData->getUserActivity();
$bidCount = $bidData['bid_count'];
$detailData = $userData->getUserDetail();

@endphp

<div id="mySidenav" class="sidenav">
    <p> < {{ trans('label.close_menu') }}</p>
    <h3>{{ trans('label.your_profile') }}</h3>
    <div class="table-otr table3">
        <table class="table">
            <tr>
            <td colspan="2" class="text-center bg-color">{{ trans('label.account_summary') }}</td>
            </tr>
            <tr>
            <td class="konstand-otr">{{ trans('label.total_winis') }} :</td>
            <td class="gebote" id="sidenav_bid_count">{{ $bidData['reflect_bid'] }}</td>
            </tr>
            <tr>
            <td class="konstand-otr">{{ trans('label.free_winis') }} :</td>
            <td class="gebote" id="sidenav_free_bid_count">{{ $bidData['free_pending_bid'] }}</td>
            </tr>
            <tr>
            <td class="konstand-otr">{{ trans('label.buy_winis') }} :</td>
            <td class="gebote" id="sidenav_purchased_bid_count">{{ $bidData['purchased_user_bid'] }}</td>
            </tr>
            <tr>
            <!-- <td colspan="2" class="popup-deals">{{ trans('label.deals') }}</td> -->
            </tr>
            <tr>
            <!-- <td>{{ trans('label.profits')  }}</td>
            <td> {{ $detailData['winAuctionCount'] }} / {{ $detailData['lossAuctionCount'] }}</td> -->
            </tr>
            <tr>
            <td colspan="2"></td>
            </tr>
        </table>
    </div>
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="{{ route('buy_package_list') }}" class="{{ (request()->is('packages')) ? 'activeNavigation' : '' }}">{{ trans('label.charge_bid_account') }} </a>
    <a href="{{ route('profile-detail') }}" class="{{ (request()->is('profile_detail')) ? 'activeNavigation' : '' }}" >{{ trans('label.bid_account') }} </a>
    <a href="{{ route('win_list') }}" class="{{ (request()->is('win_list')) ? 'activeNavigation' : '' }}" >{{ trans('label.win_loss_list') }} </a>
    <a href="{{ route('recall_list') }}" class="{{ (request()->is('recall_list')) ? 'activeNavigation' : '' }}">{{ trans('label.recall_list') }} </a>
    <a href="{{ route('wish_list') }}" class="{{ (request()->is('wish_list')) ? 'activeNavigation' : '' }}" >{{ trans('label.wish_list') }} </a>
    <a href="{{ route('invite-friend') }}" class="{{ (request()->is('invite_friend')) ? 'activeNavigation' : '' }}">{{ trans('label.invite_friend') }} </a>
    <a href="{{ route('edit_profile') }}" class="{{ (request()->is('edit_profile')) ? 'activeNavigation' : '' }}">{{ trans('label.edit_profile') }} </a>
    <a href="{{ route('redeem_voucher') }}" class="" >{{ trans('label.redeem_voucher') }} </a>
    <!-- <a href="{{ route('pages_route',['about_us']) }}" class="{{ (request()->is('pages/about_us')) ? 'activeNavigation' : '' }}">{{ trans('label.about') }} </a> -->
   <!-- <a href="#"  >{{ trans('label.services') }} </a>
    <a href="#" >{{ trans('label.clients') }} </a> -->
    <!-- <a href="{{ route('pages_route',['contact_us']) }}" class="{{ (request()->is('pages/contact_us')) ? 'activeNavigation' : '' }}" >{{ trans('label.contact') }} </a> -->
</div>