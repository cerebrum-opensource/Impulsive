<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true]);

App::setLocale('de');
////setlocale(LC_MONETARY, 'en_US.UTF-8');

/* login routes */
Route::group(['middleware' => 'App\Http\Middleware\RedirectIfAuthenticated'], function(){
    Route::any('login', 'Auth\LoginController@loginForm')->name('login');
    Route::get('admin/login', 'Admin\AdminController@loginForm')->name('adminlogin');
});

Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

Route::get('/admin/logout', '\App\Http\Controllers\Auth\LoginController@adminLogout')->name('admin-logout');
//Route::get('password-reset', 'PasswordController@showForm');
/* customer routes */

/*Route::middleware(['auth', 'isUser'])->group(function () {
//Route::group(['middleware' => 'App\Http\Middleware\UsersMiddleware'], function(){
    Route::get('dashboard', 'HomepageController@dashboard')->name('dashboard');
});*/

Route::middleware(['auth', 'isUser'])->namespace('User')->group(function () {

    Route::get('dashboard', 'UserController@dashboard')->name('dashboard');
    Route::get('edit_profile', 'UserController@getUncompleteProfile')->name('user_profile');
    Route::post('competeProfile', 'UserController@postCompleteProfile')->name('user_profile_complete');
    Route::post('updateProfile', 'UserController@postProfileUpdate')->name('user_profile_update');
    Route::get('profile', 'UserController@getEditProfile')->name('edit_profile');
    Route::get('packages', 'UserController@getPackagePage')->name('buy_package_list');
    Route::post('save_voucher', 'UserController@postVoucher')->name('save_voucher');

    Route::get('packages/tab', 'UserController@getloadTab')->name('package-tab');
    Route::post('packages/save', 'UserController@postPurchasepackage')->name('package-add');
    Route::post('packages/payment', 'UserController@postPayment')->name('package-payment');
    Route::post('changepassword', 'UserController@changePassword')->name('change-password');
    Route::post('deleteAccpunt', 'UserController@deleteAccount')->name('delete-account');
    Route::get('profile_detail', 'UserController@getProfileDetail')->name('profile-detail');
    Route::get('invite_friend', 'UserController@getFriendInvitepage')->name('invite-friend');
    Route::get('recall_list', 'UserController@getRecallList')->name('recall_list');
    Route::get('wish_list', 'UserController@getWishList')->name('wish_list');
    Route::get('redeem_voucher', 'UserController@getVoucher')->name('redeem_voucher');
    Route::post('invite_send', 'UserController@postFriendInvite')->name('send-invite-friend');
    Route::get('win_list', 'UserController@getWinList')->name('win_list');
    Route::post('product/payment', 'UserController@postProductPayment')->name('product-payment');
    Route::post('product/purchase', 'UserController@postLossProductPayment')->name('product-purchase');


    Route::post('product/direct_purchase', 'UserController@postDirectProductPayment')->name('direct_purchase_product'); // for direct purchase product
});


/* admin routes*/

/*
== Super Admin's Routes
*/
Route::post('/admin/login', 'Auth\LoginController@login')->name('admin-login');
//admin logout
Route::get('/admin/logout', '\App\Http\Controllers\Auth\LoginController@adminLogout')->name('admin-logout');

