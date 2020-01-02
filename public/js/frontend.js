jQuery(document).ready(function(){
jQuery(".close-pop, .pop-container").click(function(){
    jQuery(".pop-container").css({"display":"none"});
  });
  
  window.setTimeout(function(){ 
	// First check, if localStorage is supported.
	if (window.localStorage) {
		// Get the expiration date of the previous popup.
		var nextPopup = localStorage.getItem( 'nextNewsletter' );

		if (nextPopup > new Date()) {
			return;
		}

		// Store the expiration date of the current popup in localStorage.
		var expires = new Date();
		expires = expires.setHours(expires.getHours() + 72);

		localStorage.setItem( 'nextNewsletter', expires );
	}

      $(".pop-container").css({"display":"block"});
}, 1000);

	
	jQuery(document).on("click","#login_form input[type='submit']",function(){
		jQuery("#login_form").validate({
		    rules: {
		        email:{
		            required: true
		        },
		        password:{
		            required: true
		        }
		    }
		});
	});

	jQuery(document).on("click",".dropdownmenu",function(){
		jQuery(".dropdown-menu").show();
		jQuery(".dropdown-menu").css("display","block");
	});
	jQuery(document).on("click",".logout-btn",function(){
		jQuery("#logout-form").trigger('submit');
	});
	jQuery(document).on('click', function (e) {
		var l1 = jQuery(e.target).closest(".dropdown-menu").length;
		var l2 = jQuery(e.target).closest(".dropdownmenu").length;
	    if (l1 ===0 && l2 === 0 ) {
	        jQuery(".dropdown-menu").hide();
	        jQuery(".dropdown-menu").css("display","none");
	    }
	});
	
	jQuery(document).on("click",".more-text-button",function(){
		if(jQuery(".product-inner3a").hasClass("show-product-text")){
			jQuery(".product-inner3a").removeClass("show-product-text");
			jQuery(".more-text-button").html("Mehr lesen");

		}else{
			jQuery(".product-inner3a").addClass("show-product-text");
			jQuery(".more-text-button").html("Weniger");

		}
	});
	
	jQuery(".dropdownmenu .dropdown-toggle").on('click', function (e) {
		if(jQuery("#mySidenav").width() == 250){
			jQuery("#mySidenav").css({"width": "0"});
		}else{
			jQuery("#mySidenav").css({"width": "250"});
		};
	});
	
	if(!jQuery(".top-bar").hasClass("top-bar2")){
		jQuery(".open-nav").addClass("open-nav-prelogin");
	}
});
