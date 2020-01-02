<div class="header-advance-area">
   <div class="header-top-area">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               <div class="header-top-wraper">
                  <div class="row">
                     <div class="col-lg-1 col-md-0 col-sm-1 col-xs-12">
                        <div class="menu-switcher-pro">
                           <button type="button" id="sidebarCollapse" class="btn bar-button-pro header-drl-controller-btn btn-info navbar-btn">
                           <i class="icon nalika-menu-task"></i>
                           </button>
                        </div>
                     </div>
                     <div class="col-lg-6 col-md-7 col-sm-6 col-xs-12"></div>
                     <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                        <div class="header-right-info">
                           <ul class="nav navbar-nav mai-top-nav header-right-menu">
                              <li class="nav-item">
                                 <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                                 <i class="icon nalika-user nalika-user-rounded header-riht-inf" aria-hidden="true"></i>
                                 <span class="admin-name">{{App\Http\Helper\Helper::getUser()->first_name}} {{App\Http\Helper\Helper::getUser()->last_name}}</span>
                                 </a>
                              </li>
                              <li class="nav-item nav-setting-open">
                                 <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="icon nalika-menu-task"></i></a>
                                 <div role="menu" class="admintab-wrap menu-setting-wrap menu-setting-wrap-bg dropdown-menu animated zoomIn">
                                    <ul class="nav nav-tabs custon-set-tab">
                                       <li class="active"><a href="#">{{ trans('label.edit_profile') }}</a></li>
                                       <li><a href="{{ route('admin-logout') }}" >{{ trans('label.logout') }} </a></li>
                                       <form id="logout-form" action="{{URL::to('/logout')}}" method="POST" style="display: none;">
                                          @csrf
                                       </form>
                                    </ul>
                                 </div>
                              </li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="breadcome-area">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               <div class="breadcome-list">
                  <div class="row">
                     <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <div class="breadcomb-wp">
                           <div class="breadcomb-icon">
                              <i class="icon nalika-home"></i>
                           </div>
                           <div class="breadcomb-ctn">
                              <h2> {{ $name }}</h2>
                              <p>{{ trans('label.welcome_to') }} {{App\Http\Helper\Helper::getUser()->first_name}} {{App\Http\Helper\Helper::getUser()->last_name}} <span class="bread-ntd"></span></p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>