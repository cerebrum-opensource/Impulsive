<!-- ----------------footer------------------- -->
			<table style="position: fixed; bottom: 150px; width: 100%;">
         
			<tbody>
<tr>
   <td style=" padding:80px 0px 0px 0px; position: relative;">
      <div style=" position: absolute; right: -17px;   top: -2px;"> <img src="https://winimi.de/images/happy_deal.png"
         width="300px">
      <table cellpadding="0" cellspacing="0" style="border-top: 1px solid #939393;     width: 100%;  " >
         <tbody>
            <tr>
               <td>
                  <table style=" font-size:10px;     width: 100%;   padding: 20px 0 0;" cellpadding="0" cellspacing="0">
                     <!-- -----1 -->
                     <tr>
                        <td style="border-right: 1px solid #939393;  padding: 0 10px;">
                           <p>{{ trans('pdf.company_name') }}<br>
                              {{ trans('pdf.address_1') }}<br>
                              {{ trans('pdf.address_2') }}
                           <p>
                        </td>
                        <!-- --------- -->
                        <td style="    padding: 0 10px; border-right: 1px solid #939393;">
                           <table cellpadding="0" cellspacing="0">
                              <tbody>
                                 <tr>
                                    <td style="font-weight: bold;    width: 33%;">{{ trans('pdf.email') }} </td>
                                    <td>{{ trans('pdf.email_info') }} </td>
                                 </tr>
                       
                                 <tr>
                                    <td style="font-weight: bold;    width: 33%;">{{ trans('pdf.internet') }} </td>
                                    <td>{{ trans('pdf.website2') }} </td>
                                 </tr>
                              </tbody>
                           </table>
                        </td>
                        <!-- ----------- -->
                        <td style=" padding: 0 10px; border-right: 1px solid #939393;">
                           <table cellpadding="0" cellspacing="0">
                              <tbody>
                                 <tr>
                                    <td style="width: 25%;">{{ trans('pdf.bank') }} </td>
                                    <td>{{ trans('pdf.bank_city') }} </td>
                                 </tr>
                                 <tr>
                                    <td style="width: 25%;">{{ trans('pdf.owner') }} </td>
                                    <td>{{ trans('pdf.owner_name') }} </td>
                                 </tr>
                                 <tr>
                                    <td style="width: 25%;">{{ trans('pdf.iban') }} </td>
                                    <td>{{ trans('pdf.iban_number') }} </td>
                                 </tr>
                                 <tr>
                                    <td style="width: 25%;">{{ trans('pdf.bic') }} </td>
                                    <td> {{ trans('pdf.bic_number') }} </td>
                                 </tr>
                              </tbody>
                           </table>
                        </td>
                        <!-- ------------- -->
                        <td style="    padding: 0 10px;">
                           <table cellpadding="0" cellspacing="0">
                              <tbody>
                                 <tr>
                                    <td colspan="2">{{ trans('pdf.tax_office') }} </td>
                                 </tr>
                                 <tr>
                                    <td>{{ trans('pdf.street') }}</td>
                                    <td>{{ trans('pdf.street_no') }}</td>
                                 </tr>
                                 <tr>
                                    <td>{{ trans('pdf.vat_idn') }} </td>
                                    <td>{{ trans('pdf.vat_number') }}</td>
                                 </tr>
                              </tbody>
                           </table>
                        </td>
                     </tr>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
   </td>
</tr>
         </tbody>
      </table>
<!-- ----------------------------- -->