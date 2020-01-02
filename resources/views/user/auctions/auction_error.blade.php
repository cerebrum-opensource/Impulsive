@extends('layouts.app')
@section('title')
 {{ trans('label.auctions') }}
@endsection
@section('content')

<div class="product-otr">
   <div class="container">
@if(\Session::has('error'))
    <div class="alert alert-danger">
      {{\Session::get('error')}}
    </div>
  @endif
</div>
</div>


@endsection
@section('javascript')
<script type="text/javascript">


  $.ajaxSetup({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
               // $('body').waitMe();
            },
            complete: function () {
              //  $('body').waitMe('hide');
            },
            error: function (error) {
                //console.log(error);
            }
        });
</script>
@endsection