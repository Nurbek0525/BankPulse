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
                                            <select class="selectpicker form-control" name="ratingtype" data-live-search="true" required="required">
                                                <option value="" selected disabled hidden> {{ trans('app.select rating type') }}</option>
                                                <option value="all">{{ trans('app.all') }}</option>
                                                <option value="monthly">{{ trans('app.monthly rating') }}</option>
                                                @if(!empty($rating_types))
                                                    @foreach ($rating_types as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>    
                                                    @endforeach
                                                @endif
                                                    
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <select class="selectpicker form-control" name="sub_department" data-live-search="true" >
                                                <option value="" selected disabled hidden> {{ trans('app.select rating sub type') }}</option>
                                                <option value="all">{{ trans('app.all') }}</option>
                                                @if(!empty($sub_department))
                                                    @foreach ($sub_department as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <input readonly="true" name="startmonthyear" class="form-control datepicker"  type="text" placeholder="{{ trans('app.time from') }}" value="" />
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <input readonly="true" name="endmonthyear" class="form-control datepicker"  type="text" placeholder="{{ trans('app.time to') }}" value="" />
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
                    <div class="screen" style="padding: 2rem 3rem">
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

                                            <div class="analysis chart-btn" style="margin-left: 20px; margin-right: 20px" data-toggle="tooltip" data-placement="bottom"
                                             title = "{{ trans('app.Export to IMG') }}" >
                                                <i class="fa fa-download chart-download"></i>
                                            </div> 
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-md-center">
                            <div class="col col-12 col-md-10 col-xl-8" style="margin-right: auto;margin-left: auto;">
                                <div id="lineareChart" class="chat-container container-h-600"></div>
                                <div class="legendraiting">
                                    <ul></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="outer">
    
</div>
<script src="{{ URL::asset('assets/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ URL::asset('assetsnew/js/html5Canvas.js') }}"></script>
<script src="{{ URL::asset('assetsnew/plugins/blob/blob.min.js') }}"></script>

<script>
    $('document').ready(function(){
        $('select[name="mainbank"]').on('change', function(){
            var mainbank = $(this).val();
            if(mainbank == 'all'){
                $('select[name="fillial"]').attr('disabled', true);
            }else{
                $('select[name="fillial"]').attr('disabled', false);
            }
            if(mainbank == 'all'){
                $('select[name="ratingtype"] option[value="all"]').removeAttr('selected');
                $('select[name="ratingtype"] option[value="all"]').attr('disabled', true);
            }else{ 
                $('select[name="ratingtype"] option[value="all"]').attr('disabled', false);
            }
            $('select[name="ratingtype"]').selectpicker('refresh');
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
                    url:'{!! url('/charts/rating-chart') !!}',
                    data:form_data,
                    success:function(data){
                        
                        $('.info-chart').show();
                        var title = $('.checkbox-title');
                        $('.title-chart').show();
                        title.show();
                        var info = $.parseJSON(data);
                        console.log(info);
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


         $(".legendraiting").on("click", "ul > li", function(e){
            var index = $(this).index();
            $(this).toggleClass("strike");
            myChart.dispatchAction({
                type: 'legendToggleSelect',
               
                name: myChart._chartsViews[index].__model.name                
            });
           
        });

        $(".legendraiting").on("mouseenter", "ul > li", function(e){
            
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

        function lineareChart(monthyear, data, colors, legends, image_name) {
            var legend = $('.legendraiting'+' ul');
            legend.html('');
            for(var k = 0; k < legends.length; k++){
                var name_legend = "<li style='list-item:none; cursor:pointer; display:inline-block; margin-right:10px'><i class='fa fa-circle' style='color:"+ colors[k] +"'></i> "+ legends[k].name +"</li>";
                legend.append(name_legend);
            
            }


            if ($('#lineareChart').length) {
                 myChart = echarts.init(document.getElementById('lineareChart'));

                var options = {
                    responsive: true,
                    color: colors,
                    tooltip: {
                        trigger: 'item',
                        axisPointer: {
                            type: 'cross'
                        },
                        formatter: function(params){
                            var data = params.value;
                            var name = params.seriesName+": "+data.toLocaleString();
                            return name;
                        },

                    },
                    legend: {
                        show: false,
                        // data: data,
                        // bottom: "0%"
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

        $('.chart-btn').click(function(){
            window.scrollTo(0,0);
            var outer_box = document.querySelector('.screen');
            window.devicePixelRatio = 2;
            html2canvas(outer_box, { scale: 2, height: $('.screen').height() + 50}).then(function(canvas) {
                console.log(canvas);
                    document.querySelector('.outer').appendChild(canvas);
                    canvas.imageSmoothingEnabled= false;
                    var image = canvas.toDataURL("image/jpeg", 1.0).replace("image/jpeg", "image/octet-stream");
                    image.crossOrigin = 'anonymous';
                    var link = document.createElement('a');
                    link.download = "{{$title}}.jpeg";
                    link.href = image;
                    link.click();
             });      
            
        });
        
        
    })
</script>
@endsection