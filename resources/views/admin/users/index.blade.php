@extends('layouts.adminapp')

@section('content')

@php
  $name = trans('label.users');
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
                    <h4>{{ trans('label.users') }}</h4>
                    <div class="form-group">
                        <select name="filter_status" id="filter_status" class="form-control" required>
                            <option value="">Status</option>
                            <option value="1">Completed</option>
                            <option value="0">Incomplete</option>
                        </select>
                    </div>
                    <button type="button" name="filter" id="filter" class="btn btn-info">Filter</button>
                    <!-- <button type="button" name="reset" id="reset" class="btn btn-default">Reset</button> -->
                    <table class="datatable" id="datatable">
                        <thead>
                                <tr>
                                    <th>{{ trans('label.serial_number_short_form') }}</th>
                                    <th>{{ trans('label.first_name') }} </th>
                                    <th>{{ trans('label.last_name') }} </th>
                                    <th>{{ trans('label.username') }} </th>
                                    <th>{{ trans('label.email') }} </th>
                                    <th>{{ trans('label.role') }} </th>
                                    <th>{{ trans('label.resend') }} </th>
                                    <th>{{ trans('label.status') }} </th>
                                    <th>{{ trans('label.profile_status') }} </th>
                                    <th>{{ trans('label.action') }} </th>
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
    
   fill_datatable();
    function fill_datatable(filter_status = ''){
        console.log("hello");
        console.log(filter_status);
           var dataTable = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                bSort: false,
                ajax:{
                    url: "{{ route('datatable/getUsers') }}",
                    data: function (d) {
                        d.filter_status = $('#filter_status').val();
                        // d.end_date = $('#end_date').val();
                    }
                },
                 //ajax: "{{ route('datatable/getUsers') }}",
                language: {
                  searchPlaceholder: "{{trans('label.search')}}",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
                    {data: 'first_name', name: 'first_name',orderable: false},
                    {data: 'last_name', name: 'last_name',orderable: false},
                    {data: 'username', name: 'username',orderable: false},
                    {data: 'email', name: 'email',orderable: false},
                    {data: 'role', name: 'role',orderable: false},
                    {data: 'resend', name: 'resend',orderable: false, searchable: false},
                    {data: 'status', name: 'status',orderable: false, searchable: false},
                    {data: 'profile_status', name: 'profile_status',orderable: false,},
                    {data: 'actions', name: 'actions',orderable: false, searchable: false},
                    {data: 'setting', name: 'setting',orderable: false, searchable: false},
                ]
            });
    }
});
 $('#filter').click(function(){

        // var filter_status = $('#filter_status').val();
        

        // if(filter_status != '' &&  filter_status != '')
        // {
        //     $('#datatable').DataTable().destroy();
        //     fill_datatable(filter_status);
        // }
        // else
        // {
        //     alert('Select filter option');
        // }

        $('#datatable').DataTable().draw(true);
    });
 // $('#reset').click(function(){

 //        $('#filter_status').val('');
 //        $('#datatable').DataTable().destroy();
 //        fill_datatable();
 //    });
 function user_delete(user_id){
    
    var confrm = confirm("{{ trans('message.are_you_sure_delete_user') }}");
    console.log(confrm);
    if(confrm == true)
    {
        $.ajax({
            url:"{{route('user-delete')}}",
            type:"POST",
            data:{id:user_id},
            dataType: "json",
            success:function(data){
                alert(data.message);
                location.reload();
            },
        });
    }
}
function changeStatus(model,action,id,text){
      /*bootbox.confirm({ 
        message: "Are you sure, you want to "+text+" this user?", 
        callback: function(result){  
          if (result) {
            $.ajax({
                url:"{{ route('change-user-status') }}",
                data:{id:id,status:action},
                success:function(data){
                    window.location.reload();
                }
            });
          }
        }
      })*/

        //var r = confirm("Are you sure, you want to "+text+" this user?");
        var r = confirm("{{ trans('message.are_you_sure_you_want_to') }} "+text+" {{ trans('message.this_user') }}");
        if (r == true) {
          $.ajax({
                url:"{{ route('change-user-status') }}",
                data:{id:id,status:action},
                success:function(data){
                    window.location.reload();
                }
            });
        } else {
         // txt = "You pressed Cancel!";
        }

}

function resendVerificationMail(id){
      
    $.ajax({
        url:"{{ route('resend-verification-email') }}",
        data:{id:id},
        success:function(data){
            window.location.reload();
        }
    });
        
}

</script>



@endsection