Route::prefix('admin')->middleware(['auth', 'isAdmin'])->namespace('Admin')->group(function () {

//Route::group(['middleware' => 'App\Http\Middleware\AdminMiddleware'], function(){
    Route::get('dashboard', 'AdminController@dashboard')->name('admin_dashboard');
    Route::post('saveAdminDP', 'AdminController@saveAdminDP')->name('saveAdminDP');

    /* Product controller */
    Route::get('product/list', 'ProductController@productList')->name('admin_product_list');
    Route::get('product/add/{id?}', 'ProductController@productAdd')->name('admin_product_add');
    Route::post('product/add', 'ProductController@saveProduct')->name('product-save');
    Route::post('product/delete', 'ProductController@productDelete')->name('product-delete');
    Route::post('product/img_dragdrop', 'ProductController@imageDragDrop')->name('product-image-dragdrop');
    Route::get('datatable/getProductList', 'ProductController@productListDatatable')->name('datatable/getProductList');

    Route::get('product/category', 'CategoryController@productCategory')->name('admin_product_category');
    Route::get('product/product_amount_categories', 'CategoryController@productAmountCategory')->name('admin_product_amount_categories');
    Route::get('product/category/add/{id?}', 'CategoryController@addCategory')->name('admin_category_add');
    Route::get('product/product_amount_category/add/{id?}', 'CategoryController@addProductAmount')->name('admin_product_amount_category_add');
    Route::post('product/category/add', 'CategoryController@saveCategory')->name('category-add');
    Route::post('product/category/delete', 'CategoryController@categoryDelete')->name('category-delete');
    Route::post('product/product_amount_category/add', 'CategoryController@saveProductAmount')->name('product-amount-add');
    Route::get('datatable/getCategoryList', 'CategoryController@categoryList')->name('datatable/getCategoryList');
    Route::get('datatable/getAmountCategoryList', 'CategoryController@amountCategoryList')->name('datatable/getAmountCategoryList');
    Route::get('product/category/delete', 'CategoryController@deleteCategory')->name('admin_category_delete');

    Route::get('product/custom_fields', 'ProductController@getCustomField')->name('custom-fields');

    /*  Route for attributes for product*/
    Route::get('product/attributes', 'AttributeController@list')->name('attribute_list');
    Route::get('product/attributes/add/{id?}', 'AttributeController@getAdd')->name('attribute_add');
    Route::post('product/attributes/add', 'AttributeController@postSave')->name('attribute_save');
    Route::post('product/attributes/delete', 'AttributeController@attributeDelete')->name('attribute-delete');
    Route::get('datatable/getAttributeList', 'AttributeController@attributeList')->name('datatable/getAttributeList');

    Route::get('product/import', 'ProductController@productImportPage')->name('admin_product_import_page');
    Route::post('product/import', 'ProductController@importProduct')->name('product-import');
    Route::get('product/import_csv', 'ProductController@importProductCSV')->name('product-import-csv');
    Route::get('product/update_csv', 'ProductController@insertCSVUpdate')->name('product-update-csv');
    //Route::post('product/import_csv', 'ProductController@importProduct')->name('product-import-csv');

    /* Route for user */
    Route::get('users', 'AdminController@getUserList')->name('users');
    Route::post('change-status', 'AdminController@postChangeStatus')->name('change-user-status');
    Route::get('users/edit/{id?}', 'AdminController@getUserProfile')->name('edit_user');
    Route::get('users/detail/{id}', 'AdminController@getUserProfileDetail')->name('user_detail');
    Route::post('users/update', 'AdminController@postUpdateProfile')->name('update_user');
    Route::post('users/delete', 'AdminController@userDelete')->name('user-delete');
    Route::get('datatable/getUsers', 'AdminController@getUsers')->name('datatable/getUsers');

    Route::get('user_rankings', 'AdminController@getUserRankingList')->name('user_rankings');
    Route::get('datatable/getUserRankings', 'AdminController@getUserRankings')->name('datatable/getUserRankings');
    /* Route for suppliers */
    Route::get('suppliers/create/{id?}', 'AdminController@getCreateEdit')->name('add_user');
    Route::post('suppliers/create', 'AdminController@postCreate')->name('user-add');
    Route::post('suppliers/delete', 'AdminController@supplierDelete')->name('supplier-delete');
    Route::get('suppliers', 'AdminController@getSupplierList')->name('suppliers');
    Route::get('supplierList', 'AdminController@getSuppliers')->name('getSupplierList');

    /* Route for packages */
    Route::get('packages/create/{id?}', 'PackageController@getCreateEdit')->name('add_package');
    Route::post('packages/create', 'PackageController@postCreate')->name('package_save');
    Route::get('packages', 'PackageController@getPackageList')->name('packages');
    Route::post('packages/delete', 'PackageController@packageDelete')->name('package-delete');
    Route::get('datatable/getPackages', 'PackageController@getPackages')->name('datatable/getPackages');
    Route::post('change_package_status', 'PackageController@postChangeStatus')->name('change-package-status');

    /* Route for Promotional packages */
    Route::get('promo_packages/create/{id?}', 'PackageController@getPromoCreateEdit')->name('add_promo_package');
    Route::post('promo_packages/create', 'PackageController@postPromoCreate')->name('promo_package_save');
    Route::get('promo_packages', 'PackageController@getPromoPackageList')->name('promo_packages');
    Route::post('promo_packages/delete', 'PackageController@promoPackageDelete')->name('promo-package-delete');
    Route::get('datatable/getPromoPackages', 'PackageController@getPromoPackages')->name('datatable/getPromoPackages');

    Route::post('change_promo_package_status', 'PackageController@postPromoChangeStatus')->name('change-promo-package-status');


    /* Route for auctions */
    Route::get('auctions/create/{id?}', 'AuctionController@getCreateEdit')->name('add_auction');
    Route::get('auctions/edit/{id?}', 'AuctionController@getQueueAuctionEdit')->name('edit_auction');
    Route::post('auctions/create', 'AuctionController@postCreate')->name('auction_update');
    Route::get('auctions', 'AuctionController@getAuctionList')->name('auctions');
    Route::get('auctionList', 'AuctionController@getAuctions')->name('getAuctionList');
    Route::post('auction/queue_auction_delete', 'AuctionController@queueAuctionDelete')->name('queue-auction-delete');
    Route::get('live_auctions', 'AuctionController@getLiveAuctionList')->name('live_auctions');
    Route::get('liveAuctionList', 'AuctionController@getLiveAuctions')->name('getLiveAuctionList');
    Route::get('auctions/dashboard', 'AuctionController@getDashBoard')->name('auctions_dashboard');


    /* Queue Auction List*/
    Route::get('queue_auctions', 'AuctionController@getQueueAuctions')->name('queue_auctions');

    /* Active */

    Route::get('active_auctions', 'AuctionController@getActiveAuctionList')->name('active_auctions');
    Route::get('activeAuctionList', 'AuctionController@getActiveAuctions')->name('getActiveAuctions');

     /* Deactive */

    Route::get('deactive_auctions', 'AuctionController@getDeActiveAuctionList')->name('deactive_auctions');
    Route::get('deactiveAuctionList', 'AuctionController@getDeActiveAuctions')->name('getDeActiveAuctions');
    Route::any('deactiveAuctionList/export', 'AuctionController@deactiveAuctionExport')->name('admin_deactiveAuction_export');
    /* Planned */

    Route::get('planned_auctions', 'AuctionController@getPlannedAuctionList')->name('planned_auctions');
    Route::get('plannedAuctionList', 'AuctionController@getPlannedAuctions')->name('getPlannedAuctions');


    Route::post('update_priority', 'AuctionController@postUpdatePriority')->name('update_priority');
    Route::get('cronLogic', 'AuctionController@cronLogic')->name('cronLogic');
    Route::get('auctions/{id}/detail', 'AuctionController@getAuctionDetail')->name('auction_detail');
    Route::any('auctions/product/{id}/export', 'AuctionController@auctionProductExport')->name('admin_auction_product_export');


    /* Route for faqs */
    Route::get('faqs/create/{id?}', 'FaqController@getCreateEdit')->name('add_faq');
    Route::post('faqs/create', 'FaqController@postCreate')->name('faq-add');
    Route::get('faqs', 'FaqController@getFaqs')->name('faqs');
    Route::post('faqs/delete', 'FaqController@faqDelete')->name('faq-delete');
    Route::get('faqList', 'FaqController@getFaqList')->name('getFaqList');
    Route::post('change_faq_status', 'FaqController@postChangeStatus')->name('change-faq-status');

     /* Route for faqs */
    Route::get('faq_type/create/{id?}', 'FaqController@getFaqTypeCreateEdit')->name('add_faq_type');
    Route::post('faq_type/create', 'FaqController@postFaqTypeCreate')->name('faq-type-add');
    Route::get('faq_type', 'FaqController@getFaqTypes')->name('faq_type');
    Route::get('faqTypeList', 'FaqController@getFaqTypeList')->name('getFaqTypeList');
    Route::post('change_faq_type_status', 'FaqController@postChangeStatusFaqType')->name('change-faq-type-status');

    Route::get('contact_us', 'QueryController@getContactUs')->name('contact_us');
    Route::get('contactUsList', 'QueryController@getcontactUsList')->name('getcontactUsList');
    Route::post('contact_us/reply_mail', 'QueryController@replyMail')->name('reply_mail');
    Route::get('contact_us/view/{id}', 'QueryController@getcontactUsDetail')->name('contact_us_view');

     /* Route for page sliders */
    Route::get('sliders/create/{id?}', 'StaticContentController@getCreateEditSlider')->name('add_slider');
    Route::post('sliders/create', 'StaticContentController@postCreateSlider')->name('slider-add');
    Route::get('sliders', 'StaticContentController@getSliders')->name('sliders');
    Route::post('sliders/delete', 'StaticContentController@sliderDelete')->name('slider-delete');
    Route::get('sliderList', 'StaticContentController@getSliderList')->name('getSliderList');
    Route::post('change_slider_status', 'StaticContentController@postChangeStatusSlider')->name('change-slider-status');

    /* Route for page */

    Route::get('pages/terms_and_conditions', 'StaticContentController@getTermPage')->name('add_term_page');

    Route::post('pages/terms_and_conditions/create', 'StaticContentController@postCreateTermPage')->name('term-page-add');


    /* Route for page */

    Route::get('pages/agb_page', 'StaticContentController@getAGBPage')->name('add_agb_page');

    Route::post('pages/agb_page/create', 'StaticContentController@postAGBPage')->name('term-agb-add');


     /* Route for page */

    Route::get('pages/imprint', 'StaticContentController@getImprintPage')->name('add_imprint_page');

    Route::post('pages/imprint/create', 'StaticContentController@postCreateImprintPage')->name('imprint-page-add');

      /* Route for page */

    Route::get('pages/about_us', 'StaticContentController@getAboutUsPage')->name('add_aboout_us_page');

    Route::post('pages/about_us/create', 'StaticContentController@postCreateAboutPage')->name('about_us-page-add');


       /* Route for page */

    Route::get('pages/how_it_works', 'StaticContentController@getHowWorkPage')->name('add_how_work_page');
    Route::get('product/search/{id?}', 'ProductController@productSearch')->name('admin_product_search');

    Route::post('pages/how_it_works/create', 'StaticContentController@postCreateHowWorkPage')->name('how_work-page-add');
    /* Route for footer */

    Route::get('footer', 'StaticContentController@getFooterPage')->name('add_update_footer');

    Route::post('footer/create', 'StaticContentController@postCreateFooter')->name('footer-add');

    Route::get('footer_links/create/{id?}', 'StaticContentController@getFooterLinkCreateEdit')->name('add_footer_link');
    Route::post('footer_links/create', 'StaticContentController@postFooterLinkCreate')->name('footer_link-add');
    Route::get('footer_links', 'StaticContentController@getFooterLinkPage')->name('footer_links');
    Route::get('footerLinkList', 'StaticContentController@getFooterLinkList')->name('getFooterLinkList');
    Route::post('change_footer_link_status', 'StaticContentController@postChangeStatusFooterLink')->name('change-footer-link-status');

    // widget routes

    Route::get('widgets/create/{id?}', 'StaticContentController@getWidgetCreateEdit')->name('add_widget');
    Route::post('widgets/create', 'StaticContentController@postWidgetCreate')->name('widget-add');
    Route::get('widgets', 'StaticContentController@getWidgetPage')->name('widgets');
    Route::get('widgetsList', 'StaticContentController@getWidgetList')->name('getWidgetList');
    Route::post('change_widget_status', 'StaticContentController@postChangeStatusWidget')->name('change-widget-status');

    // support widget routes
    Route::get('support_widget/create/{id?}', 'StaticContentController@getSupportWidgetCreateEdit')->name('add_support_widget');
    Route::post('support_widget/create', 'StaticContentController@postSupportWidgetCreate')->name('support_widget-add');
    Route::get('support_widget', 'StaticContentController@getSupportWidgetPage')->name('support_widget');
    Route::get('supportWidgetList', 'StaticContentController@getSupportWidgetList')->name('getSupportWidgetList');

    // support widget routes
    Route::get('seo_widgets/create/{id?}', 'StaticContentController@getSeoWidgetCreateEdit')->name('add_seo_widget');
    Route::post('seo_widgets/create', 'StaticContentController@postSeoWidgetCreate')->name('seo_widget-add');
    Route::get('seo_widgets', 'StaticContentController@getSeoWidgetPage')->name('seo_widgets');
    Route::get('seoWidgetList', 'StaticContentController@getSeoWidgetList')->name('getSeoWidgetList');


    // newsletter route
    Route::get('news_letter', 'NewsLetterController@getNewsLetterPage')->name('add_update_news_letter');
    Route::post('news_letter/create', 'NewsLetterController@postNewsLetterCreate')->name('news_letter-add');


    // order route
    Route::get('order/list', 'TransactionController@orderList')->name('admin_order_list');
    Route::get('order/view/{id}', 'TransactionController@getOrderDetail')->name('order_view');
    Route::any('order/export', 'TransactionController@ordereExport')->name('admin_order_export');
     // order route
    Route::get('transaction/list', 'TransactionController@transactionList')->name('admin_transactionr_list');
    Route::get('transaction/view/{id}', 'TransactionController@getTransactionDetail')->name('transaction_view');
    Route::get('transaction/export', 'TransactionController@transactionExport')->name('admin_transaction_export');
    Route::get('transaction/invoice_export', 'TransactionController@transactionInvoiceExport')->name('admin_transaction_invoice_export');
    Route::get('transaction/detail_invoice_export/{id}', 'TransactionController@transactionDetailInvoiceExport')->name('admin_transaction_detail_invoice_export');

//});

    Route::get('setting', 'SettingController@getSettingPage')->name('add_update_setting');

    Route::post('setting/update', 'SettingController@postUpdateSetting')->name('setting_update');

    /* Delete routs for all*/
    Route::post('common/delete', 'AdminController@deleteById')->name('common_delete');
});

