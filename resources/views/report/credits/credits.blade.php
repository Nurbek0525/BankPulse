@extends('layouts.app')

@section('content')

<div class="content-w">
<div class="content-i">
<div class="content-box">
<div class="element-wrapper">
    <div class="element-box">
        <div class="row">
            <div class="col-12">
                <form method="POST" action="/report/loan/table{{ (!empty($status))?"?status=".$status:"" }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    <h5 class="form-header form-header-font-size">
                                        @if(!empty($title))
                                            {{$title}}
                                        @endif
                                    </h5>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-1">
                                    <select class="selectpicker form-control" name="city" data-live-search="true" >
                                        <option value="all">{{ trans('app.all') }}</option>
                                        <option value="" selected disabled hidden> {{ trans('app.select city') }}</option>
                                        @if(!empty($cities))
                                            @foreach ($cities as $item)
                                                @if(!empty($city) && $city != 'all')
                                                    <option {{ $city->id == $item->id?'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                                @else
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <select class="selectpicker form-control" name="mainbank" data-live-search="true">
                                            <option value="" selected disabled hidden> {{ trans('app.select main bank') }}</option>
                                            <option value="all">{{ trans('app.all') }}</option>
                                            @if(!empty($mainbanks))
                                                @foreach ($mainbanks as $item)
                                                    @if(!empty($mainbank) && $mainbank != 'all')
                                                        <option {{ $mainbank->id == $item->id?'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @else
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select class="selectpicker form-control" name="fillial" data-live-search="true">
                                            
                                            <option value="" selected disabled hidden> {{ trans('app.select fillial bank') }}</option>
                                            <option value="all">{{ trans('app.all') }}</option>
                                            @if(!empty($fillials))
                                                @foreach ($fillials as $bank)
                                                    @if(!empty($fillial) && $fillial != 'all')
                                                        <option {{ ($fillial->id == $bank->id)?'selected':'' }} value="{{ $bank->id }}">{{ $bank->name }}</option>
                                                    @else
                                                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select class="selectpicker form-control" name="activity_code" data-live-search="true">
                                            <option value="" selected disabled hidden> {{ trans('app.select activity') }}</option>
                                            <option value="all">{{ trans('app.all') }}</option>
                                            @if(!empty($activities))
                                                @foreach ($activities as $item)
                                                    @if(!empty($activity_code)  && $activity_code != 'all')
                                                        <option {{ $activity_code->code == $item->code?'selected':'' }} value="{{ $item->code }}">{{ $item->name." [".$item->code."]" }}</option>
                                                    @else
                                                        <option value="{{ $item->code }}">{{ $item->name." [".$item->code."]" }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">    
                                        <select class="selectpicker form-control" name="goal_code" data-live-search="true">
                                            <option value="" selected disabled hidden> {{ trans('app.select goal') }}</option>
                                            <option value="all">{{ trans('app.all') }}</option>
                                            @if(!empty($goal_codes))
                                                @foreach ($goal_codes as $item)
                                                    @if(!empty($goal_code) && $goal_code != 'all')
                                                        <option {{ $goal_code->code == $item->code?'selected':'' }} value="{{ $item->code }}">{{ $item->name." [".$item->code."]" }}</option>
                                                    @else
                                                        <option value="{{ $item->code }}">{{ $item->name." [".$item->code."]" }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="status" value="{{ $status??'' }}">
                                    <div class="col-md-1">
                                    <div class="form-group">
                                    <input name="monthyear" value="{{ $current_year.'-'??'' }}{{ !empty($current_month)?((count_digit($current_month) == 1)?'0'.$current_month:$current_month):'' }}" readonly="true" class="form-control datepicker"  type="text" placeholder="{{ trans('app.time') }}"  value="{{ $monthyear ?? "" }}" />
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                    <input type="text" name="search" class="form-control " placeholder="{{ trans('app.search') }}" value="{{ $s??'' }}" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="row">
                                        <div class="col-md-9">
                                           <div class="form-group">
                                                <input type="submit" class="form-control btn btn-primary btn-block" value="{{ trans('app.show') }}" />
                                            </div> 
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="{{trans('app.Export to Excel')}}"
                                                    class="btn btn-primary btn-square excel-button">
                                                    <i class="fa fa-file-excel-o"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                {{-- <div class="col-md-1">
                                    <div class="row">
                                        
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        
                    </div>
                    
                </form>
            </div>
        </div>
        
        <div class="table-responsive">
            <table id="myTable" class="table table-striped table-lightfont">
                <thead>
                    <tr>
                        <th width='1%' class="color-black">#</th>
                        <th width='14%'class="color-black">{{ trans('app.client name') }}</th> 
                        <th width='4%'class="color-black">{{ trans('app.client passport') }}</th>
                        <th width='4%'class="color-black">{{ trans('app.client inn') }}</th>
                        <th width='15%'class="color-black">{{ trans('app.banks') }}</th>
                        <th width='2%'class="color-black">{{ trans('app.mfo id') }}</th>
                        <th width='2%'class="color-black">{{ trans('app.credit rate') }}</th>
                        <th width='5%'class="color-black">{{ trans('app.given date') }}</th>
                        <th width='5%'class="color-black">{{ trans('app.expire date') }}</th>
                        <th width='8%'class="color-black">{{ trans('app.credit amount') }}</th>
                        <th width='8%'class="color-black">{{ trans('app.credit remainder') }}</th>
                        <th width='7%' class="color-black">{{ trans('app.out of time loan') }}</th>
                        <th width='8%'class="color-black">{{ trans('app.all debt') }}</th>
                        <th width='7%'class="color-black">{{ trans('app.backup created') }}</th>
                        <th width='7%'class="color-black">{{ trans('app.backup needed') }}</th>
                    </tr>
                        <tr style="background-color: #ecaf3269">
                        <th style="text-transform: uppercase; font-weight: 700;">{{trans('app.whole')}}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="amount" style="font-weight: 700;">{{ number_format($all_amount_equiv , 0, '.', ' ') }}</th>
                        <th class="amount" style="font-weight: 700;">{{ number_format($all_remainder , 0, '.', ' ') }}</th>
                        <th class="amount" style="font-weight: 700;">{{ number_format($all_out_of , 0, '.', ' ') }}</th>
                        <th class="amount" style="font-weight: 700;">{{ number_format($all_debt_amount , 0, '.', ' ') }}</th>
                        <th class="amount" style="font-weight: 700;">{{ number_format($all_backup , 0, '.', ' ') }}</th>
                        <th class="amount" style="font-weight: 700;">{{ number_format($all_needed_backup , 0, '.', ' ') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($credits))
                    @php
                        $i=1;
                        if(isset($_GET['page'])){
                            $i = $i + (intval($_GET['page']) - 1)*100;
                        }
                        
                    @endphp
                    
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
                                <td class="amount">
                                    {{ number_format($portfolio->contract_amount_eqiuv, 0, '.', ' ') }}
                                </td>
                                <td class="amount">
                                    {{ number_format(floatval($portfolio->remainder), 0, '.', ' ') }}
                                </td>
                                <td class="amount">
                                    {{ number_format(floatval($portfolio->out_of), 0, '.', ' ') }}
                                </td>
                                <td class="amount">
                                    {{ number_format($portfolio->debt_amount, 0, '.', ' ') }}
                                </td>
                                <td class="amount">
                                    {{ number_format($portfolio->backup_created, 0, '.', ' ') }}
                                </td>
                                <td class="amount">
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
            @if(!empty($credits))
                {{ $credits->links() }}
            @endif
        </div>
    </div>
</div>
</div>
</div>
</div>
<script src="{{ URL::asset('assets/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ URL::asset('assetsnew/js/html5Canvas.js') }}"></script>

<script>
    $('document').ready(function(){
        $('select[name="mainbank"]').on('change', function(){
            var mainbank = $(this).val();
            if(mainbank == 'all'){
                $('select[name="fillial"]').attr('disabled', true);
            }else{
                $('select[name="fillial"]').attr('disabled', false);
            }
        })
        $('select[name="city"]').on('change', function(){
            var city = $(this).val();
            if(city == 'all'){
                $('select[name="fillial"]').attr('disabled', true);
            }else{
                $('select[name="fillial"]').attr('disabled', false);
            }
        })
        $('select[name="region"]').on('change', function(){
            $('select[name="city"]').selectpicker('refresh');
            var state = $(this).val();
            $.ajax({
                method: 'GET',
                url:'/getcity',
                data:'region='+state,
                success:function(data){
                    $('select[name="city"]').html(data);
                    $('select[name="city"]').selectpicker('refresh');
                }
            });
            
        });
        $('select[name="region"], select[name="city"]').on('change', function(){
            var region = $('select[name="region"]').val();
            var city = $('select[name="city"]').val();
            $.ajax({
                method: 'GET',
                url:'/getmainbank',
                data: {region:region, city:city},
                success:function(data){
                    $('select[name="mainbank"]').html(data);
                    $('select[name="mainbank"]').selectpicker('refresh');
                }
            });
        })
        $('select[name="city"]').on('change', function(){
            $('select[name="mainbank"]').selectpicker('refresh');
            var city = $('select[name="city"]').val();
            $.ajax({
                method: 'GET',
                url:'/getfillial',
                data: {city:city},
                success:function(data){
                    $('select[name="fillial"]').html(data);
                    $('select[name="fillial"]').selectpicker('refresh');
                }
            });
        })
        $('select[name="region"]').on('change', function(){
            $('select[name="mainbank"]').selectpicker('refresh');
            var state = $('select[name="region"]').val();
            $.ajax({
                method: 'GET',
                url:'/getfillial',
                data: {state:state},
                success:function(data){
                    $('select[name="fillial"]').html(data);
                    $('select[name="fillial"]').selectpicker('refresh');
                }
            });
        })
        $('select[name="mainbank"]').change( function(){
            var state = $('select[name="region"]').val();
            var mainbank = $('select[name="mainbank"]').val();
            var city = $('select[name="city"]').val();
            $.ajax({
                method: 'GET',
                url:'/getfillial',
                data:{state:state, mainbank:mainbank, city:city},
                success:function(data){
                    $('select[name="fillial"]').html(data);
                    $('select[name="fillial"]').selectpicker('refresh');
                }
            });
        });
        var t = $('#myTable').DataTable({
            "searching": false,
            "paging": false,
            "bInfo": false,
            "scrollY":  800,
            "fixedHeader": {
                header: true,
            },
            "ordering": false,
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'pdfHtml5',
                'csvHtml5'
            ]
        });

        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
        $('input[name="search"]').on( 'keyup', function () {
            t.search($(this).val()).draw();
        } );

        $('.btn-square').on('focus', function () {
        $(this).blur()
        });

        $('input.datepicker').datepicker({
            format:'yyyy-mm',
            startView: 'months',
            minViewMode: 'months',
            autoclose:1,
            startView:'1',
            endDate: new Date()
        });

        $('.image-button').click(function(){
            window.scrollTo(0,0);
            t.destroy();
            t = $('#myTable').DataTable({
                "searching": false,
                "paging": false,
                "bInfo": false,
                "scrollY":  false,
                "fixedHeader": {
                    header: true,
                },
                "ordering": false,
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'pdfHtml5',
                    'csvHtml5'
                ]
            });
            var $outer_box = document.querySelector('#myTable_wrapper');
            // {scale: 1,  height: $('#myTable_wrapper').height() + 50}
            html2canvas($outer_box, { height: $('#myTable_wrapper').height() + 50}).then(function(canvas) {
                console.log(canvas);
                var image = canvas.toDataURL("image/png", 1.0).replace("image/png", "image/octet-stream");
                image.crossOrigin = 'anonymous';
                var link = document.createElement('a');
                link.download = "{{$title}}.png";
                link.href = image;
                link.click();
            });

            t.destroy();
            t = $('#myTable').DataTable({
                "searching": false,
                "paging": false,
                "bInfo": false,
                "scrollY":  800,
                "fixedHeader": {
                    header: true,
                },
                "ordering": false,
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'pdfHtml5',
                    'csvHtml5'
                ]
            });
        });

        $('.excel-button').click(function(){
            var data = $('form').serialize();
            window.location.href = '{!! url('/export/excel/loans/loans') !!}?'+data;
        });
        $('.pdf-button').click(function(){
            var monthyear = $('input[name="monthyear"]').val();
            window.location.href = '{!! url('/export/pdf/loans/loans') !!}?monthyear='+monthyear;
        });
    })
</script>
<style type="text/css">
    ::-webkit-scrollbar {
      display: none;
    }

    ::-webkit-scrollbar-button {
      display: none;
    }

    body {
      -ms-overflow-style:none;
    }
    ul.pagination{
        margin-top: 20px
    }
    #myTable_filter, #myTable_wrapper .row:first-child{
        display: none;
    }
    .form-group{
        margin-bottom: 0!important
    }
    .dt-buttons{
        display: none;
    }
</style>
@endsection