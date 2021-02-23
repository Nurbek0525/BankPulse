@extends('layouts.app')

@section('content')

<div class="content-w">
    <div class="content-i">
        <div class="content-box">
            <div class="element-wrapper">
                <div class="element-box">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-xl-12">
                            <form method="POST" action="/report/mainbank-inspeksiya/table" enctype="multipart/form-data">
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
                                    <div class="col-md-6" >
                                        <div class="row">
                                            <div class="col-md-3"></div>
                                             <div class="col-md-2">
                                                <div class="form-group">
                                                    <input name="monthyear" readonly="true" class="form-control datepicker"  type="text" placeholder="{{ trans('app.time') }}" value="{{ (!empty($monthyear)?$monthyear:'') }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="submit" class="form-control btn btn-primary btn-block" value="{{ trans('app.show') }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="text" name="search" class="form-control" placeholder="{{ trans('app.search') }}" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="{{trans('app.Export to IMG')}}" class="btn btn-primary btn-square image-button">
                                                                <i class="fa fa-file-picture-o"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="{{trans('app.Export to Excel')}}" class="btn btn-primary btn-square excel-button">
                                                                <i class="fa fa-file-excel-o"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="{{trans('app.Export to PDF')}}" class="btn btn-primary btn-square pdf-button">
                                                                <i class="fa fa-file-pdf-o"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                            </form>
                        </div>
                    </div>
                    
                    <div class="table-responsive" style="width: 100%; display: flex;">
                        <table id="myTable" class="table table-striped table-lightfont">
                            <thead>
                                <tr>
                                <th rowspan="2" width='1%' class="color-black">#</th>
                                    <th rowspan="2" width='10%'class="color-black">{{ trans('app.banks') }}</th>
                                    <th             width='3%' class="color-black">{{ trans('app.average rating of banks') }}</th>
                                    <th colspan="2"  width='4%'>{{ trans('app.changing rate of banks') }}</th>
                                    <th rowspan="2" width="2%" class="color-black" style="text-align: center; padding: 40px 5px 10px 5px;">
                                         <img src="{{ URL::asset('assetsnew/img/kisspng_bracket.png') }}" alt=""/ style="height: 90px; width: auto;">
                                    </th>
                                    <th class="text-center">
                                            <span class="vert-header color-black">{{ trans('app.i out of') }}</span>
                                    </th>
                                    <th class="text-center">
                                            <span class="vert-header color-black">{{ trans('app.i work lost') }}</span>
                                    </th>
                                    <th class="text-center">
                                            <span class="vert-header color-black">{{ trans('app.i likvid active') }}</span>
                                    </th>
                                    <th class="text-center">
                                            <span class="vert-header color-black">{{ trans('app.i likvid credit') }}</span>
                                    </th>
                                    <th class="text-center">
                                            <span class="vert-header color-black">{{ trans('app.i bank liability') }}</span>
                                    </th>
                                    <th class="text-center">
                                            <span class="vert-header color-black">{{ trans('app.i bank liability demand') }}</span>
                                    </th>
                                    <th class="text-center">
                                            <span class="vert-header color-black">{{ trans('app.i net profit') }}</span>
                                    </th>
                                    <th width="6%" class="text-center">
                                            <span class="vert-header color-black">{{ trans('app.i active likvid') }}</span>
                                    </th>
                                    <th width="7%" class="text-center">   
                                            <span class="vert-header color-black">{{ trans('app.i income expense') }}</span>
                                    </th>
                                    <th class="text-center">
                                        <span class="vert-header color-black">{{ trans('app.i others') }}</span>
                                    </th>
                                </tr>
                                
                                <tr style="height: 50px">  
                                    <th>100%</th>  
                                    <th>{{ trans('app.change in rating') }}</th>
                                    <th>{{ trans('app.change in percent') }}</th>
                                    <th>{{ $weight->i_out_of }}%
                                    <a class="sort_href" href="/report/inspeksiya/outof/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                           <span class="os-icon os-icon-zoom-in table-icon-color"></span> 
                                    </a>
                                    </th>
                                    <th>{{ $weight->i_work_lost }}% 
                                        <a class="sort_href" href="/report/inspeksiya/worklost/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                           <span class="os-icon os-icon-zoom-in table-icon-color"></span> 
                                        </a>
                                    </th>
                                    <th>{{ $weight->i_likvid_active }}%
                                        <a class="sort_href" href="/report/inspeksiya/likvidactive/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                           <span class="os-icon os-icon-zoom-in table-icon-color"></span> 
                                        </a>
                                    </th>
                                    <th>{{ $weight->i_likvid_credit }}%
                                        <a class="sort_href" href="/report/inspeksiya/likvidcredit/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                           <span class="os-icon os-icon-zoom-in table-icon-color"></span> 
                                        </a>
                                    </th>
                                    <th>{{ $weight->i_b_liability }}%
                                        <a class="sort_href" href="/report/inspeksiya/liability/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                           <span class="os-icon os-icon-zoom-in table-icon-color"></span> 
                                        </a>
                                    </th>
                                    <th>{{ $weight->i_b_liability_demand }}%
                                        <a class="sort_href" href="/report/inspeksiya/demand/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                           <span class="os-icon os-icon-zoom-in table-icon-color"></span> 
                                        </a>
                                    </th>
                                    <th>{{ $weight->i_net_profit }}%
                                        <a class="sort_href" href="/report/inspeksiya/netprofit/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                           <span class="os-icon os-icon-zoom-in table-icon-color"></span> 
                                        </a>
                                    </th>
                                    <th>{{ $weight->i_active_likvid }}%
                                        <a class="sort_href" href="/report/inspeksiya/activelikvid/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                           <span class="os-icon os-icon-zoom-in table-icon-color"></span> 
                                        </a>
                                    </th>
                                    <th>{{ $weight->i_income_expense }}%
                                        <a class="sort_href" href="/report/inspeksiya/incomeexpense/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                           <span class="os-icon os-icon-zoom-in table-icon-color"></span> 
                                        </a>
                                    </th>
                                    <th>{{ $weight->i_others }}%
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($mainbankss))
                                    @foreach ($mainbankss as $item)
                                        @php
                                            if(!empty($item->i_out_of)){
                                                $i_out_of = $item->i_out_of;
                                            }else{
                                                $i_out_of = new stdClass;
                                                $i_out_of->final_result = 0;
                                            }
                                            if(!empty($item->i_work_lost)){
                                                $i_work_lost = $item->i_work_lost;
                                            }else{
                                                $i_work_lost = new stdClass;
                                                $i_work_lost->final_result = 0;
                                            }
                                            if(!empty($item->i_likvid_active)){
                                                $i_l_active = $item->i_likvid_active;
                                            }else{
                                                $i_l_active = new stdClass;
                                                $i_l_active->final_result = 0;
                                            }
                                            if(!empty($item->i_likvid_credit)){
                                                $i_l_credit = $item->i_likvid_credit;
                                            }else{
                                                $i_l_credit = new stdClass;
                                                $i_l_credit->final_result = 0;
                                            }
                                            if(!empty($item->i_active_likvid)){
                                                $i_a_likvid = $item->i_active_likvid;
                                            }else{
                                                $i_a_likvid = new stdClass;
                                                $i_a_likvid->final_result = 0;
                                            }
                                            if(!empty($item->i_b_liability)){
                                                $i_b_liability = $item->i_b_liability;
                                            }else{
                                                $i_b_liability = new stdClass;
                                                $i_b_liability->final_result = 0;
                                            }
                                            if(!empty($item->i_b_liability_demand)){
                                                $i_b_liability_demand = $item->i_b_liability_demand;
                                            }else{
                                                $i_b_liability_demand = new stdClass;
                                                $i_b_liability_demand->final_result = 0;
                                            }
                                            if(!empty($item->i_net_profit)){
                                                $i_net_profit = $item->i_net_profit;
                                            }else{
                                                $i_net_profit = new stdClass;
                                                $i_net_profit->final_result = 0;
                                            }
                                            if(!empty($item->i_income_expense)){
                                                $i_i_expense = $item->i_income_expense;
                                            }else{
                                                $i_i_expense = new stdClass;
                                                $i_i_expense->final_result = 0;
                                            }
                                            if(!empty($item->i_others)){
                                                $i_others = $item->i_others;
                                            }else{
                                                $i_others = new stdClass;
                                                $i_others->final_result = 0;
                                            }
                                            if(isset($item->rate_diff)){
                                                $rate_diff = $item->rate_diff;
                                                if($rate_diff < 0){
                                                    $rate_diff = $rate_diff*-1;
                                                    $diff_icon = 'down6';
                                                    $diff_color = 'red';
                                                }else{
                                                    $diff_icon = 'up6';
                                                    $diff_color = 'blue';
                                                }
                                            }
                                            if(isset($item->rate_percent)){
                                                $rate_percent = $item->rate_percent;
                                                $rate_percent = $item->rate_percent;
                                                if($rate_percent < 0){
                                                    $rate_percent = $rate_percent*-1;
                                                    $percent_icon = 'down6';
                                                    $percent_color = 'red';
                                                }else{
                                                    $percent_icon = 'up6';
                                                    $percent_color = 'blue';
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td></td>
                                            <td>{{ $item->name }}</td>
                                            <td style="text-align: center">
                                                {{ number_format($item->rate, 2) }}
                                            </td>
                                            <td style="text-align: center">
                                                {{ $rate_diff??'' }}
                                                @if(!empty($diff_icon))
                                                    <i style="color:{{ $diff_color }}" class="os-icon os-icon-arrow-{{$diff_icon}}"></i>
                                                @endif
                                            </td>
                                            <td style="text-align: center">
                                                {{ $rate_percent??'' }}
                                                @if(!empty($percent_icon))
                                                    <i style="color:{{ $percent_color }}" class="os-icon os-icon-arrow-{{$percent_icon}}"></i>
                                                @endif
                                            </td>
                                            <td></td>
                                            <td style="text-align: center; color:{{ $i_out_of->color??'' }}">
                                                {{ number_format($i_out_of->final_result, 2) }}
                                                <a href="/report/inspeksiya/outof/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id??'' }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                                
                                            </td>
                                            <td style="text-align: center; color:{{ $i_work_lost->color??'' }}">
                                                {{ number_format($i_work_lost->final_result, 2) }}
                                                <a href="/report/inspeksiya/worklost/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id??'' }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center; color:{{ $i_l_active->color??'' }}">
                                                {{ number_format($i_l_active->final_result, 2) }}
                                                <a href="/report/inspeksiya/likvidactive/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id??'' }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center; color:{{ $i_l_credit->color??'' }}">
                                                {{ number_format($i_l_credit->final_result, 2) }}
                                                <a href="/report/inspeksiya/likvidcredit/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id??'' }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center; color:{{ $i_b_liability->color??'' }}">
                                                {{ number_format($i_b_liability->final_result, 2) }}
                                                <a href="/report/inspeksiya/liability/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id??'' }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center; color:{{ $i_b_liability_demand->color??'' }}">
                                                {{ number_format($i_b_liability_demand->final_result, 2) }}
                                                <a href="/report/inspeksiya/demand/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id??'' }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center; color:{{ $i_net_profit->color??'' }}">
                                                {{ number_format($i_net_profit->final_result, 2) }}
                                                <a href="/report/inspeksiya/netprofit/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id??'' }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center; color:{{ $i_a_likvid->color??'' }}">
                                                {{ number_format($i_a_likvid->final_result, 2) }}
                                                <a href="/report/inspeksiya/activelikvid/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id??'' }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center; color:{{ $i_i_expense->color??'' }}">
                                                {{ number_format($i_i_expense->final_result, 2) }}
                                                <a href="/report/inspeksiya/incomeexpense/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id??'' }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center">
                                                {{ number_format($i_others->final_result, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                @endif
                            </tbody>
                        </table>
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
        var t = $('#myTable').DataTable({
            "searching": true,
            "paging": false,
            "bInfo": false,
            "scrollY":  800,
            "fixedHeader": {
                header: true,
            },
            "language": {
                "search": '{{ trans('app.search') }}'
            },
            "columnDefs": [
                { "orderable": false, "targets": 5 },
                { "orderable": false, "targets": 0 }
            ],
            "order": [[ 2, "desc" ]],
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'pdfHtml5',
                'csvHtml5'
            ]
        });
        $('.sort_href').click(function(event){
          event.stopPropagation();
        });

        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
        $('input[name="search"]').on( 'keyup', function () {
            t.search($(this).val()).draw();
        } );

        $('input.datepicker').datepicker({
            format:'yyyy-mm',
            startView: 'months',
            minViewMode: 'months',
            autoclose:1,
            startView:'1',
            endDate: new Date()
        });

         $('.btn-square').on('focus', function () {
            $(this).blur()
        });

        $('.image-button').click(function(){
            window.scrollTo(0,0);
            t.destroy();
            t = $('#myTable').DataTable({
                "searching": true,
                "paging": false,
                "bInfo": false,
                "scrollY": false,
                "fixedHeader": {
                    header: true,
                },
                "language": {
                    "search": '{{ trans('app.search') }}'
                },
                "columnDefs": [
                    { "orderable": false, "targets": 5 },
                    { "orderable": false, "targets": 0 }
                ],
                "order": [[ 2, "desc" ]],
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'pdfHtml5',
                    'csvHtml5'
                ]
            });

            if ( ! t.data().any() ) {
               Swal.fire({
                  icon: 'warning',
                  title: 'Пожалуйста заполните таблицу!',
                  iconColor: '#ecaf32',
                  showConfirmButton: true,
                  confirmButtonColor: '#ecaf32',
                  })
            }else{
                var $outer_box = document.querySelector('#myTable_wrapper');

                html2canvas($outer_box, { height: $('#myTable_wrapper').height() + 50}).then(function(canvas) {
                    console.log(canvas);
                    var image = canvas.toDataURL("image/png", 1.0).replace("image/png", "image/octet-stream");
                    image.crossOrigin = 'anonymous';
                    var link = document.createElement('a');
                    link.download = "{{$title}}.png";
                    link.href = image;
                    link.click();
                });
            }
            t.destroy();
            t = $('#myTable').DataTable({
                "searching": true,
                "paging": false,
                "bInfo": false,
                "scrollY":  800,
                "fixedHeader": {
                    header: true,
                },
                "language": {
                    "search": '{{ trans('app.search') }}'
                },
                "columnDefs": [
                    { "orderable": false, "targets": 6 },
                    { "orderable": false, "targets": 0 }
                ],
                "order": [[ 3, "desc" ]],
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'pdfHtml5',
                    'csvHtml5'
                ]
            });
        });
        

       
        $('.excel-button').click(function(){
            if ( ! t.data().any() ) {
               Swal.fire({
                  icon: 'warning',
                  title: 'Пожалуйста заполните таблицу!',
                  iconColor: '#ecaf32',
                  showConfirmButton: true,
                  confirmButtonColor: '#ecaf32',
                  })
            }else{
                var monthyear = $('input[name="monthyear"]').val();
                window.location.href = '{!! url('/export/excel/inspeksiya/inspeksiya') !!}?monthyear='+monthyear;
            }
        });

        $('.pdf-button').click(function(){
            if ( ! t.data().any() ) {
               Swal.fire({
                  icon: 'warning',
                  title: 'Пожалуйста заполните таблицу!',
                  iconColor: '#ecaf32',
                  showConfirmButton: true,
                  confirmButtonColor: '#ecaf32',
                  })
            }else{
                var monthyear = $('input[name="monthyear"]').val();
                window.location.href = '{!! url('/export/pdf/inspeksiya/inspeksiya') !!}?monthyear='+monthyear;
            }
        });
    })
</script>
<style type="text/css">
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