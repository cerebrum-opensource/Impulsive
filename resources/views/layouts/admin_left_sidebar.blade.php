@php
   $link = '';
   $subMenu = '';
   if(isset($active))
      $link = $active;
   if(isset($sub_active))
      $subMenu = $sub_active;

@endphp

<div class="left-custom-menu-adp-wrap comment-scrollbar">
   <nav class="sidebar-nav left-sidebar-menu-pro">
      <ul class="metismenu" id="menu1">
         <li class="{{ $link == 'dashboard' ? 'active' : '' }}">
            <a class="has-arrow" href="{{ route('admin_dashboard') }}">
            <i class="icon nalika-home icon-wrap"></i>
            <span class="mini-click-non">{{ trans('label.dashboard') }} </span>
            </a>
            <ul class="submenu-angle {{ $link == 'dashboard' ? 'collapse in' : '' }}" aria-expanded="true">
               <li><a class="{{ $subMenu == 'dashboard' ? 'active' : '' }}" title="Dashboard v.1" href="{{ route('admin_dashboard') }}"><span class="mini-sub-pro">{{ trans('label.dashboard') }} </span></a></li>
            </ul>
         </li>
         <li class="{{ $link == 'products' ? 'active' : '' }}">
            <a class="has-arrow" href="javascript::void(0);">
            <i class="icon nalika-home icon-wrap"></i>
            <span class="mini-click-non">{{ trans('label.products') }}  </span>
            </a>
            <ul class="submenu-angle {{ $link == 'products' ? 'collapse in' : '' }}" aria-expanded="true">
               <li><a class="{{ $subMenu == 'manage' ? 'active' : '' }}" title="{{ trans('label.manage') }}" href="{{ route('admin_product_list') }}"><span class="mini-sub-pro">{{ trans('label.manage') }} </span></a></li>
               <li><a class="{{ $subMenu == 'category' ? 'active' : '' }}" title="{{ trans('label.category') }}" href="{{ route('admin_product_category') }}"><span class="mini-sub-pro">{{ trans('label.category') }} </span></a></li>
               <li><a class="{{ $subMenu == 'product_amount_categories' ? 'active' : '' }}" title="{{ trans('label.product_amount_categories') }}" href="{{ route('admin_product_amount_categories') }}"><span class="mini-sub-pro">{{ trans('label.product_amount_categories') }} </span></a></li>
               <li><a class="{{ $subMenu == 'attribute' ? 'active' : '' }}" title="{{ trans('label.attributes') }}" href="{{ route('attribute_list') }}"><span class="mini-sub-pro">{{ trans('label.attributes') }} </span></a></li>
            </ul>
         </li>
         <li class="{{ $link == 'auctions' ? 'active' : '' }}">
            <a class="has-arrow" href="#mailbox.html" aria-expanded="false"><i class="icon nalika-pie-chart icon-wrap"></i> <span class="mini-click-non">{{ trans('label.auctions') }} </span></a>
            <ul class="submenu-angle {{ $link == 'auctions' ? 'collapse in' : '' }}" aria-expanded="false">
               <li><a class="{{ $subMenu == 'auction_dashboard' ? 'active' : '' }}" title="{{ trans('label.overview') }}" href="{{ route('auctions_dashboard') }}"><span class="mini-sub-pro">{{ trans('label.overview') }} </span></a></li>
               <li><a class="{{ $subMenu == 'add_auction' ? 'active' : '' }}" title="{{ trans('label.add_auction') }}" href="{{ route('add_auction') }}"><span class="mini-sub-pro">{{ trans('label.add_auction') }} </span></a></li>
               <li><a class="{{ $subMenu == 'active_auctions' ? 'active' : '' }}" title="{{ trans('label.active') }}" href="{{ route('active_auctions') }}"><span class="mini-sub-pro">{{ trans('label.active') }} </span></a></li>
               <li><a class="{{ $subMenu == 'queue_auctions' ? 'active' : '' }}" title="{{ trans('label.queue') }}" href="{{ route('queue_auctions') }}"><span class="mini-sub-pro">{{ trans('label.queue') }} </span></a></li>
               <li><a class="{{ $subMenu == 'planned_auctions' ? 'active' : '' }}" title="{{ trans('label.planned') }}" href="{{ route('planned_auctions') }}"><span class="mini-sub-pro">{{ trans('label.planned') }} </span></a></li>
               <li><a class="{{ $subMenu == 'deactive_auctions' ? 'active' : '' }}" title="{{ trans('label.de_active') }}" href="{{ route('deactive_auctions') }}"><span class="mini-sub-pro">{{ trans('label.de_active') }} </span></a></li>
            </ul>
         </li>
         <li class="{{ $link == 'suppliers' ? 'active' : '' }}">
            <a class="has-arrow" href="#mailbox.html" aria-expanded="false"><i class="icon nalika-pie-chart icon-wrap"></i> <span class="mini-click-non">{{ trans('label.suppliers') }} </span></a>
            <ul class="submenu-angle {{ $link == 'suppliers' ? 'collapse in' : '' }}" aria-expanded="false">
               <li><a class="{{ $subMenu == 'supplier_list' ? 'active' : '' }}" title="{{ trans('label.list') }}"  href="{{ route('suppliers') }}"><span class="mini-sub-pro">{{ trans('label.list') }} </span></a></li>
               <li><a class="{{ $subMenu == 'add_supplier' ? 'active' : '' }}" title="{{ trans('label.add') }}"  href="{{ route('add_user') }}"><span class="mini-sub-pro">{{ trans('label.add') }} </span></a></li>
            </ul>
         </li>
         <li class="{{ $link == 'packages' ? 'active' : '' }}">
            <a class="has-arrow" href="#mailbox.html" aria-expanded="false"><i class="icon nalika-pie-chart icon-wrap"></i> <span class="mini-click-non">{{ trans('label.packages') }} </span></a>
            <ul class="submenu-angle {{ $link == 'packages' ? 'collapse in' : '' }}" aria-expanded="false">
               <li><a class="{{ $subMenu == 'package_list' ? 'active' : '' }}" title="{{ trans('label.list') }}" href="{{ route('packages') }}"><span class="mini-sub-pro">{{ trans('label.list') }}</span></a></li>
               <li><a class="{{ $subMenu == 'package_add' ? 'active' : '' }}" title="{{ trans('label.add') }}" href="{{ route('add_package') }}"><span class="mini-sub-pro">{{ trans('label.add') }}</span></a></li>
               <li><a class="{{ $subMenu == 'list_promo' ? 'active' : '' }}" title="{{ trans('label.promotional_list') }}" href="{{ route('promo_packages') }}"><span class="mini-sub-pro">{{ trans('label.promotional_list') }}</span></a></li>
            </ul>
         </li>
         <li class="{{ $link == 'orders' ? 'active' : '' }}">
            <a class="has-arrow"  aria-expanded="false"><i class="icon nalika-pie-chart icon-wrap"></i> <span class="mini-click-non">{{ trans('label.orders') }} </span></a>
            <ul class="submenu-angle {{ $link == 'orders' ? 'collapse in' : '' }}" aria-expanded="false">
               <li><a class="{{ $subMenu == 'order_list' ? 'active' : '' }}" title="{{ trans('label.orders') }}" href="{{ route('admin_order_list') }}"><span class="mini-sub-pro">{{ trans('label.list') }}</span></a></li>
            </ul>
         </li>
         <li class="{{ $link == 'transactions' ? 'active' : '' }}">
            <a class="has-arrow"  aria-expanded="false"><i class="icon nalika-pie-chart icon-wrap"></i> <span class="mini-click-non">{{ trans('label.transactions') }} </span></a>
            <ul class="submenu-angle {{ $link == 'transactions' ? 'collapse in' : '' }}" aria-expanded="false">
               <li><a class="{{ $subMenu == 'transaction_list' ? 'active' : '' }}" title="{{ trans('label.transactions') }}" href="{{ route('admin_transactionr_list') }}"><span class="mini-sub-pro">{{ trans('label.list') }}</span></a></li>
            </ul>
         </li>
         <li class="{{ $link == 'news_letter' ? 'active' : '' }}">
            <a class="has-arrow" href="mailbox.html" aria-expanded="false"><i class="icon nalika-pie-chart icon-wrap"></i> <span class="mini-click-non">{{ trans('label.newsletter') }} </span></a>
            <ul class="submenu-angle {{ $link == 'news_letter' ? 'collapse in' : '' }}" aria-expanded="false">
               <li><a class="{{ $subMenu == 'newsletter_list' ? 'active' : '' }}" title="{{ trans('label.newsletter') }}" href="{{ route('add_update_news_letter') }}"><span class="mini-sub-pro">{{ trans('label.list') }}</span></a></li>
               <li><a title="Blog" href="blog.html"><span class="mini-sub-pro">{{ trans('label.subscribe_new') }}</span></a></li>
            </ul>
         </li>
         <li  class="{{ $link == 'contact_us' ? 'active' : '' }}">
            <a class="has-arrow" href="mailbox.html" aria-expanded="false"><i class="icon nalika-pie-chart icon-wrap"></i> <span class="mini-click-non">{{ trans('label.contact_us') }} </span></a>
            <ul class="submenu-angle {{ $link == 'contact_us' ? 'collapse in' : '' }}" aria-expanded="false">
               <li><a class="{{ $subMenu == 'list' ? 'active' : '' }}" title="{{ trans('label.contact_us') }}" href="{{ route('contact_us') }}"><span class="mini-sub-pro">{{ trans('label.list') }}</span></a></li>
            </ul>
         </li>
         <li id="removable" class="{{ $link == 'pages' ? 'active' : '' }}">
            <a class="has-arrow" href="#" aria-expanded="false"><i class="icon nalika-new-file icon-wrap"></i> <span class="mini-click-non">{{ trans('label.pages') }} </span></a>
            <ul class="submenu-angle {{ $link == 'pages' ? 'collapse in' : '' }}" aria-expanded="false">
               <li><a class="{{ $subMenu == 'sliders' ? 'active' : '' }}" title="{{ trans('label.sliders') }}" href="{{ route('sliders') }}"><span class="mini-sub-pro">{{ trans('label.sliders') }} </span></a></li>
               <li><a class="{{ $subMenu == 'footer' ? 'active' : '' }}" title="{{ trans('label.footer') }}" href="{{ route('add_update_footer') }}"><span class="mini-sub-pro">{{ trans('label.footer') }} </span></a></li>
               <li><a class="{{ $subMenu == 'footer_link' ? 'active' : '' }}" title="{{ trans('label.footer_links') }}" href="{{ route('footer_links') }}"><span class="mini-sub-pro">{{ trans('label.footer_links') }} </span></a></li>
               <li><a class="{{ $subMenu == 'terms' ? 'active' : '' }}"  title="{{ trans('label.term_&_condition') }}" href="{{ route('add_term_page') }}"><span class="mini-sub-pro">{{ trans('label.privacy_&_policy') }} </span></a></li>
                  <li><a class="{{ $subMenu == 'imprint' ? 'active' : '' }}"  title="{{ trans('label.privacy_&_policy') }}" href="{{ route('add_imprint_page') }}"><span class="mini-sub-pro">{{ trans('label.imprint') }} </span></a></li>
               <li><a class="{{ $subMenu == 'terms' ? 'active' : '' }}"  title="{{ trans('label.term_&_condition') }}" href="{{ route('add_agb_page') }}"><span class="mini-sub-pro">{{ trans('label.term_&_condition') }} </span></a></li>
               <li><a class="{{ $subMenu == 'about_us' ? 'active' : '' }}"  title="{{ trans('label.about_us') }}" href="{{ route('add_aboout_us_page') }}"><span class="mini-sub-pro">{{ trans('label.about_us') }} </span></a></li>
               <li><a class="{{ $subMenu == 'how_it_works' ? 'active' : '' }}" title="{{ trans('label.how_it_works') }}" href="{{ route('add_how_work_page') }}"><span class="mini-sub-pro">{{ trans('label.how_it_works') }} </span></a></li>
               <li><a class="{{ $subMenu == 'widgets' ? 'active' : '' }}" title="{{ trans('label.widget') }}" href="{{ route('widgets') }}"><span class="mini-sub-pro">{{ trans('label.widget') }} </span></a></li>
               <li><a class="{{ $subMenu == 'support_widgets' ? 'active' : '' }}" title="{{ trans('label.support_widget') }}" href="{{ route('support_widget') }}"><span class="mini-sub-pro">{{ trans('label.support_widget') }} </span></a></li>
               <li><a class="{{ $subMenu == 'seo_widgets' ? 'active' : '' }}" title="{{ trans('label.seo_widget') }}" href="{{ route('seo_widgets') }}"><span class="mini-sub-pro">{{ trans('label.seo_widget') }} </span></a></li>
            </ul>
         </li>
         <li class="{{ $link == 'faqs' ? 'active' : '' }}" id="removable">
            <a class="has-arrow" href="#" aria-expanded="false"><i class="icon nalika-new-file icon-wrap"></i> <span class="mini-click-non">{{ trans('label.faqs') }} </span></a>
            <ul class="submenu-angle {{ $link == 'faqs' ? 'collapse in' : '' }}" aria-expanded="false">
               <li><a class="{{ $subMenu == 'faq_list' ? 'active' : '' }}" title="{{ trans('label.faqs') }}" href="{{ route('faqs') }}"><span class="mini-sub-pro">{{ trans('label.list') }} </span></a></li>
               <li><a class="{{ $subMenu == 'faq_type_list' ? 'active' : '' }}" title="{{ trans('label.faq_type') }}" href="{{ route('faq_type') }}"> <span class="mini-sub-pro">{{ trans('label.faq_type') }} </span></a></li>
            </ul>
         </li>
         <li class="{{ $link == 'settings' ? 'active' : '' }}">
            <a class="has-arrow" href="#" aria-expanded="false"><i class="icon nalika-diamond icon-wrap"></i> <span class="mini-click-non">{{ trans('label.settings') }} </span></a>
            <ul class="submenu-angle {{ $link == 'manage_setting' ? 'collapse in' : '' }}" aria-expanded="false">
               <li><a title="{{ trans('label.settings') }}" href="{{ route('add_update_setting') }}"><span class="mini-sub-pro">{{ trans('label.manage') }} </span></a></li>
            </ul>
         </li>
         <li class="{{ $link == 'users' ? 'active' : '' }}">
            <a class="has-arrow" href="#" aria-expanded="false"><i class="icon nalika-mail icon-wrap"></i> <span class="mini-click-non">{{ trans('label.users_side') }} </span></a>
            <ul class="submenu-angle {{ $link == 'users' ? 'collapse in' : '' }}" aria-expanded="false">
               <li><a class="{{ $subMenu == 'user_list' ? 'active' : '' }}" title="{{ trans('label.users_side') }}" href="{{ route('users') }}"><span class="mini-sub-pro">{{ trans('label.manage') }} </span></a></li>
               <li><a class="{{ $subMenu == 'user_rank_list' ? 'active' : '' }}" title="{{ trans('label.user_ranking') }}" href="{{ route('user_rankings') }}"><span class="mini-sub-pro">{{ trans('label.user_ranking') }} </span></a></li>
            </ul>
         </li>
      </ul>
   </nav>
</div>