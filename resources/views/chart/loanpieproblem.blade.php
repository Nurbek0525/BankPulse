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
                                            <input readonly="true" name="monthyear" class="form-control datepicker"  type="text" placeholder="{{ trans('app.time') }}" value="" />
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
                            <h4 style="display:inline-block;">{{ trans('app.problem loans portfolio title') }}:</h4>
                            <h4  style="display:inline-block;" class="form-header head-title title"></h4>
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
                        <div class="col-12 col-md-8 info-chart" style=" display: none;">
                            <div class="card-body" style="display: none;">
                                <div class="custom-control custom-checkbox mb-3 problem">
                                    <input divide="1000000000" url="problem" type="checkbox" class="custom-control-input divide" id="Checkproblemmlrd" /> 
                                    <label class="custom-control-label" for="Checkproblemmlrd">{{ trans('app.mlrdcredit') }}</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-3 problem">
                                    <input divide="1000000" url="problem" type="checkbox" class="custom-control-input divide" id="Checkproblemmln" /> 
                                    <label class="custom-control-label" for="Checkproblemmln">{{ trans('app.mlncredit') }}</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-3 problem">
                                    <input divide="1000" url="problem" type="checkbox" class="custom-control-input divide" id="Checkproblemming" /> 
                                    <label class="custom-control-label" for="Checkproblemming">{{ trans('app.mingcredit') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="row">
                                <div class="col-md-8">
                                    <canvas id="pieproblem" style="width: 100%; height: 450px;"></canvas>
                                </div>
                                <div class="col-md-4">
                                    <div class="pieChartjsproblem legend"></div>
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
<script>
    $('document').ready(function(){
        var pieChartjscredit = null;
        var pieChartjsproblem = null;
        var polarChartjscredit = null;
        var polarChartjsproblem = null;
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

        $('input[name="monthyear"]').on('click', function(){
            if($(this).hasClass("form-control-focus")){
                $(this).removeClass("form-control-focus");
            }
        });


        $('.myChartForm').submit(function(){
            var monthyear = $('input[name="monthyear"]').val();
             if(monthyear == '')
            {    
                $('input[name="monthyear"]').focus();
                $('input[name="monthyear"]').addClass("form-control-focus");
            }else{
                var form_data = $(this).serialize();
                $.ajax({
                    method: 'POST',
                    url:'{!! url("/charts/loan-pie-problem") !!}',
                    data:form_data,
                    success:function(data){
                        $('.info-chart').show();
                        var title = $('.title-chart');
                        title.show();
                        var info = $.parseJSON(data);
                        if(info.data == "empty"){
                            $('h4.head-title').html(info.head_title);
                            $('span.place-title').html(info.place_title);
                            $('span.bank-title').html(info.bank_title);
                            $('span.time-title').html(info.time_title);
                            Swal.fire({
                                title: '{{ trans('app.empty data') }}',
                                showConfirmButton: true,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#ecaf32',
                                timer: null
                            });
                            pieChartjs(null, credit, null);
                        }else{
                            $('h4.head-title').html(info.head_title);
                            $('span.place-title').html(info.place_title);
                            $('span.bank-title').html(info.bank_title);
                            $('span.time-title').html(info.time_title);
                            var chart = info.chart;
                            var length = chart.length;
                            var monthyear = [];
                            var credit = [];
                            var problem = [];
                            var colors = [];
                            var names = [];
                            var dataproblem = [];
                            var mainbank = $('select[name="mainbank"]').val();
                            for(var i = 0; i < length; i++){
                                var color = '#'+(Math.random() * 0xFFFFFF << 0).toString(16).padStart(6, '0');
                                if(info.fillial_view == true || info.account_sheet == true){
                                    var name = info.chart[i].data_name+" ["+info.chart[i].data_id + "]";
                                }else{
                                    var name = info.chart[i].data_name;
                                }
                                var datavalueproblem = { value: info.chart[i].problem, name: name};
                                colors.push(color);
                                problem.push(info.chart[i].data_problem_credit);
                                names.push(name);
                                dataproblem.push(datavalueproblem);
                            }
                            pieChartjs(names, problem, colors);
                        }
                    }
                });
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

        function pieChartjs(names, values, colors) {
            if ($('#pieproblem').length) {
                var ctx = document.getElementById('pieproblem');
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
                if(pieChartjsproblem == null){
                    pieChartjsproblem = new Chart(ctx, config);
                    $('.pieChartjsproblem').html(pieChartjsproblem.generateLegend());
                }else{

                    if(names == null){
                        pieChartjsproblem.destroy();
                        $('.pieChartjsproblem').html('');
                        pieChartjsproblem = new Chart(ctx, config);
                    }else{
                        pieChartjsproblem.config.data.labels = names;
                        pieChartjsproblem.config.data.datasets[0].data = values;
                        pieChartjsproblem.config.data.datasets[0].backgroundColor = colors;
                        pieChartjsproblem.update();
                        $('.pieChartjsproblem').html(pieChartjsproblem.generateLegend());
                    }
                }
            }
        }
        $(".pieChartjsproblem").on("click", "ul > li", function(e){
            var index = $(this).index();
            $(this).toggleClass("strike");
            console.log(pieChartjsproblem.data.datasets[0])
            var curr = pieChartjsproblem.data.datasets[0]._meta[2].data[index];
            curr.hidden = !curr.hidden;
            pieChartjsproblem.update();
        });
        
        
    })
</script>
@endsection