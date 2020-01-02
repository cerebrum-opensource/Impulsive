jQuery(document).ready(function(){
		
	jQuery(document).on("click","#admin_login_form input[type='submit']",function(){
		jQuery("#admin_login_form").validate({
		    rules: {
		        email:{
		            required: true,
		            email: true
		        },
		        password:{
		            required: true
		        },
		    }
		});
	});


	$("#admindp").on("submit",function(e){
        e.preventDefault();
        var formData = new FormData(this)
        saveAdminDp(formData);
		return false;
    });

	jQuery(document).on("click",".admindp",function(){ jQuery("#admindp input[name='admindp']").trigger("click"); });
    jQuery("#admindp input[name='admindp']").change(function(){ readURL(this,'.admindp'); jQuery("#admindp").trigger('submit'); });
    function readURL(input,imgClass) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(imgClass).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    function saveAdminDp(formData){
    	var url = jQuery("meta[name='siteaddress']").attr('content');
    	jQuery.ajax({
	        url: url+"admin/saveAdminDP",
	        type: "POST",
	        data: formData,
	        contentType: false,
	        cache: false,
	        processData:false,
	        success: function(data){
	            
	        },error: function (jqXHR, status, err) {

	        },
	    });
	}

	jQuery(document).on("click",".delete-btn", function(){
        var token = jQuery(this).attr('del_token');
        console.log(token);
    	var url = jQuery("meta[name='siteaddress']").attr('content');
    	jQuery.ajax({
	        url: url+"admin/product/category/delete",
	        type: "GET",
	        data: {token:token},
	        success: function(data){
	        	window.location.href=window.location.href;
	        },error: function (jqXHR, status, err) {

	        },
	    });
    });

    jQuery(document).on("click",".img-1,.img-2,.img-3,.img-4",function(){ 
		var name = $(this).attr("data-name");
		jQuery("#product-form input[name='"+name+"']").trigger("click"); 
	});
    
    jQuery("#product-form input[type='file']").change(function(){
    	var name = $(this).attr("name");
    	readURL(this,"."+name); 
    	//jQuery("#productimageform").trigger('submit'); 
    });
         

});
