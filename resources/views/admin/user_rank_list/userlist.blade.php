@extends('layouts.adminapp')

@section('content')

@php
  $name = trans('label.user_ranking');
@endphp
@include('admin.breadcome', ['name' => $name])
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">

<style type="text/css">
       
        table.dataTable tbody tr{
         background-color : #adadad !important;
        }

</style>

<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap">
                    <h4>{{ $name }} </h4>
                    
                    <div style="margin-bottom: 15px">
                    
                    </div>
                    <div class="table-responsive referral_table">
                        @include('admin.user_rank_list.table')
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
    
@endsection
@section('javascript')

<script type="text/javascript">
    $(document).ready( function () {
        $('#myTable').DataTable({
            "order": [[ 3, "desc" ]]
        } );
    } );

</script> 


@endsection