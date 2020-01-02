@extends('layouts.adminapp')

@section('content')

@php
$name = trans('label.attributes');
@endphp
@include('admin.breadcome', ['name' => $name])


<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            @if(session()->has('message.level'))
                <div class="alert alert-{{ session('message.level') }} alert-dismissible"> 
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {!! session('message.content') !!}
                </div>
            @endif
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap">
                    <h4>{{ $name }}</h4>
                    <div class="add-product">
                        <a href="{{ route('attribute_add') }}"> {{ trans('label.add_attribute') }}</a>
                    </div>
                    <table class="datatable">
                        <thead>
                                <tr>
                                    <th>{{ trans('label.serial_number_short_form') }} </th>
                                    <th> {{ trans('label.name') }}  </th>
                                    <th> {{ trans('label.category') }}   </th>
                                    <th>{{ trans('label.action') }}</th>
                                </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('javascript')
<script type="text/javascript">
jQuery(document).ready(function() {
     $.ajaxSetup({
        type:"POST",
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        beforeSend:function(){
            $('container-fluid').waitMe();
        },
        complete:function(){
            $('container-fluid').waitMe('hide');
        },
        error:function(error){
            $.each(error.responseJSON.errors,function(key,value){
                $('input[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
                $('select[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
            });

            jQuery('html, body').animate({
                scrollTop: jQuery(document).find('.error.active:first').parent().offset().top
            }, 500);

        }
    });
    
    jQuery('.datatable').DataTable({
      //  "dom": '<"top"i>rt<"bottom"flp><"clear">',
        dom: 'lfrtip',
        processing: true,
        serverSide: true,
        stateSave: true,
        bLengthChange: false,
        bInfo: false,
        bSort: false,
        iDisplayLength: 10, 
        language: {
          searchPlaceholder: "{{trans('label.search_by_name')}}",
        },
        ajax: "{{ route('datatable/getAttributeList') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'category', name: 'category'},
            {data: 'action', name: 'action',orderable: false, searchable: false},
        ]
    });
});
function attribute_delete(attribute_id){

    var confrm = confirm("{{ trans('message.are_you_sure_delete_attribute') }}");
    console.log(confrm);
    if(confrm == true)
    {
        $.ajax({
            url:"{{route('attribute-delete')}}",
            type:"POST",
            data:{id:attribute_id},
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