/* logout routes */
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::post('resend-verification-email', 'Auth\VerificationController@resendLink')->name('resend-verification-email');
Route::get('email/update/{id}', 'Auth\VerificationController@updateEmail')->name('email.update');


// route for check status of the payment
Route::get('status', 'PaymentController@getPaymentStatus');
Route::get('klarna_status', 'PaymentController@getKlarnaPaymentStatus')->name('klarna_status');

Route::get('product_status', 'PaymentController@getProductPaymentStatus')->name('product_status');
Route::get('product_klarna_status', 'PaymentController@getProductKlarnaPaymentStatus')->name('product_klarna_status');
Route::get('ipn', 'PaymentController@getIpn');

Route::post('get_product_list', 'Admin\ProductController@postProductListData')->name('product_list_auto_complete');


//Route::any('softor_status', 'PaymentController@getSoftorPaymentStatus')->name('softor_status');
Route::any('sofort_status_notify', 'PaymentController@getSofortPaymentNotificationStatus')->name('sofort_status_notify');


Route::any('sofort_status/sucess', 'PaymentController@getSofortSuccess')->name('sofort_status');
Route::any('sofort_status/abort', 'PaymentController@getSofortAbort')->name('sofort_status_abort');


Route::any('product_sofort_status', 'PaymentController@getProductSofortPaymentNotificationStatus')->name('product_sofort_status_notify');
Route::any('product_sofort_status/sucess', 'PaymentController@getProductSofortSuccess')->name('product_sofort_status');
Route::any('product_sofort_status/abort', 'PaymentController@getProductSofortAbort')->name('product_sofort_abort');
/* Start Routes for auction working in frontend */

