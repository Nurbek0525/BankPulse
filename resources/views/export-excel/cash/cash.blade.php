

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
        <div>
            <table>
                <thead>
                    <tr>
                        <th colspan="2" style="font-weight:700; text-align:left" >{{ $title }}</th>
                        <th colspan="8"style="font-weight:700; text-align:left">{{ $date_title }}</th>
                    </tr>
                    <tr>
                        <th rowspan="2" style="font-weight:700; text-align:center" >#</th>
                        <th rowspan="2" style="font-weight:700; text-align:center">{{ trans('app.banks') }}</th>
                        <th rowspan="2" style="font-weight:700; text-align:center">{{ trans('app.mfo id') }}</th>
                        <th style="font-weight:700; text-align:center">{{ trans('app.average rating of banks') }}</th>
                        <th colspan="2" style="font-weight:700; text-align:center">{{ trans('app.changing rate of banks') }}</th>
                        <th style="font-weight:700; text-align:center">
                            {{ trans('app.cash tushum') }}
                        </th>
                        <th style="font-weight:700; text-align:center">
                            {{ trans('app.cash qaytish') }}
                        </th>
                        <th style="font-weight:700; text-align:center">
                            {{ trans('app.cash monthly report') }}
                        </th>
                        <th style="font-weight:700; text-align:center">
                            {{ trans('app.cash ijro') }}
                        </th>
                    </tr>
                    
                    <tr>
                        <th>100%</th>
                        <th>
                            {{ trans('app.change in rating') }}
                        </th>
                        <th>
                            {{ trans('app.change in percent') }}
                        </th>
                        <th>
                            {{ $weight->cash_tushum }}% 
                        </th>
                        <th>
                            {{ $weight->cash_qaytish }}% 
                        </th>
                        <th>
                            {{ $weight->cash_m_report }}%  
                        </th>
                        <th>
                            {{ $weight->cash_execution }}%
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
                            if(!empty($item->cash_tushum)){
                                $tushum = json_decode($item->cash_tushum);
                            }else{
                                $tushum = new stdClass;
                                $tushum->final_result = 0;
                            }
                            if(!empty($item->cash_qaytish)){
                                $qaytish = json_decode($item->cash_qaytish);
                            }else{
                                $qaytish = new stdClass;
                                $qaytish->final_result = 0;
                            }
                            if(!empty($item->cash_execution)){
                                $execution = json_decode($item->cash_execution);
                            }else{
                                $execution = new stdClass;
                                $execution->final_result = 0;
                            }
                            if(!empty($item->cash_m_report)){
                                $m_report = json_decode($item->cash_m_report);
                            }else{
                                $m_report = new stdClass;
                                $m_report->final_result = 0;
                            }
                            if(!empty($item->cash)){
                                $average = json_decode($item->cash)->percent;
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
                                if($rate_percent < 0){
                                    $rate_percent = $rate_percent*-1;
                                    $percent_color = 'red';
                                }else{
                                    $percent_color = 'blue';
                                }
                            }
                            @endphp
                            <tr>
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
                                <td style="text-align: center; color:{{ ($tushum->color == '#000')?'':$tushum->color }}">
                                    {{ number_format($tushum->final_result, 2) }}
                                </td>
                                <td style="text-align: center; color:{{ ($qaytish->color == '#000')?'':$qaytish->color }}">
                                    {{ number_format($qaytish->final_result, 2) }}
                                </td>
                                <td style="text-align: center; color:{{ ($m_report->color == '#000')?'':$m_report->color }}">
                                    {{ number_format($m_report->final_result, 2) }}
                                </td>
                                <td style="text-align: center; color:{{ ($execution->color == '#000')?'':$execution->color }}">
                                    {{ number_format($execution->final_result, 2) }}
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