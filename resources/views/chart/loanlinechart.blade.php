@extends('layouts.app')

@section('content')
@php
    $user = Auth::user();
    $position = get_position($user);
@endphp
<div class="content-w">
    <div class="content-i">
        <div class="content-box">
            <div class="element-wrapper">
                <h6 class="element-header">{{ $title }}</h6>
                <div class="element-box">
                    <form class="myChartForm" action="javascript:void(0)" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="row">
                                    @if($position == 'admin' || $position == 'country')
                                        <div class="col-12 col-md-1">
                                            <div class="form-group">
                                                <select class="selectpicker form-control" name="region" data-live-search="true" required>
                                                    <option value="" selected disabled hidden> {{ trans('app.select region') }}</option>
                                                    <option value="all">{{ trans('app.all') }}</option>
                                                    @if(!empty($regions))
                                                        @foreach ($regions as $item)
                                                            @if(!empty($region))
                                                                <option {{ $region->id == $item->id?'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @else
                                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-12 col-md-1">
                                        <div class="form-group">
                                            <select class="selectpicker form-control" name="city" data-live-search="true" >
                                                <option value="all">{{ trans('app.all') }}</option>
                                                <option value="" selected disabled hidden> {{ trans('app.select city') }}</option>
                                                @if(!empty($cities))
                                                    @foreach ($cities as $item)
                                                        @if(!empty($city))
                                                            <option {{ $city == $item->id?'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @else
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <select class="selectpicker form-control" name="mainbank" data-live-search="true" >
                                                <option value="" selected disabled hidden> {{ trans('app.select main bank') }}</option>
                                                <option value="all">{{ trans('app.all') }}</option>
                                                @if(!empty($mainbanks))
                                                    @foreach ($mainbanks as $item)
                                                        @if(!empty($mainbank))
                                                            <option {{ $mainbank->id == $item->id?'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @else
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <select class="selectpicker form-control" name="fillial" data-live-search="true" >
                                                <option value="all">{{ trans('app.all') }}</option>
                                                <option value="" selected disabled hidden> {{ trans('app.select fillial bank') }}</option>
                                                @if(!empty($fillials))
                                                    @foreach ($fillials as $item)
                                                        @if(!empty($bank))
                                                            <option {{ $bank->id == $item->id?'selected':'' }} value="{{ $item->id }}">{{ '['.$item->mfo_id.'] '.$item->name }}</option>
                                                        @else
                                                            <option value="{{ $item->id }}">{{ '['.generateMfo($item->mfo_id).'] '.$item->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <select class="selectpicker form-control" name="activity_code" data-live-search="true">
                                                <option value="" selected disabled hidden> {{ trans('app.select activity') }}</option>
                                                <option value="all">{{ trans('app.all') }}</option>
                                                @if(!empty($activities))
                                                    @foreach ($activities as $item)
                                                        @if(!empty($activity))
                                                            <option {{ $activity->code == $item->code?'selected':'' }} value="{{ $item->code }}">{{ $item->name." [".$item->code."]" }}</option>
                                                        @else
                                                            <option value="{{ $item->code }}">{{ $item->name." [".$item->code."]" }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <select class="selectpicker form-control" name="goal_code" data-live-search="true">
                                                <option value="" selected disabled hidden> {{ trans('app.select goal') }}</option>
                                                <option value="all">{{ trans('app.all') }}</option>
                                                @if(!empty($goal_codes))
                                                    @foreach ($goal_codes as $item)
                                                        @if(!empty($goal))
                                                            <option {{ $goal->code == $item->code?'selected':'' }} value="{{ $item->code }}">{{ $item->name." [".$item->code."]" }}</option>
                                                        @else
                                                            <option value="{{ $item->code }}">{{ $item->name." [".$item->code."]" }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <input readonly="true"  required="required" name="startmonthyear" class="form-control datepicker"  type="text" placeholder="{{ trans('app.time') }}" value="" />
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <input readonly="true"  required="required" name="endmonthyear" class="form-control datepicker"  type="text" placeholder="{{ trans('app.time') }}" value="" />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-1">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <input type="submit" class="form-control btn btn-primary btn-block" value="{{ trans('app.show') }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="element-box">
                    <div class="row">
                        <div class="col-12 col-md-6 title-chart" style="display: none; margin-bottom:20px">
                            <h5 class="form-header head-title title"></h5>
                        </div>
                        <div class="col-12 col-md-6 title-chart" style="display: none; margin-bottom:20px">
                            <div class="row" style="text-align: right">
                                <div class="col-12 col-md-4"></div>
                                <div class="col-12 col-md-2 title-chart" style="display: none; margin-bottom:20px">
                                    <h5 class="form-header font400"><div class="analysis"><i class="fa fa-globe"></i></div> <span class="place-title"></span></h5>
                                </div>
                                <div class="col-12 col-md-2 title-chart" style="display: none; margin-bottom:20px">
                                    <h5 class="form-header font400"><div class="analysis"><i class="fa fa-university"></i></div> <span class="bank-title"></span></h5>
                                </div>
                                <div class="col-12 col-md-4 title-chart" style="display: none; margin-bottom:20px">
                                    <h5 class="form-header font400"><div class="analysis"><i class="fa fa-calendar"></i></div> <span class="time-title"></span></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 info-chart" style=" display: none;">
                            <div class="card-body" style="display: none;">
                                <div class="custom-control custom-checkbox mb-3 sum">
                                    <input divide="1000000000" url="sum" type="checkbox" class="custom-control-input divide" id="Checksummlrd" /> 
                                    <label class="custom-control-label" for="Checksummlrd">{{ trans('app.mlrdsum') }}</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-3 sum">
                                    <input divide="1000000" url="sum" type="checkbox" class="custom-control-input divide" id="Checksummln" /> 
                                    <label class="custom-control-label" for="Checksummln">{{ trans('app.mlnsum') }}</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-3 sum">
                                    <input divide="1000" url="sum" type="checkbox" class="custom-control-input divide" id="Checksumming" /> 
                                    <label class="custom-control-label" for="Checksumming">{{ trans('app.mingsum') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 info-chart" style=" display: none;">
                            <div class="card-body" style="display: none;">
                                <div class="custom-control custom-checkbox mb-3 currency">
                                    <input divide="1000000000" url="currency" type="checkbox" class="custom-control-input divide" id="Checkcurrencymlrd" /> 
                                    <label class="custom-control-label" for="Checkcurrencymlrd">{{ trans('app.mlrdsum') }}</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-3 currency">
                                    <input divide="1000000" url="currency" type="checkbox" class="custom-control-input divide" id="Checkcurrencymln" /> 
                                    <label class="custom-control-label" for="Checkcurrencymln">{{ trans('app.mlnsum') }}</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-3 currency">
                                    <input divide="1000" url="currency" type="checkbox" class="custom-control-input divide" id="Checkcurrencyming" /> 
                                    <label class="custom-control-label" for="Checkcurrencyming">{{ trans('app.mingsum') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col col-12 col-md-6">
                            <div class="checkbox-title" style="display: none;">
                                <div class="row"  style="margin-left: 45px; font-weight: 700; position: absolute; ">
                                    <h5 style="margin: unset; ">{{ trans('app.sum') }}</h5>
                                    <h5><span id="sum" class="checbox-type" style="margin-left: 5px; font-weight:400"></span></h5>
                                </div>
                            </div>
                            <div id="lineareChartsum" class="chat-container container-h-600"></div>
                        </div>
                        <div class="col col-12 col-md-6">
                            <div class="checkbox-title"  style="display: none;">
                                <div class="row"  style="margin-left: 45px; font-weight: 700; position: absolute;">
                                    <h3 style="margin: unset; ">{{ trans('app.currency') }}</h3>
                                    <span id="cur" class="checbox-type" style="margin-top: 14px; margin-left: 5px"></span>
                                </div>
                            </div>
                            <div id="lineareChartcurrency" class="chat-container container-h-600"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ URL::asset('assets/js/jquery-3.3.1.min.js') }}"></script>
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
        $('select[name="ratingtype"]').on('change', function(){
            var ratingtype = $(this).val();
            if(ratingtype == 'all' || ratingtype == 'monthly'){
                $('select[name="sub_department"]').attr('disabled', true);
            }else{
                $('select[name="sub_department"]').attr('disabled', false);
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
        $('.myChartForm').submit(function(){
            var form_data = $(this).serialize();
            $.ajax({
                method: 'POST',
                url:'{!! url('/charts/rating-chart') !!}',
                data:form_data,
                success:function(data){
                    $('.info-chart').show();
                    var title = $('.checkbox-title');
                    $('.title-chart').show();
                    title.show();
                    var info = $.parseJSON(data);
                    $('h4.head-title').html(info.head_title);
                    $('span.place-title').html(info.place_title);
                    $('span.bank-title').html(info.bank_title);
                    $('span.time-title').html(info.time_title);
                    var chart = info.chart;
                    var length = chart.length;
                    var monthyear = [];
                    var colors =[];
                    var final_result =[];
                    var legends = [];
                    var mainbank = $('select[name="mainbank"]').val();
                    var city = $('select[name="city"]').val();
                    var image_name = info.bank_title+' '+info.time_title;
                    for(var i = 0; i < length; i++){
                        var color = '#'+(Math.random() * 0xFFFFFF << 0).toString(16).padStart(6, '0')
                        colors.push(color);
                    	if(i == 0){
                    		for(var k = 0; k < chart[i].data_monthyear.length; k++){
	                            monthyear.push(chart[i].data_monthyear[k]);
	                        }
                    	}
                        if(!info.fillial_view){
                            var name = {
                                name: chart[i].data_name
                            }
                        }else{
                            var name = {
                                name: chart[i].data_name+' ['+chart[i].data_id + ']'
                            }
                        }
                        if(!info.fillial_view){
                            var data_final = {
                                name: chart[i].data_name,
                                type: 'line',
                                symbolSize: 4,
                                smooth: true,
                                data: chart[i].final_result 
                            };
                        }else{
                            var data_final = {
                                name: chart[i].data_name+' ['+chart[i].data_id + ']',
                                type: 'line',
                                symbolSize: 4,
                                smooth: true,
                                data: chart[i].final_result 
                            };
                        }
                        legends.push(name);
                        final_result.push(data_final);
                    }
                    lineareChart(monthyear, final_result, colors, legends, image_name);
                    
                }
            })
        });

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
        $('select[name="ratingtype"]').change( function(){
            var department = $(this).val();
            $.ajax({
                method: 'GET',
                url:'/getsubdepartment',
                data:{department:department},
                success:function(data){
                    $('select[name="sub_department"]').html(data);
                    $('select[name="sub_department"]').selectpicker('refresh');
                }
            });
        });
        function lineareChart(monthyear, data, colors, legends, image_name) {
            if ($('#lineareChart').length) {
                var myChart = echarts.init(document.getElementById('lineareChart'));

                var options = {
                    color: colors,
                    tooltip: {
                        trigger: 'item',
                        axisPointer: {
                            type: 'cross'
                        },
                        formatter: function(params){
                            var data = params.value;
                            return data.toLocaleString();
                        },

                    },
                    legend: {
                        data: legends,                        
                        textStyle: {
                            fontFamily: "Roboto,Arial,sans-serif"
                        },
                        itemGap: 5,
                        itemWidth: 25,
                        bottom: 0
                    },
                    toolbox: {
                        feature: {
                            saveAsImage: {
                                title: '{{ trans('app.save chart as image') }}',
                                name: image_name,
                                icon: 'image://{{ URL::asset('resources/views/layouts/assets/img/download.png') }}'
                            },
                        },
                        right: "5%" 
                    },
                    grid: {
                        left: 60,
                        right: 30,
                        top: 50,
                        bottom: 130
                    },
                    xAxis: {
                        type: 'category',
                        axisLine: {
                            onZero: false,
                            lineStyle: {
                                color: '#ef3e36'
                            }
                        },
                        boundaryGap: false,
                        minorTick: {
                            show: true
                        },
                        splitLine: {
                            show: true
                        },

                        data: monthyear
                    },
                    yAxis: {
                        type: 'value',
                        axisPointer: {
                            label: {
                                formatter: function(value){
                                    var data = parseInt(value.value);
                                    return data.toLocaleString();
                                }
                            }
                        },
                        axisLabel: {
                            formatter: function(value){
                                return value.toFixed(2);
                            }
                        },
                        minorTick: {
                            show: true
                        },
                        splitLine: {
                            lineStyle: {
                                color: '#999'
                            }
                        },
                        minorSplitLine: {
                            show: true,
                            lineStyle: {
                                color: '#ddd'
                            }
                        }

                    },
                    series: data
                };

                myChart.setOption(options, true);

                // Resize chart
                $(function() {
                    $(window).on('resize', resize);

                    function resize() {
                      setTimeout(function() { myChart.resize() }, 200);
                    }
                })
            }
        };
        
        
    })
</script>
@endsection