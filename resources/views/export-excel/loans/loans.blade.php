
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
                    <th colspan="13"style="font-weight:700; text-align:left">{{ $date_title }}</th>
                </tr>
                <tr>
                    <th style="font-weight: 700;">#</th>
                    <th style="font-weight: 700;">{{ trans('app.client name') }}</th> 
                    <th style="font-weight: 700;">{{ trans('app.client passport') }}</th>
                    <th style="font-weight: 700;">{{ trans('app.client inn') }}</th>
                    <th style="font-weight: 700;">{{ trans('app.banks') }}</th>
                    <th style="font-weight: 700;">{{ trans('app.mfo id') }}</th>
                    <th style="font-weight: 700;">{{ trans('app.credit rate') }}</th>
                    <th style="font-weight: 700;">{{ trans('app.given date') }}</th>
                    <th style="font-weight: 700;">{{ trans('app.expire date') }}</th>
                    <th style="font-weight: 700;">{{ trans('app.credit amount') }}</th>
                    <th style="font-weight: 700;">{{ trans('app.credit remainder') }}</th>
                    <th style="font-weight: 700;">{{ trans('app.out of time loan') }}</th>
                    <th style="font-weight: 700;">{{ trans('app.all debt') }}</th>
                    <th style="font-weight: 700;">{{ trans('app.backup created') }}</th>
                    <th style="font-weight: 700;">{{ trans('app.backup needed') }}</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($credits))
                    @php
                        $i=1;
                    @endphp
                    <tr style="background-color: #ecaf3269">
                        <td style="text-transform: uppercase; font-weight: 700;">{{trans('app.whole')}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: 700;">{{ number_format($all_amount_equiv , 0, '.', ' ') }}</td>
                        <td style="font-weight: 700;">{{ number_format($all_remainder , 0, '.', ' ') }}</td>
                        <td style="font-weight: 700;">{{ number_format($all_out_of , 0, '.', ' ') }}</td>
                        <td style="font-weight: 700;">{{ number_format($all_debt_amount , 0, '.', ' ') }}</td>
                        <td style="font-weight: 700;">{{ number_format($all_backup , 0, '.', ' ') }}</td>
                        <td style="font-weight: 700;">{{ number_format($all_needed_backup , 0, '.', ' ') }}</td>
                    </tr>
                    @foreach ($credits as $item)
                        @php
                            $portfolio = json_decode($item->portfolio);
                            if(!empty($portfolio->out_of)){
                                $datetime2 = new DateTime(date('d-m-Y', strtotime($portfolio->out_of_date)));
                                $datetime1 = new DateTime(date('d-m-Y'));
                                $interval = $datetime1->diff($datetime2)->format('%a');
                                if($interval > 180){
                                    $backup_needed = $portfolio->debt_amount*1;
                                }elseif($interval > 120 && $interval < 180){
                                    $backup_needed = $portfolio->debt_amount*0.5;
                                }elseif($interval > 90 && $interval < 120){
                                    $backup_needed = $portfolio->debt_amount*0.25;
                                }elseif($interval > 30 && $interval < 90){
                                    $backup_needed = $portfolio->debt_amount*0.1;
                                }
                            }else{
                                $backup_needed = 0;
                            }

                            $str = explode(",", $item->client_inn_passport);
                            if(!empty($str[0])){
                                
                            }
                            $inn = (!empty($str[0]))?$str[0]:"";
                            $passport = (!empty($str[1]))?$str[1]:"";
                        @endphp
                        <tr>
                            <td>{{ $i }}</td>
                            <td>
                                {{ ucwords(strtolower($item->client_name)) }}
                            </td>
                            <td>
                                {{ $passport }}
                            </td>
                            <td>
                                {{ $inn }}
                            </td>
                            <td>{{ $item->bank_name }}</td>
                            <td>{{ generateMfo($item->mfo_id) }}</td>
                            <td style="text-align: center">
                                {{ number_digiting(floatval($portfolio->rate)) }}%
                            </td>
                            <td>
                                {{ date('d.m.Y', strtotime($portfolio->given_date)) }}
                            </td>
                            <td>
                                {{ date('d.m.Y', strtotime($portfolio->expire_date)) }}
                            </td>
                            <td>
                                {{ number_format($portfolio->contract_amount_eqiuv, 0, '.', ' ') }}
                            </td>
                            <td>
                                {{ number_format(floatval($portfolio->remainder), 0, '.', ' ') }}
                            </td>
                            <td>
                                {{ number_format(floatval($portfolio->out_of), 0, '.', ' ') }}
                            </td>
                            <td>
                                {{ number_format($portfolio->debt_amount, 0, '.', ' ') }}
                            </td>
                            <td>
                                {{ number_format($portfolio->backup_created, 0, '.', ' ') }}
                            </td>
                            <td>
                                {{ number_format($backup_needed, 0, '.', ' ') }}
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
