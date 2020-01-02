<!DOCTYPE html
   PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title></title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   </head>
   <body style="margin: 0; padding:0; font-family: sans-serif; font-size: 14px;">
      <!-- main table -->
      <table style=" width: 700px; max-width: 100%; margin: 0 auto;" cellpadding="0"
         cellspacing="0" align="center">
         <tbody>
           
            @include('pdf.header')
            <!---------------------------- row two ------------------------------------>
            <tr>
               <td style="padding: 15px 40px 50px 40px;">
                  <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 12px;">
                     <tbody>
                        @include('pdf.header_row')
                        <!-- --2--- -->
                        <tr>
                           <td>
                              <table cellpadding="0" cellspacing="0" style="width: 100%; padding: 50px 0 15px 0; font-size: 12px;">
                                 <tbody>
                                    <tr>
                                       <td><b>{{ @$user->full_name}} </b></td>
                                    </tr>
                                    <tr>
                                       <td><b>{{ @$user->street }}  {{ @$user->house_number }} </b></td>
                                    </tr>
                                    <tr>
                                       <td><b>{{ @$user->city }} {{ @$user->postal_code }} {{ @$user->state }} </b></td>
                                    </tr>
                                    <tr>
                                       <td><b>{{ @$user->country }} </b></td>
                                       <td style="text-align: right;">{{ trans('pdf.Heilbronn') }} <b>{{ date('d.m.Y') }}</b></td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <!-- --------------3--- -->
                        <tr>
                           <td>
                              <h1 style="font-size:32px;">{{ trans('label.invoice_number') }} / {{ @$invoice_number }} </h1>
                           </td>
                        </tr>
                        <!-- ------------------4--------- -->
                        <tr>
                           <td style="    padding: 30px 0 50px  0;">
                              <table cellpadding="5" cellspacing="0" style="width: 100%; border:2px solid #c0c0c0;   font-size: 16px;    text-align: left;">
                                 <tr style=" background-color: #d6d6d6; border-bottom:2px solid #c0c0c0; ">
                                    <th style="    width: 10%;  vertical-align: baseline; border-right:2px solid #c0c0c0;">{{ trans('label.type') }}
                                    </th>
                                    <th style=" width: 25%; vertical-align: baseline; border-right:2px solid #c0c0c0;">
                                       {{ trans('label.name') }}
                                    </th>
                                    <th style="    width: 13%;  vertical-align: baseline; border-right:2px solid #c0c0c0;">
                                       {{ trans('label.invoice_number') }}
                                    </th>
                                    <th style="  vertical-align: baseline; border-right:2px solid #c0c0c0;">
                                       {{ trans('label.amount') }}
                                    </th>
                                    <th style="    width: 9%;  vertical-align: baseline; border-right:2px solid #c0c0c0;">
                                       {{ trans('label.tax_percent') }}
                                    </th>
                                    <th style="vertical-align: baseline;">{{ trans('label.total_amount') }} </th>
                                 </tr>
                                 <tr>
                                    <td style="vertical-align: baseline; border-right:2px solid #c0c0c0;">
                                       {{ $type }} 
                                    </td>
                                    <td style="vertical-align: baseline; border-right:2px solid #c0c0c0;">
                                       {{ @$package_name }} 
                                    </td>
                                    <td style="vertical-align: baseline; border-right:2px solid #c0c0c0;">
                                       {{ @$invoice_number }} 
                                    </td>
                                    <td style="vertical-align: baseline; border-right:2px solid #c0c0c0;">
                                       {{ price_reflect_format(@$package_price) }}{{ CURRENCY_ICON }}
                                    </td>
                                    <td style="vertical-align: baseline; border-right:2px solid #c0c0c0;">
                                       {{ price_reflect_format(@$tax_price) }}{{ CURRENCY_ICON }}
                                    </td>
                                    <td style="vertical-align: baseline; ">{{ price_reflect_format(@$total_price) }}{{ CURRENCY_ICON }} </td>
                                 </tr>
                              </table>
                           </td>
                        </tr>
                        <!-- -------------5 -->
                        <tr>
                           <td>
                              <table cellpadding="0" cellspacing="0" style="width: 100%; padding: 30px 0; font-size: 16px;">
                                 <tbody>
                                    <tr>
                                       <td style="width: 65%"></td>
                                       <td><b>{{ trans('label.total') }}</b></td>
                                       <td style="padding-left:5px;"><b>{{ price_reflect_format(@$total_price) }}{{ CURRENCY_ICON }}</b></td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <!-- -------------------6--------------- -->
                        <tr>
                           <td>
                              <table cellpadding="0" cellspacing="0" style="width: 100%; padding:10px 0 20px 0; font-size: 16px;">
                                 <tr>
                                    <td><b>{{ trans('label.warranty') }}: {{ trans('label.warranty_message') }}.</b>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td><b>{{ trans('label.terms_of_payment') }}: {{ trans('label.prepayment') }}. </b></td>
                                 </tr>
                              </table>
                           </td>
                        </tr>
                        <!-- -------------------7--------------- -->
                        <tr>
                           <td>
                              <h3 style="text-decoration: underline; font-size: 16px;">Payment method and shipping</h3>
                              <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 16px;">
                                 <tr>
                                    <td style="width: 15%;"><b>{{ trans('label.payment_method') }} </b></td>
                                    <td style="width: 25%;"><b>{{ @$payment_type }} </b></td>
                                 </tr>
                                 <tr>
                                    <td style="width: 15%;"><b>{{ trans('label.payment_status') }} </b></td>
                                    <td style="width: 25%;"><b>{{ @$status }} </b></td>
                                 </tr>
                                 <tr>
                                    <td style="width: 15%;"><b>{{ trans('label.order_date') }} </b></td>
                                    <td style="width: 25%;"><b>{{ @$date }} </b></td>
                                 </tr>
                              </table>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
           @include('pdf.footer')
         </tbody>
      </table>
   </body>
</html>