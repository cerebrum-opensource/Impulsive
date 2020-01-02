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
  $name = trans('label.import').' '.trans('label.product');
  
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
                                  <!--  <li><a href="#reviews"><i class="icon nalika-picture" aria-hidden="true"></i> Pictures</a></li> -->
                                </ul>
                                <div id="myTabContent" class="tab-content custom-product-edit">
                                    <div class="product-tab-list tab-pane fade active in" id="description">

                                        <form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ route('product-import-csv') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                                                  @csrf
                                   
                                                  @if ($errors->any())
                                                      <div class="alert alert-danger">
                                                          <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                                          <ul>
                                                              @foreach ($errors->all() as $error)
                                                                  <li>{{ $error }}</li>
                                                              @endforeach
                                                          </ul>
                                                      </div>
                                                  @endif
                                   
                                                  @if (Session::has('success'))
                                                      <div class="alert alert-success">
                                                          <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                                          <p>{{ Session::get('success') }}</p>
                                                      </div>
                                                  @endif
                                   
                                                  <input type="file" name="import_file" />
                                                  <button class="btn btn-primary">Import File</button>
                                              </form>
                                         
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


<script type="text/javascript">
    function saveform(formId){
  $('.model_box_save').attr("disabled", "disabled");
  $('span.error').hide().removeClass('active');
  var formData = new FormData($(formId)[0]);
  var data = {};
  var dataDelete = {};
  /*$("form#product-customform :input").each(function(){
        console.log($(this).data('value')); 
        console.log($(this).attr('value')); 

    });*/
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


</script>

@endsection