/*Route::post('auctions', 'AuctionController@postAuctions')->name('frontend_auctions');
Route::get('check_login', 'HomepageController@getCheckLogin')->name('frontend_login_check');
Route::post('bid_on_auction', 'AuctionController@bidOnAuction')->name('frontend_bid_on_auction');*/


Route::middleware(['isUser'])->group(function () {
    Route::get('/', 'HomepageController@view')->name('homepage');
    Route::get('check_login', 'HomepageController@getCheckLogin')->name('frontend_login_check');
    Route::get('check_login_overview', 'HomepageController@getCheckLoginOverview')->name('frontend_login_check_overview');
    Route::get('auctions/{id}/detail', 'AuctionController@getAuctionDetail')->name('frontend_auction_detail');
    Route::get('user_activity', 'AuctionController@getUserActivityDetail')->name('user_activity_detail');
    /* Live auctions */
    Route::post('live_auctions', 'AuctionController@postAuctionsHtml')->name('frontend_auctions_html');
    Route::post('setup_bid_agent', 'AuctionController@postSetupBidAgent')->name('frontend_setup_bid_agent');

    Route::post('auctions_ajax', 'AuctionController@postAuctions')->name('frontend_auctions');

    Route::post('bid_on_auction', 'AuctionController@bidOnAuction')->name('frontend_bid_on_auction');

    // add to recall
    Route::post('add_to_recall', 'AuctionController@addToRecall')->name('frontend_add_to_recall');
    Route::post('add_to_wishlist', 'AuctionController@addToWishList')->name('frontend_add_to_wishlist');

    Route::get('auktionen', 'AuctionController@getLiveAuctions')->name('frontend_live_auctions');
    Route::get('kommende-deals', 'AuctionController@getComingAuctions')->name('frontend_coming_auctions');

    // add query
    Route::post('add_to_query', 'PagesController@addToQuery')->name('frontend_add_to_query');

    Route::get('auction_overview', 'AuctionController@getAuctionOverview')->name('auction_overview');
});



