
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
            font-size: 10px;
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
    </style>
    <body>                       
        <table>
            <thead>
                <tr>
                    <th colspan="2" style="font-weight:700; text-align:left" >{{ $title }}</th>
                    <th colspan="14"style="font-weight:700; text-align:left">{{ $date_title }}</th>
                </tr>
                <tr>
                    <th rowspan="2" style="font-weight:700; text-align:center">#</th>
                    <th rowspan="2" style="font-weight:700; text-align:center">{{ trans('app.banks') }}</th>
                    <th rowspan="2" style="font-weight:700; text-align:center">{{ trans('app.mfo id') }}</th>
                    <th style="font-weight:700; text-align:center">{{ trans('app.average rating of banks') }}</th>
                    <th colspan="2"  style="font-weight:700; text-align:center">{{ trans('app.changing rate of banks') }}</th>
                    <th style="font-weight:700; text-align:center" class="text-center">
                        {{ trans('app.i out of') }}
                    </th>
                    <th style="font-weight:700; text-align:center">
                        {{ trans('app.i work lost') }}
                    </th>
                    <th style="font-weight:700; text-align:center">
                        {{ trans('app.i likvid active') }}
                    </th>
                    <th style="font-weight:700; text-align:center">
                        {{ trans('app.i likvid credit') }}
                    </th>
                    <th style="font-weight:700; text-align:center">
                        {{ trans('app.i bank liability') }}
                    </th>
                    <th style="font-weight:700; text-align:center; word-wrap:break-word">
                        {{ trans('app.i bank liability demand') }}
                    </th>
                    <th style="font-weight:700; text-align:center">
                        {{ trans('app.i net profit') }}
                    </th>
                    <th style="font-weight:700; text-align:center">
                        {{ trans('app.i active likvid') }}
                    </th>
                    <th style="font-weight:700; text-align:center">   
                        {{ trans('app.i income expense') }}
                    </th>
                    <th style="font-weight:700; text-align:center">
                        {{ trans('app.i others') }}
                    </th>
                </tr>
                
                <tr>  
                    <th style="font-weight:700; text-align:center">100%</th>  
                    <th style="font-weight:700; text-align:center">
                        {{ trans('app.change in rating') }}</th>
                    <th style="font-weight:700; text-align:center">
                        {{ trans('app.change in percent') }}</th>
                    <th style="font-weight:700; text-align:center">
                        {{ $weight->i_out_of }}%
                    </th>
                    <th style="font-weight:700; text-align:center">
                        {{ $weight->i_work_lost }}% 
                    </th>
                    <th style="font-weight:700; text-align:center">
                        {{ $weight->i_likvid_active }}%
                    </th>
                    <th style="font-weight:700; text-align:center">
                        {{ $weight->i_likvid_credit }}%
                    </th>
                    <th style="font-weight:700; text-align:center">
                        {{ $weight->i_b_liability }}%
                    </th>
                    <th style="font-weight:700; text-align:center">
                        {{ $weight->i_b_liability_demand }}%
                    </th>
                    <th style="font-weight:700; text-align:center">
                        {{ $weight->i_net_profit }}%
                    </th>
                    <th style="font-weight:700; text-align:center">
                        {{ $weight->i_active_likvid }}%
                    </th>
                    <th style="font-weight:700; text-align:center">
                        {{ $weight->i_income_expense }}%
                    </th>
                    <th style="font-weight:700; text-align:center">
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
                            <td style="text-align: left">{{ $i }}</td>
                            <td style="text-align: left">{{ $item->name }}</td>
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
                            <td style="text-align: center; color:{{ ($i_out_of->color == '#000')?'':$i_out_of->color }}">
                                {{ number_format($i_out_of->final_result, 2) }}
                                
                            </td>
                            <td style="text-align: center; color:{{ ($i_work_lost->color == '#000')?'':$i_work_lost->color }}">
                                {{ number_format($i_work_lost->final_result, 2) }}
                            </td>
                            <td style="text-align: center; color:{{ ($i_l_active->color == '#000')?'':$i_l_active->color }}">
                                {{ number_format($i_l_active->final_result, 2) }}
                            </td>
                            <td style="text-align: center; color:{{ ($i_l_credit->color == '#000')?'':$i_l_credit->color }}">
                                {{ number_format($i_l_credit->final_result, 2) }}
                            </td>
                            <td style="text-align: center; color:{{ ($i_b_liability->color == '#000')?'':$i_b_liability->color }}">
                                {{ number_format($i_b_liability->final_result, 2) }}
                            </td>
                            <td style="text-align: center; color:{{ ($i_b_liability_demand->color == '#000')?'':$i_b_liability_demand->color }}">
                                {{ number_format($i_b_liability_demand->final_result, 2) }}
                            </td>
                            <td style="text-align: center; color:{{ ($i_net_profit->color == '#000')?'':$i_net_profit->color }}">
                                {{ number_format($i_net_profit->final_result, 2) }}
                            </td>
                            <td style="text-align: center; color:{{ ($i_a_likvid->color == '#000')?'':$i_a_likvid->color }}">
                                {{ number_format($i_a_likvid->final_result, 2) }}
                            </td>
                            <td style="text-align: center; color:{{ ($i_i_expense->color == '#000')?'':$i_i_expense->color }}">
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
    </body>
 </html>