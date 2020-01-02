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

        @include('layouts.admin_left_sidebar')
        
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
    