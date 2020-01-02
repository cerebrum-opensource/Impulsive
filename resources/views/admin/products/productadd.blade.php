@extends('layouts.adminapp')
@section('css')
 <style>    
    .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background-color: #959494;
    opacity: 1;
    }
    .form_bal_textfield{
        height: 100% !important;
    }
 </style> 
  <link rel="stylesheet" href="{{ asset('css/admin/form_builder.css') }}">
@endsection
@section('content')

@php
  $name = trans('label.add_new_product');
  if($product->id)
  $name = trans('label.edit_product');



//echo $product->product_price_label
@endphp
 
@include('admin.breadcome', ['name' => $name])

 <div class="single-product-tab-area mg-b-30">
            <!-- Single pro tab review Start-->
            <div class="single-pro-review-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="review-tab-pro-inner">
                                <ul id="myTab3" class="tab-review-design">
                                    <li class="active"><a href="#description"><i class="icon nalika-edit" aria-hidden="true"></i> {{ trans('label.product') }} </a></li>
                                    <li class="active">
                                        
                                    </li>
                                  <!--  <li><a href="#reviews"><i class="icon nalika-picture" aria-hidden="true"></i> Pictures</a></li> -->
                                </ul>

                                <form name="form_product_search" id = "form_product_search" >

                                            <input type="text" name="product_search" id="product_search" placeholder="Search by Article Number">
                                            <input type="submit" name="submit_product_search" value="Search">
                                        </form>
                                        <span id="form_result"></span><br/>
                                        <button id="auto_fill_data">Auto Fill Data</button>
                                <div id="myTabContent" class="tab-content custom-product-edit">
                                    <div class="product-tab-list tab-pane fade active in" id="description">
                                         {!! Form::model($product,['id' => 'product-form', 'files' => true]) !!}
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="review-content-section">
                                                    <div class="input-group mg-b-pro-edt">
                                                        <label> {{ trans('label.product_title') }} </label>
                                                         {!! Form::text('product_title',null,array('maxlength' => 100,'placeholder'=>trans('label.product_title'),'class'=>'form-control','id'=>'product_title')) !!}
                                                         <span class="error" style="color:red"></span>
                                                    </div>
                                                    <div class="input-group mg-b-pro-edt">
                                                        <label> {{ trans('label.regular_price') }} </label>
                                                       
                                                        {!! Form::text('product_price',null,array('maxlength' => 100,'placeholder'=>trans('label.regular_price'),'class'=>'form-control','id'=>'product_price')) !!}
                                                         <span class="error" style="color:red"></span>
                                                    </div>
                                                    <div class="input-group mg-b-pro-edt">
                                                        <label> {{ trans('label.article_number') }} </label>
                                                        {!! Form::text('article_number',null,array('maxlength' => 100,'placeholder'=>trans('label.article_number'),'class'=>'form-control','id'=>'article_number')) !!}
                                                         <span class="error" style="color:red"></span>
                                                    </div>

                                                    <div class="input-group mg-b-pro-edt">
                                                        <label> {{ trans('label.shiping_price') }} </label>
                                                         {!! Form::text('shipping_price',null,array('maxlength' => 100,'placeholder'=>trans('label.shiping_price'),'class'=>'form-control','id'=>'shipping_price')) !!}
                                                         <span class="error" style="color:red"></span>
                                                    </div>
                                                    <div class="mg-b-pro-edt">
                                                        <label> {{ trans('label.seller') }}  </label>
                                                         {!! Form::select('seller_id',$sellers,null,array("class" => "form-control",'id'=>'seller')) !!}
                                                         <span class="error" style="color:red"></span>
                                                    </div>
                                                     <div class="mg-b-pro-edt">
                                                        <label> {{ trans('label.show_seller') }}  </label>
                                                         {{ Form::checkbox('show_seller',null,null, array('id'=>'asap')) }}
                                                         <span class="error" style="color:red"></span>
                                                    </div>
                                                    <div class="mg-b-pro-edt">
                                                        <label>{{ trans('label.attachments') }} </label>
                                                         {!! Form::button(trans('label.upload_product_images'),array('placeholder'=>trans('label.upload_product_images'),'class'=>'btn btn-primary d-block', 'data-toggle'=>"collapse", 'data-target'=>"#collapseExample", 'aria-expanded'=>"false", 'aria-controls'=>"collapseExample")) !!}


                                                         <span class="error" id="image_valid" style="color:red"></span>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="review-content-section">
                                                    <div class="input-group mg-b-pro-edt">
                                                       <label>{{ trans('label.product_short_title') }}  </label>
                                                           {!! Form::text('products_short_title',null,array('maxlength' => 100,'placeholder'=>trans('label.product_short_title'),'class'=>'form-control')) !!}
                                                       <span class="error" style="color:red"></span>
                                                    </div>
                                                    <div class="input-group mg-b-pro-edt">
                                                       <label> {{ trans('label.product_brand') }} </label>
                                                        {!! Form::text('product_brand',null,array('maxlength' => 100,'placeholder'=> trans('label.product_brand'),'class'=>'form-control')) !!}
                                                        <span class="error" style="color:red"></span>
                                                    </div>
                                                     <div class="input-group mg-b-pro-edt">
                                                        <label> {{ trans('label.stock') }} </label>
                                                        {!! Form::text('stock',null,array('maxlength' => 100,'placeholder'=>trans('label.stock'),'class'=>'form-control','id'=>'product_stock')) !!}
                                                         <span class="error" style="color:red"></span>
                                                    </div>

                                                    <div class="input-group mg-b-pro-edt">
                                                       <label> {{ trans('label.tax') }} </label>
                                                        {!! Form::text('tax',null,array('maxlength' => 100,'placeholder'=>trans('label.tax'),'class'=>'form-control','readonly'=>true)) !!}
                                                        <span class="error" style="color:red"></span>
                                                    </div>
                                                    <div class="input-group mg-b-pro-edt">
                                                         <label> {{ trans('label.short_description') }} </label>
                                                         {!! Form::text('product_short_desc',null,array('maxlength' => 100,'placeholder'=>trans('label.short_description'),'class'=>'form-control',)) !!}
                                                         <span class="error" style="color:red"></span>
                                                    </div>
                                                    
                                                    <div class="input-group mg-b-pro-edt">
                                                        <label> {{ trans('label.product_category') }} </label>
                                                        @if($product->id)
                                                            {!! Form::select('category_id',array('' => trans('label.select_category')) + $categoryList,null,array("class" => "form-control category_id","id"=>"product_category")) !!}
                                                        @else 
                                                        {!! Form::select('category_id',array('' => trans('label.select_category')) + $categoryList,null,array("class" => "form-control category_id","id"=>"product_category")) !!}
                                                        @endif
                                                         <span class="error" style="color:red"></span>
                                                    </div>
                                                    <div class="input-group mg-b-pro-edt">
                                                        <label> {{ trans('label.product_amount_categories') }} </label><br/>
                                                         @foreach($afterplayList as $afterplay_id => $afterplay_category)
                                                            {!! Form::checkbox('afterplay_id[]', $afterplay_id) !!}
                                                            {{ $afterplay_category }}<br/>
                                                         @endforeach
                                                         <span class="error" style="color:red"></span>
                                                    </div>
                                                    <div class="mg-b-pro-edt">
                                                        <label> {{ trans('label.is_package') }}  </label>
                                                         {{ Form::checkbox('is_package',null,null, array('id'=>'is_package')) }}
                                                         <span class="error" style="color:red"></span>
                                                    </div>
                                                    <div class="mg-b-pro-edt number_of_bid">
                                                        <label> {{ trans('label.number_of_bid') }}  </label>
                                                         {{ Form::select('package_id', $number_of_bids,null,array('id' => 'package_id')) }}
                                                         <span class="error" style="color:red"></span>
                                                    </div>
                                                
                                                    @php 
                                                     // print_r($number_of_bids);
                                                    @endphp
                                                 
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                 
                                                    @if(($product->id) && ($product->feature_image_1 != null || $product->feature_image_2 != null || $product->feature_image_3 != null || $product->feature_image_4 != null) )
                                                    <div class="collapse show" id="collapseExample">
                                                    @else
                                                    <div class="collapse" id="collapseExample">
                                                    @endif

                                                    
                                                        <div class="card card-body product-images">
                                                                <input type="file" name="prdctimg1">
                                                                <input type="file" name="prdctimg2">
                                                                <input type="file" name="prdctimg3">
                                                                <input type="file" name="prdctimg4">
                                                            <a href="#" id="sortable">
                                                                <img src="@if($product->feature_image_1) {{ asset('/images/admin/products/').'/'.$product->id.'/'.$product->feature_image_1 }} @else {{ asset('images/admin/img/notification/4.jpg') }} @endif" class="rounded float-left img-1 prdctimg1" alt="" data-name="prdctimg1" data-feature_name="feature_image_1">
                                                                <img src="@if($product->feature_image_2) {{ asset('/images/admin/products/').'/'.$product->id.'/'.$product->feature_image_2 }} @else {{ asset('images/admin/img/notification/4.jpg') }} @endif" class="rounded float-left img-2 prdctimg2" alt="" data-name="prdctimg2" data-feature_name="feature_image_2">
                                                                <img src="@if($product->feature_image_3) {{ asset('/images/admin/products/').'/'.$product->id.'/'.$product->feature_image_3 }} @else {{ asset('images/admin/img/notification/4.jpg') }} @endif" class="rounded float-left img-3 prdctimg3" alt="" data-name="prdctimg3" data-feature_name="feature_image_3">
                                                                <img src="@if($product->feature_image_4) {{ asset('/images/admin/products/').'/'.$product->id.'/'.$product->feature_image_4 }} @else {{ asset('images/admin/img/notification/4.jpg') }} @endif" class="rounded float-left img-4 prdctimg4" alt="" data-name="prdctimg4" data-feature_name="feature_image_4">
                                                            </a>
                                                        </div>
                                                    </div>
                                            </div>

                                            
                                        </div>            
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="review-content-section">
                                                    <div class="input-group mg-b-pro-edt">
                                                        <!-- <span class="input-group-addon" style="vertical-align: top;"><i class="icon nalika-user" aria-hidden="true"></i></span> -->
                                                        <textarea placeholder="{{ trans('label.product_title') }} Description" name="product_long_desc" id='product_short_description' class="form-control" rows="8">{{ @$product->product_long_desc }}</textarea>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="" id='concern'>
                                                @if($product->id)
                                                     @include('admin.products.attributes')
                                                @endif
                                            </div>  
                                        </div>  
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="text-center custom-pro-edt-ds">
                                                   <button type="button" class="btn blue-btn btn-ctl-bt waves-effect waves-light m-r-10 model_box_save" onClick="javascript:saveform('#product-form')">{{ $product->id ? trans('label.update') : trans('label.save') }}
                                                    </button>
                                                    <input type='hidden' name='product_id' value='{{ $product->id ? $product->id : 0 }}' >

                                                    <button type="button" onClick="javascript:disCardForm('{{ route('admin_product_list') }}')" class="btn blue-btn btn-ctl-bt waves-effect waves-light">{{ trans('label.discard') }}
                                                        </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {!! Form::close() !!}
                                    </div>
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('javascript')


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('vendor/unisharp/laravel-ckeditor/adapters/jquery.js') }}"></script>
<script type="text/javascript">
$('#auto_fill_data').hide();
$('.number_of_bid').hide();
$('#is_package').on('change', function () {
    if (this.checked) {
        $('.number_of_bid').show();
    }else{
      $('.number_of_bid').hide();
    }
});
     $('textarea').ckeditor({
      allowedContent:true,
      filebrowserUploadUrl: ''
     });
    function saveform(formId){
  $('.model_box_save').attr("disabled", "disabled");
  
  $('span.error').hide().removeClass('active');

  for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
      }
  var formData = new FormData($(formId)[0]);
  var data = {};
  var dataDelete = {};
  /*$("form#product-customform :input").each(function(){
        console.log($(this).data('value')); 
        console.log($(this).attr('value')); 

    });*/
    // $(document).ready(function(){
        
    // });
    

    $(".form_builder :input").each(function(){
        
        if($(this).data('value')){
            if($(this).attr('type') == 'radio'){
                data[$(this).attr('name')] = $('input[name='+$(this).attr('name')+']:checked').val();
            }else{
                data[$(this).attr('name')] = $(this).val();
            }
            
        }
        else {
            dataDelete[$(this).attr('name')] = $(this).val();
        }
    });
    for (var key in dataDelete) {
      formData.delete(key);
    }
    for (var key in data) {
        formData.append(key, data[key]);
    }
  $.ajax({
    url:"{{ route('product-save') }}",
    data:formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success:function(data){
        $('.model_box_save').removeAttr("disabled");
        window.location.href="{{ route('admin_product_list') }}";   
    },
    error:function(error){
        $('.model_box_save').removeAttr("disabled");
       console.log(error.responseJSON);
        $.each(error.responseJSON.errors,function(key,value){
            
             $('input[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
             console.log($('select[name="'+key+'"]').parent());
             $('select[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
             if(key=="prdctimg1" || key=="prdctimg1" || key=="prdctimg1" || key=="prdctimg1"){
                $('#image_valid').html(value).addClass('active').show();
             }

        }); 
        
        jQuery('html, body').animate({
              scrollTop: jQuery(document).find('.error.active:first').parent().offset().top
          }, 500);    
    }
  });
}


$(".category_id").change(function() {
   
    var value = $('option:selected', this).val();
    @if($product->id)
    var r = confirm("Are you sure, you want change the category it will remove all the previous save fields?");
    if (r == true) {
                
                var proId = '{{ $product->category_id }}';
                var issame = 0;
              if(value == proId){
                issame = 1;
              }  

              $.ajax({  
                url:"{{ route('custom-fields') }}",
                type: "GET",
                dataType: "html",
                data: {id:value,product_id:'{{ $product->id }}',is_same:issame },
                    success:function(data){                     
                      var success = $.parseJSON(data);
                      $('#concern').html(success.html.fields);
                      datePickers();    
                      initDragDrop();    
                      
                   }
                });
        } else {
            $(".category_id").val("{{ $product->category_id }}")
            return true;
        }
    @else
        $.ajax({  
                url:"{{ route('custom-fields') }}",
                type: "GET",
                dataType: "html",
                data: {id:value},
                    success:function(data){                    
                      var success = $.parseJSON(data);
                      $('#concern').html(success.html.fields);
                      datePickers();    
                      initDragDrop();    
                      
                   }
                });

    @endif

    
    /* */
            
});


function datePickers(){
  $('.datePicker').datepicker({
    autoclose: true,
    format: 'mm-dd-yyyy',
    endDate: '-0d',
  });
}

function initDragDrop(){
    $(".nav-sidebar .form_bal_textfield").draggable({
        connectToSortable: ".form_builder_area"
    });
    
    $(".form_builder_area").sortable({
        cursor: 'move',
        placeholder: 'placeholder',
        start: function (e, ui) {
            ui.placeholder.height(ui.helper.outerHeight());
        },
        stop: function (ev, ui) {
            if(ui.item.find('.remove_bal_field').length < 1){
               ui.item.find('a').append('<button type="button" class="btn btn-primary btn-sm remove_bal_field pull-right"> Remove</button>');
            }
            ui.item.find('input').attr('data-value','1');
            ui.item.find('select').attr('data-value','1');
            ui.item.find('select').data('value','1');
            ui.item.find('input').data('value','1');
            ui.item.find('.review-content-section').show();
        }
    });

}

$(document).ready(function () {
   /* Code for sort and drag drop unassigned fields*/
   //$(".form_builder_area").disableSelection();
    $(document).on('click', '.remove_bal_field', function (e) {
      var html  = '<li class="form_bal_textfield">'+$(this).parent().parent().html() +'</li>';
      $('.nav-sidebar .nav').append(html);
      $('.nav-sidebar').find('.remove_bal_field').remove("button");
      $('.nav-sidebar').find('.review-content-section').hide();
      $('.nav-sidebar').find('input').attr('data-value','0');
      $('.nav-sidebar').find('select').attr('data-value','0');
      $(this).parent().parent().remove();
      $(".form_bal_textfield").draggable({
        connectToSortable: ".form_builder_area"
      });
    });
    datePickers();
    /*$( function() {
    $( "#sortable" ).sortable(
      {
        start: function(event, ui){
          // console.log(event);
          // console.log(ui);
        },
        change: function(event, ui){
          //console.log(ui);
        },
        update: function(event, ui){
          console.log(event);
          console.log(ui);
        },
      });
    $( "#sortable" ).disableSelection();
  } );*/

  $( function() {
      var old_position='';
      var old_dataname = '';
    $( "#sortable" ).sortable(
      {
        start: function(event, ui){
          //console.log(ui);
          old_position = ui.item.index();
          old_dataname = $(ui.item[0]).attr('data-feature_name');
          
        },
        stop: function(event, ui)
        {
          $(".ui-sortable-handle").removeClass (function (index, className) {
            return (className.match (/(^|\s)prdctimg\S+/g) || []).join(' ');
          });
          $(".ui-sortable-handle").removeClass (function (index, className) {
            return (className.match (/(^|\s)img-\S+/g) || []).join(' ');
          });

          $(".ui-sortable-handle").addClass(function(i) {
           return "prdctimg" + (i + 1) 

          });
          $(".ui-sortable-handle").addClass(function(i) {
           return "img-" + (i + 1) 
          });

        /*  $('.ui-sortable-handle').each(function(i) {
              var tt = 'product_image_'+i;
              var ttr = i+1;
              $(this).prop('id', tt);

          });*/


           $('.ui-sortable-handle').each(function(i) {
              
              var ttr = i+1;
              var name = 'prdctimg'+ttr;
              var feature_name = 'feature_image_'+ttr;
              
              $(this).attr('data-name', name);
              $(this).attr('data-feature_name', feature_name);
          });
           new_dataname = $(ui.item[0]).attr('data-feature_name');
           // console.log(old_dataname);
           // console.log(new_dataname);
            @php
              if($product->id){
            @endphp
                var prodct_id = @php echo $product->id; @endphp;
                $.ajax({
                  type: "POST",
                  url: "{{ route('product-image-dragdrop') }}",
                  data: {prod_id:prodct_id,source:old_dataname,destination:new_dataname},
                  success: function(data){
                    // console.log(data);
                  },
                  beforeSend:function(){
            //$('body').waitMe();
        },
        complete:function(){
          //  $('body').waitMe('hide');
        },
                });
            @php
              }
            @endphp
           /*AJAX */

          //new_dataname = $(ui.item).attr('data-name');
        }
      });
    $( "#sortable" ).disableSelection();
  } );
    
});

$("#form_product_search").on('submit',function(e)
{
    e.preventDefault();
    var article_number = $('#product_search').val();
    $.ajax({
        type: "GET",
        url: "{{ route('admin_product_search') }}",
        data: {article_number:$("#product_search").val()},
        success: function(data){
            // var article = $.parseJSON(data);
            if(data.data[0] != null){
            $('#form_result').html(
                              // data.data[0].id+
                              '<br>Article Number:'+data.data[0].article_number+
                              '<br>Article Name:'+data.data[0].article_name+
                              '<br>Article Description:'+data.data[0].article_description+
                              '<br>Article Price:'+data.data[0].article_price+
                              '<br>Article Manufacturer:'+data.data[0].article_manufacturer+
                              '<br>Article Product Group Key:'+data.data[0].article_productgroupkey+
                              '<br>Article Product Group:'+data.data[0].article_productgroup+
                              '<br>Article EAN:'+data.data[0].article_ean+
                              '<br>Article HBNR:'+data.data[0].article_hbnr+
                              '<br>Article Shipping Cost Text:'+data.data[0].article_shippingcosttext+
                              '<br>Article Amount:'+data.data[0].article_amount+
                              '<br>Article Payment In Advance:'+data.data[0].article_paymentinadvance+
                              '<br>Article Max Delivery Amount:'+data.data[0].article_maxdeliveryamount+
                              '<br>Article Energy Efficiency Class:'+data.data[0].article_energyefficiencyclass
                              );
            $('#auto_fill_data').show('fast');
            $("#auto_fill_data").click(function(){
                $("#product_title").val(data.data[0].article_name);
                $("#product_price").val(data.data[0].article_price);
                $("#article_number").val(data.data[0].article_number);
                $("#product_short_description").val(data.data[0].article_description);
                $("#product_stock").val(data.data[0].article_amount);
                $("#product_category").val(data.data[0].article_productgroup);
            });
          }else{
            $('#form_result').html("No results found");
            console.log(data);
          }
        },

    });
});
</script>

@endsection