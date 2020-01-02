<!-- <div id="deleteModal" class="modal fade" role="dialog">
   <div class="modal-dialog">
     
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">{{ trans('label.delete') }}</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body">
            {{$products}}
        
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" style="background-color: #434c5e;padding: 6px 20px;
    color: #fff;border-radius: 4px;border: 1px solid #434c5e;text-align: center;">Close</button>
         </div>
     </div>
 </div>
</div> -->
<table>
   <tr>
      <th>{{ trans('label.image') }} </th>
      <th>{{ trans('label.product_title') }} </th>
      <th>{{ trans('label.product_brand') }} </th>
      <th>{{ trans('label.status') }} </th>
      <th>{{ trans('label.price') }} </th>
      <th>{{ trans('label.shiping_price') }} </th>
      <th>{{ trans('label.category') }} </th>
      <th>{{ trans('label.product_amount_categories') }} </th>
      <th>{{ trans('label.setting') }} </th>
   </tr>

   @if(count($products))
   @foreach($products as $product)
   <tr>

      @php
        $img='';
      if($product->feature_image_1 != null )
        $img = $product->feature_image_1;
      if($product->feature_image_2 != null )
        $img = $product->feature_image_2;
      if($product->feature_image_3 != null )
        $img = $product->feature_image_3;
      if($product->feature_image_4 != null )
        $img = $product->feature_image_4;
      @endphp
      @php
      
      @endphp

      <td>

         <img src="@if($img!='') {{ asset('/images/admin/products/').'/'.$product->id.'/'.$img }} @else {{ asset('images/admin/img/notification/4.jpg') }} @endif" class="rounded float-left img-1 prdctimg1" alt="" data-name="prdctimg1"  alt="" />

      </td>
      <td> {{ $product->product_title }}</td>
      <td> {{ $product->product_brand }}</td>
      <td>
        {!! $product->getProductStatusBadgeAttribute() !!}
      </td>
      <td>{{ $product->product_price ? CURRENCY_ICON.$product->product_price  : ''}}</td>
      <td>{{ $product->shipping_price ? CURRENCY_ICON.$product->shipping_price  : ''  }}</td>
      <td>{{ $product->category_id ? $product->category_name->name :'Pre-Defined' }}</td>
      <td>

     @php
     $counter = 0;
     $last = count($product->product_amount_categories);
     foreach($product->product_amount_categories as $product_amount_categories)
      {
        echo $product_amount_categories->name;
        ++$counter;
        if ($last!=$counter) 
         echo ", ";
      }
      //die();
     @endphp
   </td>
      <td>
         <button data-toggle="tooltip" title="Edit" class="pd-setting-ed">
          <a href="{{ route('admin_product_add', encrypt_decrypt('encrypt',$product->id))}}" > 
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
          </a>
          </button>
         <button data-toggle="tooltip" title="Trash" class="pd-setting-ed" id="product_delete_btn" onclick="product_delete({{$product->id}})"><i class="fa fa-remove " aria-hidden="true"></i></button>
      </td>
   </tr>

    @endforeach
        @else
          <tr><td colspan="8">No record found</td></tr>
      @endif
</table>


<div class="custom-pagination">
{{ $products->links('vendor.pagination.custom') }}
</div>
