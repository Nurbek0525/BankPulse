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
                                                <select class="selectpicker" name="region" data-live-search="true" required>
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
                                            <select class="selectpicker form-control" name="cataccount" data-live-search="true" >
                                                <option value="" selected disabled hidden> {{ trans('app.select cat account') }}</option>
                                                @if(!empty($cat_accounts))
                                                    @foreach ($cat_accounts as $item)
                                                        @if(!empty($cat_account))
                                                            <option {{ $cat_account->id == $item->id?'selected':'' }} value="{{ $item->id }}">{{ '['.$item->account_id.'] '.$item->name }}</option>
                                                        @else
                                                            <option value="{{ $item->id }}">{{ '['.$item->account_id.'] '.$item->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <select class="selectpicker form-control" name="accountsheet" data-live-search="true" >
                                                <option value="" selected disabled hidden> {{ trans('app.select account sheet') }}</option>
                                                <option value="all">{{ trans('app.all') }}</option>
                                                @if(!empty($account_sheets))
                                                    @foreach ($account_sheets as $item)
                                                        @if(!empty($account_sheet))
                                                            <option {{ ($account_sheet->id == $item->id)?'selected':'' }} value="{{ $item->id }}">{{ '['.$item->account_id.'] '.$item->name }}</option>
                                                        @else
                                                            <option value="{{ $item->id }}">{{ '['.$item->account_id.'] '.$item->name }}</option>
                                                        @endif
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


                <div id="canvas" class="element-box chart-canvas">
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
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <div class="chart-screen-sum">
                                <div class="checkbox-title" style="display: none;">
                                    <div class="row"  style="margin-left: 45px; font-weight: 700; position: absolute; ">
                                        <h6 style="margin: unset; ">{{ trans('app.balance sum') }}</h6>
                                        <h6><span id="sum" class="checbox-type" style="margin-left: 5px; font-weight:400"></span></h6>
                                    </div>
                                </div>
                                <div id="lineareChartsum" class="chat-container container-h-600"></div>
                                <div class="legendsum">
                                    <ul></ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <div class="chart-screen-currency">
                                <div class="checkbox-title"  style="display: none;">
                                    <div class="row"  style="margin-left: 45px; font-weight: 700; position: absolute;">
                                        <h6 style="margin: unset; ">{{ trans('app.balance currency') }}</h6>
                                        <h6><span id="cur" class="checbox-type" style="margin-left: 5px; font-weight:400"></span></h6>
                                    </div>
                                </div>
                                <div id="lineareChartcurrency" class="chat-container container-h-600"></div>
                                <div class="legendcurrency">
                                    <ul></ul>
                                </div>
                            </div>
                        </div>
                    </div></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="outer"></div>
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
        })
        $('select[name="city"]').on('change', function(){
            var city = $(this).val();
            if(city == 'all'){
                $('select[name="fillial"]').attr('disabled', true);
            }else{
                $('select[name="fillial"]').attr('disabled', false);
            }
        })

        var ticks = null;
        var linearsum = null;
        var linearcurrency = null;

        var maxsum = null;
        var maxcur = null;
        var checkboxsum = null;
        var checkboxcur = null;

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

        $('select[name="cataccount"]').on('click', function(){
            if($(this).hasClass("form-control-focus")){
                $(this).removeClass("form-control-focus");
            }
        });


        $('.myChartForm').submit(function(){
            var startmonthyear = $('input[name="startmonthyear"]').val();
            var endmonthyear = $('input[name="endmonthyear"]').val();
            var optionname = $('select[name="cataccount"]').val();
            console.log(optionname);
            if(startmonthyear == '' || endmonthyear == ''){
                $('input[name="startmonthyear"]').focus();
                $('input[name="startmonthyear"]').addClass("form-control-focus");
                $('input[name="endmonthyear"]').focus();
                $('input[name="endmonthyear"]').addClass("form-control-focus");
            }else if(optionname == null){
                $('select[name="cataccount"]').siblings('button').focus().addClass("form-control-focus");
                $('select[name="cataccount"]').css('border-color', 'red');
            }else{
               var form_data = $(this).serialize();
                $.ajax({
                    method: 'POST',
                    url:'{!! url('/charts/line-chart') !!}',
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
                        var sum = [];
                        var currency = [];
                        var sume = [];
                        var currencye = [];
                        var colors =[];
                        var legends = [];
                        var mainbank = $('select[name="mainbank"]').val();
                        var city = $('select[name="city"]').val();
                        var image_name = info.bank_title+' '+info.time_title;
                        console.log(info);
                        for(var i = 0; i < length; i++){
                            var color = info.colors[i];
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
                            
                            for (var  k = 0; k < chart[i].sum.length; k ++){

                                if(maxsum == null){
                                    maxsum = chart[i].sum[k];
                                }else if(chart[i].sum[k] > maxsum){
                                    maxsum = chart[i].sum[k];
                                }
                            }
                            for (var  k = 0; k < chart[i].currency.length; k ++){
                                if(maxcur == null){
                                    maxcur = chart[i].sum[k];
                                }else if(chart[i].sum[k] > maxcur){
                                    maxcur = chart[i].sum[k];
                                }
                            }
                            if(info.mainbank_view){
                                var dataesum = {
                                    name: chart[i].data_name,
                                    type: 'line',
                                    symbolSize: 4,
                                    smooth: true,
                                    data: chart[i].sum 
                                };
                                var dataecurrency = {
                                    name: chart[i].data_name,
                                    type: 'line',
                                    symbolSize: 4,
                                    smooth: true,
                                    data: chart[i].currency 
                                };
                            }else{
                                var dataesum = {
                                    name: chart[i].data_name+' ['+chart[i].data_id + ']',
                                    type: 'line',
                                    symbolSize: 4,
                                    smooth: true,
                                    data: chart[i].sum 
                                };
                                var dataecurrency = {
                                    name: chart[i].data_name+' ['+chart[i].data_id + ']',
                                    type: 'line',
                                    symbolSize: 4,
                                    smooth: true,
                                    data: chart[i].currency 
                                };
                            }
                            legends.push(name);
                            sume.push(dataesum);
                            currencye.push(dataecurrency);
                        }
                        if(maxsum > 1000000000){
                            checkboxsum = "{{ trans('app.mlrd') }}";
                        }else if(maxsum > 1000000){
                            checkboxsum = "{{ trans('app.mln') }}";
                        }else if(maxsum > 1000){
                            checkboxsum = "{{ trans('app.ming') }}";
                        }
                        if(maxcur > 1000000000){
                            checkboxcur = "{{ trans('app.mlrd') }}";
                        }else if(maxcur > 1000000){
                            checkboxcur = "{{ trans('app.mln') }}";
                        }else if(maxcur > 1000){
                            checkboxcur = "{{ trans('app.ming') }}";
                        }
                        $("span#sum").html('('+checkboxsum+' UZS)');
                        $("span#cur").html('('+checkboxcur+' UZS)');
                        lineareChart(monthyear, sume, 'sum', colors, legends, image_name);
                        lineareChart(monthyear, currencye, 'currency', colors, legends, image_name);
                        
                    }
                }) 
            }

            
        });


         $("element-box").css('height', "900px");

        $('.chart-btn').on('focus', function () {
            $(this).blur()
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

        $('select[name="cataccount"]').change( function(){
            var cat_account = $(this).val();
            $.ajax({
                method: 'GET',
                url:'/getaccountsheet',
                data:{cat_account:cat_account},
                success:function(data){
                    $('select[name="accountsheet"]').html(data);
                    $('select[name="accountsheet"]').selectpicker('refresh');
                }
            });
        });

        $(".legendsum").on("click", "ul > li", function(e){
            var index = $(this).index();
            $(this).toggleClass("strike");
             mychartsum.dispatchAction({
                type: 'legendToggleSelect', 
                name: mychartsum._chartsViews[index].__model.name,
            });
        });

        $(".legendsum").on("mouseenter", "ul > li", function(e){   
            var index = $(this).index();
             mychartsum.dispatchAction({
                type: 'highlight',
                seriesName: mychartsum._chartsViews[index].__model.name
            });
        }).on("mouseleave", "ul > li", function(e){
            var index = $(this).index();
             mychartsum.dispatchAction({
                type: 'downplay',
                seriesName: mychartsum._chartsViews[index].__model.name
            });
        });

        $(".legendcurrency").on("click", "ul > li", function(e){
            var index = $(this).index();
            $(this).toggleClass("strike");
            mychartcurrency.dispatchAction({
                type: 'legendToggleSelect',
               
                name: mychartcurrency._chartsViews[index].__model.name                
            });
           
        });

        $(".legendcurrency").on("mouseenter", "ul > li", function(e){ 
            var index = $(this).index();
             mychartcurrency.dispatchAction({
                type: 'highlight',
                seriesName: mychartcurrency._chartsViews[index].__model.name
            });
        }).on("mouseleave", "ul > li", function(e){
            var index = $(this).index();
             mychartcurrency.dispatchAction({
                type: 'downplay',
                seriesName: mychartcurrency._chartsViews[index].__model.name
            });
        });
        



        var mychartsum = null;
        var mychartcurrency = null;

        function lineareChart(monthyear, data, type, colors, legends, image_name) {
            var legend = $('.legend'+type+' ul');
            legend.html('');
            for(var k = 0; k < legends.length; k++){
                var name_legend = "<li style='list-item:none; cursor:pointer; display:inline-block; margin-right:10px'><i class='fa fa-circle' style='color:"+ colors[k] +"'></i> "+ legends[k].name +"</li>";
                legend.append(name_legend);
            }

            var length = legends.length;
            if ($('#lineareChart'+type).length) {
                if(type == 'sum')
                {
                    mychartsum = echarts.init(document.getElementById('lineareChart'+type));

                }else if(type == 'currency')
                {
                    mychartcurrency = echarts.init(document.getElementById('lineareChart'+type));
                }

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
                        show:false
                    },
                    toolbox: {
                        // feature: {
                        //     myTool1: {
                        //         title: '{{ trans('app.save chart as image') }}',
                        //         name: image_name,
                        //         icon: 'image://{{ URL::asset('assets/img/download.png') }}',
                        //         onclick: function(){
                        //             window.scrollTo(0, 0);
                        //             html2canvas(document.querySelector('.chart-screen-'+type), {height: ($('.chart-screen-'+type).height()+50) }).then(function(canvas) {
                        //                 console.log(canvas);
                        //                 var image = canvas.toDataURL("image/png", 1.0).replace("image/png", "image/octet-stream");
                        //                 image.crossOrigin = 'anonymous';
                        //                 var link = document.createElement('a');
                        //                 link.download = image_name+".png";
                        //                 link.href = image;
                        //                 link.click();
                        //             });


                        //         }
                        //     },
                        // },
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
                                if(maxsum > 1000000000 && type == 'sum'){
                                    value = value/1000000000
                                }else if(maxsum > 1000000 && type == 'sum'){
                                    value = value/1000000
                                }else if(maxsum > 1000 && type == 'sum'){
                                    value = value/1000
                                }
                                if(maxcur > 1000000000 && type == 'currency'){
                                    value = value/1000000000
                                }else if(maxcur > 1000000 && type == 'currency'){
                                    value = value/1000000
                                }else if(maxcur > 1000 && type == 'currency'){
                                    value = value/1000
                                }
                                return value.toFixed(1);
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

                if(type== 'sum')
                {
                    mychartsum.setOption(options, true);
                }else if(type == 'currency')
                {
                    mychartcurrency.setOption(options, true);
                }

                

                // Resize chart
                $(function() {
                    $(window).on('resize', resize);

                    function resize() {
                      setTimeout(function() { 
                        if(type == 'sum'){
                            mychartsum.resize()    
                        }else if(type == 'currency')
                        {
                            mychartcurrency.resize()
                        } }, 200);
                    }
                });
            }
        };


        $('.chart-btn').click(function(){
            window.scrollTo(0,0);
            var outer_box = document.querySelector('.screen');
            window.devicePixelRatio = 2;
            html2canvas(outer_box, { scale: 2, height: $('#canvas').height() + 50}).then(function(canvas) {
                console.log(canvas);
                    // document.querySelector('.outer').appendChild(canvas);
                    canvas.imageSmoothingEnabled = false; 
                    // canvas.toBlob(
                    // blob => {
                    //     const anchor = document.createElement('a');
                    //     anchor.download = '{{$title}}.jpg';
                    //     anchor.href = URL.createObjectURL(blob);
                    //     anchor.click(); 
                    //     // URL.revokeObjectURL(anchor.href); // remove it from memory and save on memory! 😎
                    // },
                    // 'image/jpeg',
                    // 1.0,
                    // );
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