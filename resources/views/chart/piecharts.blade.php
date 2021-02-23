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
                    <form id="myChartForm" action="javascript:void(0)" method="POST">
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
                                    <div class="col-12 col-md-2">
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
                                            <select class="selectpicker  form-control" name="mainbank" data-live-search="true" >
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
                                            <select class="selectpicker  get-fillial  form-control" name="fillial" data-live-search="true" >
                                                <option value="" selected disabled hidden> {{ trans('app.select fillial bank') }}</option>
                                                <option value="all">{{ trans('app.all') }}</option>
                                                @if(!empty($fillials))
                                                    @foreach ($fillials as $item)
                                                        @if(!empty($bank))
                                                            <option {{ $bank->id == $item->id?'selected':'' }} value="{{ $item->id }}">{{ '['.generateMfo($item->mfo_id).'] '.$item->name }}</option>
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
                                            <select class="selectpicker  form-control" name="cataccount" data-live-search="true" required="required">
                                                <option value="" selected disabled hidden> {{ trans('app.select cat account') }}</option>
                                                @if(!empty($cat_accounts))
                                                    @foreach ($cat_accounts as $item)
                                                        @if(!empty($cat_account))
                                                            <option {{ $cat_account->id == $item->id?'selected':'' }} value="{{ $item->id }}">{{ $item->name.' ['.$item->account_id.']' }}</option>
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
                                            <select class="selectpicker  form-control" name="accountsheet" data-live-search="true">
                                                <option value="" selected disabled hidden> {{ trans('app.select account sheet') }}</option>
                                                <option value="all">{{ trans('app.all') }}</option>
                                                @if(!empty($account_sheets))
                                                    @foreach ($account_sheets as $item)
                                                        @if(!empty($account_sheet))
                                                            <option {{ ($account_sheet->id == $item->id)?'selected':'' }} value="{{ $item->id }}">{{ $item->name.' ['.$item->account_id.']' }}</option>
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
                                            <input readonly="true"  name="monthyear" class="form-control datepicker"  type="text" placeholder="{{ trans('app.time') }}" />
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
                    <div class="row">
                        <div class="col-12 col-md-6 info-chart" style=" display: none;">
                            <div class="form-header checkbox-title">
                                <div class="row"  style="margin-left: 45px; font-weight: 700; margin-top:10px">
                                    <h6 style="margin: unset; ">{{ trans('app.balance sum') }}</h6>
                                    <h6><span id="sum" class="checbox-type" style="margin-left: 5px; font-weight:400"></span></h6>
                                </div>
                            </div>
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
                            <div class="form-header checkbox-title">
                                <div class="row"  style="margin-left: 45px; font-weight: 700; margin-top:10px">
                                    <h6 style="margin: unset; ">{{ trans('app.balance currency') }}</h6>
                                    <h6><span id="sum" class="checbox-type" style="margin-left: 5px; font-weight:400"></span></h6>
                                </div>
                            </div>
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
                        <div class="col-12 col-md-6" style="display: none">
                            <div id="pieEsum" class="chat-container container-h-400"></div>
                        </div>
                        <div class="col-12 col-md-6" style="display: none">
                            <div id="pieEcurrency" class="chat-container container-h-400"></div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="row">
                                <div class="col-md-8">
                                    <canvas id="piesum" style="width: 100%; height: 450px;"></canvas>
                                </div>
                                <div class="col-md-4">
                                    <div class="pieChartjssum legend"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="row">
                                <div class="col-md-8">
                                    <canvas id="piecurrency" style="width: 100%; height: 450px;"></canvas>
                                </div>
                                <div class="col-md-4">
                                    <div class="pieChartjscurrency legend"></div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ URL::asset('assets/js/jquery-3.3.1.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
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
        $(".pieChartjssum").on("click", "ul > li", function(e){
            var index = $(this).index();
            $(this).toggleClass("strike");
            var curr = pieChartjssum.data.datasets[0]._meta[0].data[index];
            curr.hidden = !curr.hidden;
            pieChartjssum.update();
        });
        $(".pieChartjscurrency").on("click", "ul > li", function(e){
            var index = $(this).index();
            $(this).toggleClass("strike");
            
            var curr = pieChartjscurrency.data.datasets[0]._meta[2].data[index];
            curr.hidden = !curr.hidden;
            pieChartjscurrency.update();
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
        var options = $('select[name="fillial"] option');
        var pieChartjssum = null;
        var pieChartjscurrency = null;

        $('input[name="monthyear"]').on('click', function(){
            if($(this).hasClass("form-control-focus")){
                $(this).removeClass("form-control-focus");
            }
        });

        $('select[name="cataccount"]').on('click', function(){
            if($(this).hasClass("form-control-focus")){
                $(this).removeClass("form-control-focus");
            }
        });

        $('#myChartForm').submit(function(){
            var monthyear = $('input[name="monthyear"]').val();
            // var cataccount = $('select[name="cataccount"]').val();
            if(monthyear == '')
            {    
                $('input[name="monthyear"]').focus();
                $('input[name="monthyear"]').addClass("form-control-focus");
            }else{
                var form_data = $(this).serialize();
                $.ajax({
                    method: 'POST',
                    url:'{!! url("/charts/pie-chart") !!}',
                    data:form_data,
                    success:function(data){
                        $('.info-chart').show();
                        var title = $('.title-chart');
                        title.show();
                        var info = $.parseJSON(data);
                        $('h4.head-title').html(info.head_title);
                        $('span.place-title').html(info.place_title);
                        $('span.bank-title').html(info.bank_title);
                        $('span.time-title').html(info.time_title);
                        var chart = info.chart;
                        
                        var monthyear = [];
                        var sum = [];
                        var currency = [];
                        var colors = [];
                        var names = [];
                        var datasum = [];
                        var datacurrency = [];
                        if(chart == 'empty'){
                            $('h4.head-title').html(info.head_title);
                            $('span.place-title').html(info.place_title);
                            $('span.bank-title').html(info.bank_title);
                            $('span.time-title').html(info.time_title);
                            Swal.fire({
                                // icon: 'error',
                                title: '{{ trans('app.empty data') }}',
                                showConfirmButton: true,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#ecaf32',
                                timer: null
                            });
                            var typesum = 'sum';
                            var typecurrency = 'currency';
                            pieChartjs(null, sum, null, typesum);
                            pieChartjs(null, currency, null, typecurrency);
                        }else{
                            var length = chart.length;
                            for(var i = 0; i < length; i++){
                                var color = info.colors[i];
                                if(info.fillial_view == true || info.account_sheet == true){
                                    var name = info.chart[i].data_name+" ["+info.chart[i].data_id + "]";
                                }else{
                                    var name = info.chart[i].data_name;
                                }
                                var datavaluesum = { value: info.chart[i].sum, name: name};
                                var datavaluecurrency = { value: info.chart[i].currency, name: name};
                                colors.push(color);
                                sum.push(info.chart[i].sum);
                                currency.push(info.chart[i].currency);
                                names.push(name);
                                datasum.push(datavaluesum);
                                datacurrency.push(datavaluecurrency);
                            }
                            var typesum = 'sum';
                            var typecurrency = 'currency';
                            pieChartjs(names, sum, colors, typesum);
                            pieChartjs(names, currency, colors, typecurrency);
                        }
                    }
                });
            }
        });


        $('select[name="region"]').on('change', function(){
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
        $('select[name="mainbank"], select[name="region"], select[name="city"]').change( function(){
            var state = $('select[name="region"]').val();
            var city = $('select[name="city"]').val();
            var mainbank = $('select[name="mainbank"]').val();
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
        function pieChartjs(names, values, colors, type) {
            if ($('#pie'+type).length){
                var ctx = document.getElementById('pie'+type);
                var config = {
                    type: 'pie',
                    data: {
                      labels: names,
                      datasets: [
                        {
                          data: values,
                          backgroundColor: colors
                        }
                      ]
                    },
                    options: {
                      responsive: true,
                      legend: {
                            display:false
                      },
                      tooltips: {
                            callbacks: {
                                // this callback is used to create the tooltip label
                                label: function(tooltipItem, data) {
                                       // get the data label and data value to display
                                       // convert the data value to local string so it uses a comma seperated number
                                    var dataLabel = data.labels[tooltipItem.index];
                                    var value = ': ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

                                       // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
                                    if (Chart.helpers.isArray(dataLabel)) {
                                        // show value on first line of multiline label
                                        // need to clone because we are changing the value
                                        dataLabel = dataLabel.slice();
                                        dataLabel[0] += value;
                                    } else {
                                        dataLabel += value;
                                    }

                                       // return the text to display on the tooltip
                                    return dataLabel;
                                }
                            }
                        }
                    }
                }
                if(type == 'sum'){
                    if(pieChartjssum == null){
                        pieChartjssum = new Chart(ctx, config);
                        $('.pieChartjssum').html(pieChartjssum.generateLegend());
                    }else{
                        if(names == null){
                            pieChartjssum.destroy();
                            $('.pieChartjssum').html('');
                            pieChartjssum = new Chart(ctx, config);
                        }else{
                            pieChartjssum.config.data.labels = names;
                            pieChartjssum.config.data.datasets[0].data = values;
                            pieChartjssum.config.data.datasets[0].backgroundColor = colors;
                            pieChartjssum.update();
                            $('.pieChartjssum').html(pieChartjssum.generateLegend());
                        }
                    }
                }else if(type == 'currency'){
                    if(pieChartjscurrency == null){
                        pieChartjscurrency = new Chart(ctx, config);
                        $('.pieChartjscurrency').html(pieChartjscurrency.generateLegend());
                    }else{
                        if(names == null){
                            pieChartjscurrency.destroy();
                            $('.pieChartjscurrency').html('');
                            pieChartjscurrency = new Chart(ctx, config);
                        }else{
                            pieChartjscurrency.config.data.labels = names;
                            pieChartjscurrency.config.data.datasets[0].data = values;
                            pieChartjscurrency.config.data.datasets[0].backgroundColor = colors;
                            pieChartjscurrency.update();
                            $('.pieChartjscurrency').html(pieChartjscurrency.generateLegend());
                        }
                    }
                }
            }
        }
    }); 


</script>
<style type="text/css">
    

</style>
@endsection