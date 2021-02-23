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
            <thead >
                <tr>
                    <th colspan="2" style="font-weight:700; text-align:left" >{{ $title }}</th>
                    <th colspan="9"style="font-weight:700; text-align:left">{{ $date_title }}</th>
                </tr>
                <tr>
                    <th rowspan="2" width="1%">#</th>
                    <th rowspan="2" width="15%" style="font-weight:700; text-align:center">{{ trans('app.banks') }}</th>
                    <th rowspan="2" width="5%" style="font-weight:700; text-align:center">{{ trans('app.mfo id') }}</th>
                    <th width="14%"style="font-weight:700; text-align:center">{{ trans('app.average rating of banks') }}</th>
                    <th colspan="2" width="15%" style="font-weight:700; text-align:center; padding:5px">{{ trans('app.changing rate of banks') }}</th>
                    <th width="10%" style="font-weight:700; text-align:center">
                        <span>{{ trans('app.cash report') }}</span>
                    </th>
                    <th width="10%" style="font-weight:700; text-align:center">
                        <span>{{ trans('app.business report') }}</span>
                    </th>
                    <th width="10%" style="font-weight:700; text-align:center">
                        <span>{{ trans('app.inspeksiya report') }}</span>
                    </th>
                    <th width="10%" style="font-weight:700; text-align:center">
                        <span>{{ trans('app.currency report') }}</span>
                    </th>
                    <th width="10%" style="font-weight:700; text-align:center">
                        <span>{{ trans('app.ijro report') }}</span>
                    </th>
                </tr>
                <tr>
                    @if(!empty($weight))
                        <th style="font-weight:700; text-align:center">100%</th>
                        <th style="font-weight:700; text-align:center" >{{ trans('app.change in rating') }}</th>
                        <th style="font-weight:700; text-align:center">{{ trans('app.change in percent') }}</th>
                        <th style="font-weight:700; text-align:center">
                            {{ $weight->cash }}%
                        </th>
                        <th style="font-weight:700; text-align:center">
                            {{ $weight->business }}%
                        </th>
                        <th style="font-weight:700; text-align:center">
                            {{ $weight->inspeksiya }}%
                        </th>
                        <th style="font-weight:700; text-align:center">
                            {{ $weight->currency }}%
                        </th>
                        <th style="font-weight:700; text-align:center">
                            {{ $weight->ijro_head }}%
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(!empty($reports))
                @php
                    $i = 1;
                @endphp
                    @foreach ($reports as $item)
                        @php
                            
                            if(!empty($item->cash)){
                                $cash = json_decode($item->cash);
                            }else{
                                $cash = new stdClass;
                                $cash->final_result = 0;
                            }
                            if(!empty($item->inspeksiya)){
                                $inspeksiya = json_decode($item->inspeksiya);
                            }else{
                                $inspeksiya = new stdClass;
                                $inspeksiya->final_result = 0;
                            }
                            if(!empty($item->business)){
                                $business = json_decode($item->business);
                            }else{
                                $business = new stdClass;
                                $business->final_result = 0;
                            }
                            if(!empty($item->currency)){
                                $currency = json_decode($item->currency);
                            }else{
                                $currency = new stdClass;
                                $currency->final_result = 0;
                            }
                            if(!empty($item->ijro)){
                                $ijro = json_decode($item->ijro);
                            }else{
                                $ijro = new stdClass;
                                $ijro->final_result = 0;
                            }
                            if(!empty($item->rate_diff)){
                                $rate_diff = $item->rate_diff;
                                if($rate_diff < 0){
                                    $rate_diff = $rate_diff*-1;
                                    $diff_icon = 'down';
                                    $diff_color = 'red';
                                }else{
                                    $diff_icon = 'up';
                                    $diff_color = 'blue';
                                }
                            }
                            if(!empty($item->rate_percent)){
                                $rate_percent = $item->rate_percent;
                                if($rate_percent < 0){
                                    $rate_percent = $rate_percent*-1;
                                    $percent_icon = 'down';
                                    $percent_color = 'red';
                                }else{
                                    $percent_icon = 'up';
                                    $percent_color = 'blue';
                                }
                            }
                            
                        
                        @endphp
                        <tr>
                            <td>{{ $i }}</td>
                            <td style="text-align: left">{{ $item->name }}</td>
                            <td style="text-align: center">{{ generateMfo($item->mfo_id) }}</td>
                            <td style="text-align: center">
                                {{ number_format($item->rate, 2) }}
                            </td>
                            <td  style="color:{{ $diff_color??'' }}; text-align: center">
                                {{ $rate_diff??'' }}
                            </td>
                            <td  style="color:{{ $percent_color??'' }}; text-align: center">
                                {{ $rate_percent??'' }}
                            </td>
                            <td style="text-align: center; color:{{ $cash->color }}">
                                {{ number_format($cash->final_result, 2) }}
                            </td>
                            <td style="text-align: center; color:{{ $business->color }}">
                                {{ number_format($business->final_result, 2) }}
                            </td>
                            <td style="text-align: center; color:{{ $inspeksiya->color }}">
                                {{ number_format($inspeksiya->final_result, 2) }}
                            </td>
                            <td style="text-align: center; color:{{ $currency->color }}">
                                {{ number_format($currency->final_result, 2) }}
                            </td>
                            <td style="text-align: center; color:{{ $ijro->color }}">
                                {{ number_format($ijro->final_result, 2) }}
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

