<div class="left-sidebar-pro">
    <nav id="sidebar" class="">
        <div class="sidebar-header">
            <a href="{{ route('admin_dashboard') }}"><img class="main-logo" src="{{ asset('images/Logo.png') }}" alt="" /></a>
            <strong><img src="{{ asset('images/Logo.png') }}" alt="" /></strong>
        </div>
		<div class="nalika-profile">
			<div class="profile-dtl">
                <form action="#" method="POST" id="admindp" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="admindp">
                </form>
                <a href="#">
                    @if(App\Http\Helper\Helper::getUser()->profile_img != "")
                        <img class="admindp" src="{{ asset('images/admin/dp')}}/{{App\Http\Helper\Helper::getUser()->profile_img}}" alt="" />
                    @else
                        <img class="admindp" src="{{ asset('images/admin/img/notification/4.jpg') }}" alt="" />
                    @endif
                </a>
				<h2>{{App\Http\Helper\Helper::getUser()->first_name}} <span class="min-dtn">{{App\Http\Helper\Helper::getUser()->last_name}}</span></h2>

			</div>
			<!-- <div class="profile-social-dtl">
				<ul class="dtl-social">
					<li><a href="#"><i class="icon nalika-facebook"></i></a></li>
					<li><a href="#"><i class="icon nalika-twitter"></i></a></li>
					<li><a href="#"><i class="icon nalika-linkedin"></i></a></li>
				</ul>
			</div> -->
		</div>
        <div class="left-custom-menu-adp-wrap comment-scrollbar">
            <nav class="sidebar-nav left-sidebar-menu-pro">
                <ul class="metismenu" id="menu1">
                    <li class="">
                        <a class="has-arrow" href="{{ route('admin_dashboard') }}">
                           <i class="icon nalika-home icon-wrap"></i>
                           <span class="mini-click-non">{{ trans('label.dashboard') }} </span>
                        </a>
                        <ul class="submenu-angle" aria-expanded="true">
                            <li><a title="Dashboard v.1" href="{{ route('admin_dashboard') }}"><span class="mini-sub-pro">{{ trans('label.dashboard') }} </span></a></li>
                        </ul>
                    </li>
                    <li class="">
                        <a class="has-arrow" href="javascript::void(0);">
						   <i class="icon nalika-home icon-wrap"></i>
						   <span class="mini-click-non">{{ trans('label.products') }}  </span>
						</a>
                        <ul class="submenu-angle" aria-expanded="true">
                            <li><a title="Product List" href="{{ route('admin_product_list') }}"><span class="mini-sub-pro">{{ trans('label.manage') }} </span></a></li>
                            <!-- <li><a title="Product Edit" href="{{ route('admin_product_add') }}"><span class="mini-sub-pro">Product Add</span></a></li> -->
                            <li><a title="Product Detail" href="{{ route('admin_product_category') }}"><span class="mini-sub-pro">{{ trans('label.category') }} </span></a></li>
                            <li><a title="Product Cart" href="{{ route('attribute_list') }}"><span class="mini-sub-pro">{{ trans('label.attributes') }} </span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="#mailbox.html" aria-expanded="false"><i class="icon nalika-pie-chart icon-wrap"></i> <span class="mini-click-non">{{ trans('label.auctions') }} </span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a title="File Manager" href="{{ route('auctions') }}"><span class="mini-sub-pro">{{ trans('label.list') }} </span></a></li>
                            <li><a title="Blog" href="blog.html"><span class="mini-sub-pro">{{ trans('label.overview') }} </span></a></li>
                            <li><a title="Blog Details" href="blog-details.html"><span class="mini-sub-pro">{{ trans('label.active') }} </span></a></li>
                            <li><a title="404 Page" href="404.html"><span class="mini-sub-pro">{{ trans('label.queue') }} </span></a></li>
                            <li><a title="404 Page" href="404.html"><span class="mini-sub-pro">{{ trans('label.planned') }} </span></a></li>
                            <li><a title="404 Page" href="404.html"><span class="mini-sub-pro">{{ trans('label.de_active') }} </span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="#mailbox.html" aria-expanded="false"><i class="icon nalika-pie-chart icon-wrap"></i> <span class="mini-click-non">{{ trans('label.suppliers') }} </span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a title="File Manager" href="{{ route('suppliers') }}"><span class="mini-sub-pro">{{ trans('label.list') }} </span></a></li>
                            <li><a title="Blog" href="{{ route('add_user') }}"><span class="mini-sub-pro">{{ trans('label.add') }} </span></a></li>
                        </ul>
                    </li>
                     <li>
                        <a class="has-arrow" href="#mailbox.html" aria-expanded="false"><i class="icon nalika-pie-chart icon-wrap"></i> <span class="mini-click-non">{{ trans('label.packages') }} </span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a title="{{ trans('label.list') }}" href="{{ route('packages') }}"><span class="mini-sub-pro">{{ trans('label.list') }}</span></a></li>
                            <li><a title="{{ trans('label.add') }}" href="{{ route('add_package') }}"><span class="mini-sub-pro">{{ trans('label.add') }}</span></a></li>

                             <li><a title="{{ trans('label.promotional_list') }}" href="{{ route('promo_packages') }}"><span class="mini-sub-pro">{{ trans('label.promotional_list') }}</span></a></li>
                            
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="mailbox.html" aria-expanded="false"><i class="icon nalika-pie-chart icon-wrap"></i> <span class="mini-click-non">{{ trans('label.newsletter') }} </span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a title="File Manager" href="file-manager.html"><span class="mini-sub-pro">{{ trans('label.list') }}</span></a></li>
                            <li><a title="Blog" href="blog.html"><span class="mini-sub-pro">{{ trans('label.subscribe_new') }}</span></a></li>
                        </ul>
                    </li>
                    
                    <li id="removable">
                        <a class="has-arrow" href="#" aria-expanded="false"><i class="icon nalika-new-file icon-wrap"></i> <span class="mini-click-non">{{ trans('label.pages') }} </span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a title="Login" href="login.html"><span class="mini-sub-pro">{{ trans('label.term_&_condition') }} </span></a></li>
                            <li><a title="Register" href="register.html"><span class="mini-sub-pro">{{ trans('label.privacy_&_policy') }} </span></a></li>
                            <li><a title="Lock" href="lock.html"><span class="mini-sub-pro">{{ trans('label.contact') }} </span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="mailbox.html" aria-expanded="false"><i class="icon nalika-diamond icon-wrap"></i> <span class="mini-click-non">{{ trans('label.settings') }} </span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a title="Google Map" href="google-map.html"><span class="mini-sub-pro">{{ trans('label.role') }} </span></a></li>
                            <li><a title="Data Maps" href="data-maps.html"><span class="mini-sub-pro">{{ trans('label.permissions') }} </span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="mailbox.html" aria-expanded="false"><i class="icon nalika-mail icon-wrap"></i> <span class="mini-click-non">{{ trans('label.users_side') }} </span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a title="Inbox" href="{{ route('users') }}"><span class="mini-sub-pro">{{ trans('label.manage') }} </span></a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </nav>
</div>

<div class="all-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="logo-pro">
                    <a href="{{ route('admin_dashboard') }}"><img class="main-logo" src="{{ asset('images/admin/img/logo/logo.png') }}" alt="" /></a>
                </div>
            </div>
        </div>
    </div>
    