Route::get('run_bid_agent', 'AuctionController@runBidAgent')->name('run_bid_agent');

Route::get('run_auctions', 'AuctionController@auctionCountDown')->name('run_auctions');

Route::get('auction_countdown', 'AuctionController@auctionCountDownDecrese')->name('auction_countdown');

Route::get('generate','PagesController@generatePDF');

Route::get('faq','PagesController@getFaqsPage')->name('page_faq');
//Route::get('pages/{page}', 'PagesController')->name('pages_route');
Route::get('{page}', 'PagesController')->where('page', 'kontakt|ueber-uns|how_it_works')->name('pages_route');

Route::get('paypal_plus', 'PaymentController@getPayPalPlus');
Route::get('demo_ctrl','DemoController@getCURL');
Route::get('ekomi_test','DemoController@ekomiTest');
Route::get('one_second_logic','DemoController@lastSecondLogic');

Route::get('datenschutz', 'PagesController@getTermPages');
Route::get('impressum', 'PagesController@getImprintPage');
Route::get('gewinnspiel', 'PagesController@getContestPage');
Route::get('agb', 'PagesController@getAGBPage');

Route::get('product/{id}/detail','AuctionController@getProductDetail')->name('get_product_detail');

Route::get('breakTimeLogic','DemoController@getLogic');

Auth::routes();


/*Route::group([‘middleware’ => ‘App\Http\Middleware\AdminMiddleware’], function(){
    Route::match([‘get’, ‘post’], ‘/adminOnlyPage/’, ‘HomeController@admin’);
});*/


/*Route::group(array('namespace' => 'Admin', 'middleware' => 'auth'), function () {
    Route::post('Setting/addDefaultSettings', 'DefaultSettingController@addDefaultSettings')->name('addDefaultSettings');
});*/

Route::get('loss_auction_error', 'AuctionController@getUserLossAuctionError');

Route::middleware(['loss_auction_middleware'])->group(function () {

 Route::get('loss_auction/{token}', 'AuctionController@getUserLossAuction')->name('frontend_loss_auction');

});



// Route::get('ref/{upviral}', function (){
//    return redirect('gewinnspiel/?ref_id=' . request('upviral'));
// });

// Route::get('register-welcome', function (){
//     return view('user.after_register');
// });

