<div id="LeftMenu" class="box">
    <div class="list-group panel">
        <!-- One  Starts-->
        <a href="#leftnav1" class="list-group-item list-group-item-success company_setup strong collapsed" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-building-o" aria-hidden="true"></i>Company Setup</a>
        <div class="collapse submenu-outer" id="leftnav1" style="height: 0px;">
            <a href="{{URL::to('/Setting/Settings')}}" class="list-group-item sub-item default_setting"><i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                Default Settings</a>
            <a href="{{ route('manageUsers') }}" class="list-group-item sub-item manage_user"> <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                Manage User</a>
        </div>
        <!-- One Ends-->
        <!-- Two Ends-->
        <a href="#leftnav2" class="list-group-item list-group-item-success apexlink_new strong collapsed" data-toggle="collapse" data-parent="#LeftMenu"><i class="fa fa-bell" aria-hidden="true"></i>
            Apexlink New</a>
        <div class="collapse submenu-outer" id="leftnav2">
            <a href="{{route('ApexLinkNew')}}" class="list-group-item sub-item whats_new"> <i class="fa fa-long-arrow-right" aria-hidden="true"></i>What's New</a>
        </div>
        <!-- Two Ends-->
    </div>
</div>