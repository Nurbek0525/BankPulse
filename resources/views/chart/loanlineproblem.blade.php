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
                <h5 class="element-header">{{ $title }}</h5>
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
                                                            <option {{ $activity->code == $item->code?'selected':'' }} value="{{ $item->code }}">{{ "[".$item->code."] ".$item->name }}</option>
                                                        @else
                                                            <option value="{{ $item->code }}">{{ "[".$item->code."] ".$item->name }}</option>
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
                                                            <option {{ $goal->code == $item->code?'selected':'' }} value="{{ $item->code }}">{{ "[".$item->code."] ".$item->name }}</option>
                                                        @else
                                                            <option value="{{ $item->code }}">{{ "[".$item->code."] ".$item->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <input readonly="true" name="startmonthyear" class="form-control datepicker"  type="text" placeholder="{{ trans('app.time') }}" value="" />
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <input readonly="true" name="endmonthyear" class="form-control datepicker"  type="text" placeholder="{{ trans('app.time') }}" value="" />
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
                            <h4 class="form-header head-title title"></h4>
                        </div>
                        <div class="col-12 col-md-6 title-chart" style="display: none; margin-bottom:20px">
                            <div class="row justify-content-end" style="text-align: right">
                                <div class="col-12 col-md-12 title-chart" style="display: none; margin-bottom:20px">
                                    <h6 class="form-header font400">
                                        <div class="analysis">
                                            <i class="fa fa-globe"></i>
                                        </div> 
                                        <span class="place-title"></span>
                                        <div class="analysis" style="margin-left: 10px">
                                            <i class="fa fa-university"></i>
                                        </div> 
                                        <span class="bank-title"></span>
                                        <div class="analysis" style="margin-left: 10px">
                                            <i class="fa fa-calendar"></i>
                                        </div> 
                                        <span class="time-title"></span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-md-center">
                        <div class="col col-12 col-md-6">
                            <div class="checkbox-title" style="display: none;">
                                <div class="row"  style="margin-left: 45px; font-weight: 700; position: absolute; ">
                                    <h6 id="portfoliotitle" style="margin: unset; "></h6>
                                </div>
                            </div>
                            <div id="portfolioChart" class="chat-container container-h-600"></div>
                             <div class="legendPortfolio">
                                <ul></ul>
                            </div>
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

        $('select[name="city"]').on('change', function(){
            var city = $(this).val();
            if(city == 'all'){
                $('select[name="fillial"]').attr('disabled', true);
            }else{
                $('select[name="fillial"]').attr('disabled', false);
            }
        })

        $('input[name="startmonthyear"]').on('click', function(){
            if($(this).hasClass("form-control-focus")){
                $(this).removeClass("form-control-focus");
            }
        });

        $('input[name="endmonthyear"]').on('click', function(){
            if($(this).hasClass("form-control-focus")){
                $(this).removeClass("form-control-focus");
            }
        });

        $('.myChartForm').submit(function(){
            var startmonthyear = $('input[name="startmonthyear"]').val();
            var endmonthyear = $('input[name="endmonthyear"]').val();

            if(startmonthyear == '' || endmonthyear == ''){
                $('input[name="startmonthyear"]').focus();
                $('input[name="startmonthyear"]').addClass("form-control-focus");
                $('input[name="endmonthyear"]').focus();
                $('input[name="endmonthyear"]').addClass("form-control-focus");
            }else{

                var form_data = $(this).serialize();
                $.ajax({
                    method: 'POST',
                    url:'{!! url('/charts/loan-line-problem') !!}',
                    data:form_data,
                    success:function(data){
                        $('.info-chart').show();
                        var title = $('.checkbox-title');
                        $('.title-chart').show();
                        title.show();
                        var info = $.parseJSON(data);
                        $('h4.head-title').html(info.head_title);
                        $('h6#portfoliotitle').html(info.divide_text);
                        $('span.place-title').html(info.place_title);
                        $('span.bank-title').html(info.bank_title);
                        $('span.time-title').html(info.time_title);
                        var chart = info.chart;
                        var dividing = info.dividing;
                        var length = chart.length;
                        var monthyear = [];
                        var colors =[];
                        var portfolio =[];
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
                            if(info.mainbank_view){
                                var name = {
                                    name: chart[i].data_name
                                }
                            }else{
                                var name = {
                                    name: chart[i].data_name+' ['+chart[i].data_id + ']'
                                }
                            }
                            if(info.mainbank_view){
                                var data_final = {
                                    name: chart[i].data_name,
                                    type: 'line',
                                    symbolSize: 4,
                                    smooth: true,
                                    data: chart[i].portfolio 
                                };
                            }else{
                                var data_final = {
                                    name: chart[i].data_name+' ['+chart[i].data_id + ']',
                                    type: 'line',
                                    symbolSize: 4,
                                    smooth: true,
                                    data: chart[i].portfolio 
                                };
                            }
                            legends.push(name);
                            portfolio.push(data_final);
                        }
                        portfolioChart(monthyear, portfolio, colors, legends, image_name, dividing);
                    }
                })
            }
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

        $(".legendPortfolio").on("click", "ul > li", function(e){
            var index = $(this).index();
            console.log(index);
            $(this).toggleClass("strike");
            console.log(myChart);
            myChart.dispatchAction({
                type: 'legendToggleSelect',
               
                name: myChart._chartsViews[index].__model.name                
            });
           
        });

        $(".legendPortfolio").on("mouseenter", "ul > li", function(e){
            
            var index = $(this).index();
             myChart.dispatchAction({
                type: 'highlight',
                seriesName: myChart._chartsViews[index].__model.name
            });
        }).on("mouseleave", "ul > li", function(e){
            var index = $(this).index();
             myChart.dispatchAction({
                type: 'downplay',
                seriesName: myChart._chartsViews[index].__model.name
            });
        });   


        var myChart = null;

        function portfolioChart(monthyear, data, colors, legends, image_name, dividing) {
           
           var legend = $('.legendPortfolio'+' ul');
            legend.html('');
            for(var k = 0; k < legends.length; k++){
                var name_legend = "<li style='list-item:none; cursor:pointer; display:inline-block; margin-right:10px'><i class='fa fa-circle' style='color:"+ colors[k] +"'></i> "+ legends[k].name +"</li>";
                legend.append(name_legend);
            
            }
            

            if ($('#portfolioChart').length) {
                myChart = echarts.init(document.getElementById('portfolioChart'));

                var options = {
                    color: colors,
                    tooltip: {
                        trigger: 'item',
                        axisPointer: {
                            type: 'cross'
                        },
                        formatter: function(params){
                            var data = params.value*dividing;
                            var name = params.seriesName+": "+data.toLocaleString();
                            return name;
                        },

                    },
                    legend: {
                       show: false
                    },
                    toolbox: {
                        feature: {
                            saveAsImage: {
                                title: '{{ trans('app.save chart as image') }}',
                                name: image_name,
                                icon: 'image://{{ URL::asset('assets/img/download.png') }}'
                            },
                        },
                        right: "5%" 
                    },
                    grid: {
                        left: 60,
                        right: 30,
                        top: 50,
                        bottom: 50
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