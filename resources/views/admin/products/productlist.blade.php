@extends('layouts.adminapp')

@section('content')

@php
  $name = trans('label.product_list');
@endphp

@include('admin.breadcome', ['name' => $name])


<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap">
                    <h4>{{ $name }} </h4>
                    <div class="add-product">
                        <a href="{{ route('admin_product_add') }}">{{ trans('label.add_product') }}</a>
                    </div>
                     <div class="add-product">
                        <a class="import_product" href="{{ route('admin_product_import_page') }}">{{ trans('label.import') }}</a>
                    </div>
                    <div style="margin-bottom: 15px">
                    <form method="POST" id="product-search-form" class="form-inline" role="form">

                        <div class="form-group">
                            <input type="text" class="form-control product_string" name="product_title"  id="name" placeholder="{{ trans('label.search') }}" value="{{ $filter_string }}">
                        </div>
                        <div class="form-group">
                            {!! Form::select('status', [''=>trans('label.status_label'),'0'=>trans('label.draft'),'1'=>trans('label.active'),'2'=>trans('label.de_active')], $filter_status, ['class' => 'status_filter form-control pro-edt-select form-control-primary']) !!}
                        </div>
                        <div class="form-group">
                          {!! Form::select('category_id',array('' => trans('label.select_category')) + $categoryList,null,array("class" => "form-control category_id")) !!}
                        </div>
                        <button href="#" class="btn btn-primary basic-btn" disabled=""><b> {{trans('label.search')}} </b></button>
                        <a href="#" class="reset_all_filter"><b> {{trans('label.reset')}} </b></a>
                    </form>
                    </div>
                    <div class="table-responsive referral_table">
                        @include('admin.products.table')
                    </div>  
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('javascript')
<script src="{{ asset('modal_files/js/bootstrap.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
    
        $("select").on("change", function() {
             ajaxListTable();
        });
        $("#product-search-form").on("keyup", ".product_string",function () {
            searchword = $(this).val();
            if ((searchword.length) >= 3) {
              ajaxListTable();
            }
        });
        
        $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();
         var url = $(this).attr('href');  
         var data = $("#product-search-form").serialize();
            getProducts(url,data);
        });
        $('body').on('click', '.reset_all_filter', function(e) {
            e.preventDefault();
            $('.product_string').val('');
            $('.status_filter').val('');
            $('.category_id').val('');
            var url = "{{ route('admin_product_list') }}";  
            var data = '';
            getProducts(url,data);
        });
    
    }); 
    
    var ajaxReq = null;
    function ajaxListTable(){
        if (ajaxReq != null) ajaxReq.abort();
                ajaxReq = $.ajax({
                url:"{{ route('admin_product_list') }}",
                data:$("#product-search-form").serialize(),
                type:"GET",
                processData: false,
                contentType: false,
                dataType: "html",
                success:function(data){
                    $(".referral_table").html(data);

                },
                error:function(data){
                    $(".referral_table").html(data);
                }
         });
    }
    function getProducts(url,data) {
        $.ajax({
            url:url,
            type:"GET",
            data:data,
            processData: false,
            contentType: false,
            dataType: "html",
            success:function(data){
                console.log("asdfds");
                $(".referral_table").html(data);
            },
            error:function(data){
                $(".referral_table").html(data);
            }
        });
    }
    function product_delete(product_id){

        var confrm = confirm("{{ trans('message.are_you_sure_delete_product') }}");
        console.log(confrm);
        if(confrm == true)
        {
            $.ajax({
                url:"{{route('product-delete')}}",
                type:"POST",
                data:{id:product_id},
                // processData: false,
                // contentType: false,
                dataType: "json",
                success:function(data){
                   alert(data.message);
                   location.reload();
                },
            });
        }
    }
</script> 


@endsection