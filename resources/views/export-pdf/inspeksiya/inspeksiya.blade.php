
 <html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>{{ $title }}</title>
        <meta name="author" content="Andrés Herrera García">
        <meta name="description" content="PDF de una fotomulta">
        <meta name="keywords" content="fotomulta, comparendo">
    </head>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 5px;
        }
        td {
            border-top: 1px solid #000;
        }
        tr:last-child td {
            border-bottom: 1px solid #000;
        }
        tr:first-child td {
            border-top: none;
        }
        tr th {
            border-bottom: 1px solid #000;
        }
        td, th {
            border-left: none;
            border-right: none;
        }
        table td, table th {
            box-sizing: content-box;
        }
    </style>
    <body>   
        <div>
            <table>
                <thead>
                    <tr>
                        <th colspan="2" width="3%" style="font-weight:700; text-align:left" >{{ $title }}</th>
                        <th colspan="14" width="97%" style="font-weight:700; text-align:left">{{ $date_title }}</th>
                    </tr>
                    <tr style="height:50px">
                        <th width="1%" rowspan="2">#</th>
                        <th width="14%" rowspan="2" style="font-weight:700; text-align:center">{{ trans('app.banks') }}</th>
                        <th width="3%" rowspan="2" style="font-weight:700; text-align:center">{{ trans('app.mfo id') }}</th>
                        <th width="3%" style="font-weight:700; text-align:center">{{ trans('app.average rating of banks') }}</th>
                        <th width="7%" colspan="2" style="font-weight:700; text-align:center; max-width:50px">{{ trans('app.changing rate of banks') }}</th>
                        <th width="7%" class="font-weight:700; text-center; word-wrap:break-word">
                            {{ trans('app.i out of') }}
                        </th>
                        <th width="7%" class="font-weight:700; text-center;">
                            {{ trans('app.i work lost') }}
                        </th>
                        <th width="5%" class="font-weight:700; text-center;">
                            {{ trans('app.i likvid active') }}
                        </th>
                        <th width="5%" class="font-weight:700; text-center;">
                            {{ trans('app.i likvid credit') }}
                        </th>
                        <th width="8%" class="font-weight:700; text-center;">
                            {{ trans('app.i bank liability') }}
                        </th>
                        <th width="8%" class="font-weight:700; text-center;">
                            {{ trans('app.i bank liability demand') }}
                        </th>
                        <th width="5%" class="font-weight:700; text-center;">
                            {{ trans('app.i net profit') }}
                        </th>
                        <th width="5%" class="font-weight:700; text-center;">
                            {{ trans('app.i active likvid') }}
                        </th>
                        <th width="5%" class="font-weight:700; text-center;">   
                            {{ trans('app.i income expense') }}
                        </th>
                        <th width="5%" class="font-weight:700; text-center;">
                            {{ trans('app.i others') }}
                        </th>
                    </tr>
                    
                    <tr>  
                        <th>100%</th>  
                        <th>
                            {{ trans('app.change in rating') }}</th>
                        <th>
                            {{ trans('app.change in percent') }}</th>
                        <th>
                            {{ $weight->i_out_of }}%
                        </th>
                        <th>
                            {{ $weight->i_work_lost }}% 
                        </th>
                        <th>
                            {{ $weight->i_likvid_active }}%
                        </th>
                        <th>
                            {{ $weight->i_likvid_credit }}%
                        </th>
                        <th>
                            {{ $weight->i_b_liability }}%
                        </th>
                        <th>
                            {{ $weight->i_b_liability_demand }}%
                        </th>
                        <th>
                            {{ $weight->i_net_profit }}%
                        </th>
                        <th>
                            {{ $weight->i_active_likvid }}%
                        </th>
                        <th>
                            {{ $weight->i_income_expense }}%
                        </th>
                        <th>
                            {{ $weight->i_others }}%
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($reports))
                    @php
                        $i=1;
                    @endphp
                        @foreach ($reports as $item)
                            @php
                                if(!empty($item->i_out_of)){
                                    $i_out_of = json_decode($item->i_out_of);
                                }else{
                                    $i_out_of = new stdClass;
                                    $i_out_of->final_result = 0;
                                }
                                if(!empty($item->i_work_lost)){
                                    $i_work_lost = json_decode($item->i_work_lost);
                                }else{
                                    $i_work_lost = new stdClass;
                                    $i_work_lost->final_result = 0;
                                }
                                if(!empty($item->i_likvid_active)){
                                    $i_l_active = json_decode($item->i_likvid_active);
                                }else{
                                    $i_l_active = new stdClass;
                                    $i_l_active->final_result = 0;
                                }
                                if(!empty($item->i_likvid_credit)){
                                    $i_l_credit = json_decode($item->i_likvid_credit);
                                }else{
                                    $i_l_credit = new stdClass;
                                    $i_l_credit->final_result = 0;
                                }
                                if(!empty($item->i_active_likvid)){
                                    $i_a_likvid = json_decode($item->i_active_likvid);
                                }else{
                                    $i_a_likvid = new stdClass;
                                    $i_a_likvid->final_result = 0;
                                }
                                if(!empty($item->i_b_liability)){
                                    $i_b_liability = json_decode($item->i_b_liability);
                                }else{
                                    $i_b_liability = new stdClass;
                                    $i_b_liability->final_result = 0;
                                }
                                if(!empty($item->i_b_liability_demand)){
                                    $i_b_liability_demand = json_decode($item->i_b_liability_demand);
                                }else{
                                    $i_b_liability_demand = new stdClass;
                                    $i_b_liability_demand->final_result = 0;
                                }
                                if(!empty($item->i_net_profit)){
                                    $i_net_profit = json_decode($item->i_net_profit);
                                }else{
                                    $i_net_profit = new stdClass;
                                    $i_net_profit->final_result = 0;
                                }
                                if(!empty($item->i_income_expense)){
                                    $i_i_expense = json_decode($item->i_income_expense);
                                }else{
                                    $i_i_expense = new stdClass;
                                    $i_i_expense->final_result = 0;
                                }
                                if(!empty($item->i_others)){
                                    $i_others = json_decode($item->i_others);
                                }else{
                                    $i_others = new stdClass;
                                    $i_others->final_result = 0;
                                }

                                if(!empty($item->inspeksiya)){
                                    $average = json_decode($item->inspeksiya)->percent;
                                }else{
                                    $average = 0;
                                }
                                $average = number_format($average, 2);
                                if(!empty($item->rate_diff)){
                                    $rate_diff = $item->rate_diff;
                                    if($rate_diff < 0){
                                        $rate_diff = $rate_diff*-1;
                                        $diff_color = 'red';
                                    }else{
                                        $diff_color = 'blue';
                                    }
                                }
                                if(!empty($item->rate_percent)){
                                    $rate_percent = $item->rate_percent;
                                    $rate_percent = $item->rate_percent;
                                    if($rate_percent < 0){
                                        $rate_percent = $rate_percent*-1;
                                        $percent_icon = 'down6';
                                        $percent_color = 'red';
                                    }else{
                                        $percent_icon = 'up6';
                                        $percent_color = 'blue';
                                    }
                                }
                            @endphp
                            <tr id="{{ generateMfo($item->mfo_id) }}">
                                <td>{{ $i }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ generateMfo($item->mfo_id) }}</td>
                                <td style="text-align: center">
                                    {{ $average }}
                                </td>
                                <td style="color:{{ $diff_color }}; text-align: center">
                                    {{ $rate_diff??'' }}
                                </td>
                                <td style="color:{{ $percent_color }}; text-align: center">
                                    {{ $rate_percent??'' }}
                                </td>
                                <td style="text-align: center; color:{{ $i_out_of->color }}">
                                    {{ number_format($i_out_of->final_result, 2) }}
                                    
                                </td>
                                <td style="text-align: center; color:{{ $i_work_lost->color }}">
                                    {{ number_format($i_work_lost->final_result, 2) }}
                                </td>
                                <td style="text-align: center; color:{{ $i_l_active->color }}">
                                    {{ number_format($i_l_active->final_result, 2) }}
                                </td>
                                <td style="text-align: center; color:{{ $i_l_credit->color }}">
                                    {{ number_format($i_l_credit->final_result, 2) }}
                                </td>
                                <td style="text-align: center; color:{{ $i_b_liability->color }}">
                                    {{ number_format($i_b_liability->final_result, 2) }}
                                </td>
                                <td style="text-align: center; color:{{ $i_b_liability_demand->color }}">
                                    {{ number_format($i_b_liability_demand->final_result, 2) }}
                                </td>
                                <td style="text-align: center; color:{{ $i_net_profit->color }}">
                                    {{ number_format($i_net_profit->final_result, 2) }}
                                </td>
                                <td style="text-align: center; color:{{ $i_a_likvid->color }}">
                                    {{ number_format($i_a_likvid->final_result, 2) }}
                                </td>
                                <td style="text-align: center; color:{{ $i_i_expense->color }}">
                                    {{ number_format($i_i_expense->final_result, 2) }}
                                </td>
                                <td style="text-align: center">
                                    {{ number_format($i_others->final_result, 2) }}
                                </td>
                            </tr>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                        
                    @endif
                </tbody>
            </table>
        </div>                    
            
    </body>
 </html>