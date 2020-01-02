@extends('layouts.adminapp')

@section('content')

@php
  $name = trans('label.user_ranking');
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
                    
                    <table class="datatable">
                        <thead>
                                <tr>
                                    <th>{{ trans('label.serial_number_short_form') }}</th>
                                    <th>{{ trans('label.name') }} </th>
                                    <th>{{ trans('label.email') }} </th>
                                    <th>{{ trans('label.role') }} </th>
                                    <th>{{ trans('label.login_count') }} </th>
                                    <th>{{ trans('label.setting') }} </th>
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
    
   
    $('.datatable').DataTable({
        processing: true,
        serverSide: true,
        bSort: false,
        ajax: "{{ route('datatable/getUserRankings') }}",
        language: {
          searchPlaceholder: "{{trans('label.search_by_email')}}",
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
            {data: 'full_name', name: 'full_name',orderable: false},
            {data: 'email', name: 'email',orderable: false},
            {data: 'role', name: 'role',orderable: false},
            {data: 'login_count', name: 'login_count',orderable: false},
            {data: 'setting', name: 'setting',orderable: false, searchable: false},
        ]
    });
    
});

</script>
@endsection
