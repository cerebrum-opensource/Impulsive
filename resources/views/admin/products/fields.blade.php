@if(count($fields))
@foreach($fields as $field)
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
   @if($field->type == INPUT)
   <div class="review-content-section">
      <div class="input-group mg-b-pro-edt">
         <div class="form-group">
            <label >{{ $field->name }} </label>
            <input type="text" name="{{ $field->id }}" placeholder="{{ $field->place_holder }}" class="form-control">
         </div>
      </div>
   </div>
   @endif
   @if($field->type == DROPDOWN)
   <div class="review-content-section">
      <div class="input-group mg-b-pro-edt">

         <div class="form-group">
           <label >{{ $field->name }} </label>
           <select name="{{ $field->id }}" class=" pro-edt-select form-control-primary form-control">
               @foreach($field->other_value as $option)
               <option value="{{$option}}">{{ $option }}</option>
               @endforeach
            </select>
         </div>

      </div>
   </div>
   @endif
   @if($field->type == DATE)
   <div class="review-content-section">
      <div class="input-group mg-b-pro-edt">
         <div class="form-group">
             <label >{{ $field->name }} </label>
            <input type="text" name="{{ $field->id }}" placeholder="{{ $field->place_holder }}" class="form-control datePicker">
         </div>
      </div>
   </div>
   @endif
   @if($field->type == CHECKBOX)
   <div class="review-content-section">
      <div class="input-group mg-b-pro-edt">

         <label >{{ $field->name }} </label>
         <div class="radio">
           <label><input type="radio" name="{{ $field->id }}" placeholder="" value="yes" class=""> {{ trans('label.yes') }}</label>
         </div>
         <div class="radio">
           <label><input type="radio" name="{{ $field->id }}" placeholder="" value="no" class="">{{ trans('label.no') }}</label>
         </div>

      </div>
   </div>
   @endif
   @if($field->type == RADIO)
   <div class="review-content-section">
      <div class="input-group mg-b-pro-edt">

         <label >{{ $field->name }} </label>
         <div class="radio">
           <label><input type="radio" name="{{ $field->id }}" placeholder="" value="yes" class="">{{ trans('label.yes') }}</label>
         </div>
         <div class="radio">
           <label><input type="radio" name="{{ $field->id }}" placeholder="" value="no" class="">{{ trans('label.no') }}</label>
         </div>
         
      </div>
   </div>
   @endif
</div>
@endforeach
@endif
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
   @if(count($unassignedFields))
   <div class="form_builder" style="margin-top: 25px">
      <div class="row">
         <div class="col-sm-2">
            <nav class="nav-sidebar">
               <ul class="nav">
                  @foreach($unassignedFields as $unassignedField)
                  @if($unassignedField->type == INPUT)
                  <li class="form_bal_textfield">
                     <a href="javascript:;"><label>{{ $unassignedField->name }}</label></a>
                     <div class="review-content-section" style="display: none;">
                        <div class="input-group mg-b-pro-edt">
                           <input type="text" name="{{ $unassignedField->id }}" placeholder="{{ $unassignedField->place_holder }}" class="form-control">
                        </div>
                     </div>
                  </li>
                  @endif
                  @if($unassignedField->type == DROPDOWN)
                  <li class="form_bal_textfield">
                     <a href="javascript:;"><label>{{ $unassignedField->name }}</label></a>
                     <div class="review-content-section" style="display: none;">
                        <div class="input-group mg-b-pro-edt">
                           <select name="{{ $unassignedField->id }}"  class="form-control pro-edt-select form-control-primary">
                              @foreach($unassignedField->other_value as $option)
                              <option value="{{$option}}">{{ $option }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                  </li>
                  @endif
                  @if($unassignedField->type == DATE)
                  <li class="form_bal_textfield">
                     <a href="javascript:;"><label>{{ $unassignedField->name }}</label></a>
                     <div class="review-content-section" style="display: none;">
                        <div class="input-group mg-b-pro-edt">
                           <input type="text" name="{{ $unassignedField->id }}"  placeholder="{{ $unassignedField->place_holder }}" class="form-control datePicker">
                        </div>
                     </div>
                  </li>
                  @endif
                  @if($unassignedField->type == CHECKBOX)
                  <li class="form_bal_textfield">
                     <a href="javascript:;"><label>{{ $unassignedField->name }}</label></a>
                     <div class="review-content-section" style="display: none;">
                        <div class="input-group mg-b-pro-edt">
                           <input type="radio" name="{{ $unassignedField->id }}" value="yes"  placeholder="{{ $unassignedField->place_holder }}" class=""> {{ trans('label.yes') }}
                           <input type="radio" name="{{ $unassignedField->id }}" value="no" placeholder="{{ $unassignedField->place_holder }}" class=""> {{ trans('label.no') }}
                        </div>
                     </div>
                  </li>
                  @endif
                  @if($unassignedField->type == RADIO)
                  <li class="form_bal_textfield">
                     <a href="javascript:;"><label>{{ $unassignedField->name }}</label></a>
                     <div class="review-content-section" style="display: none;">
                        <div class="input-group mg-b-pro-edt">
                           <input type="radio" name="{{ $unassignedField->id }}"   value="yes" placeholder="" class=""> {{ trans('label.yes') }}
                           <input type="radio" name="{{ $unassignedField->id }}"  value="no" placeholder="" class=""> {{ trans('label.no') }}
                        </div>
                     </div>
                  </li>
                  @endif                                             
                  @endforeach
               </ul>
            </nav>
         </div>
         <div class="col-md-5 bal_builder">
            <div class="form_builder_area"></div>
         </div>
      </div>
   </div>
   @endif
</div>