@extends('layouts.app')

@section('content')

<div class="content-w">
    <div class="content-i">
        <div class="content-box">
            <div class="element-wrapper">
                <div class="element-box">
                    <div class="row">
                        <div class="col-12">
                            <form method="POST" action="/report/bussiness/table" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                <h5 class="form-header">
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
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-5">
                                                        <div class="form-group" style="margin-top: 5px">
                                                            <a href="javascript:void(0)" class="btn btn-primary btn-square excel-button">
                                                                <i class="fa fa-file-excel-o"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group" style="margin-top: 5px">
                                                            <a href="javascript:void(0)" class="btn btn-primary btn-square pdf-button">
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
                    <div class="table-responsive">
                        <table id="myTable" class="table table-striped table-lightfont">
                            <thead>
                                <tr>
                                    <th rowspan="3" width='5%'>#</th>
                                    <th rowspan="3" width='20%'>{{ trans('app.banks') }}</th>
                                    <th rowspan="3" width='10%'>{{ trans('app.mfo id') }}</th>
                                    <th rowspan="3" width='10%'>{{ trans('app.average rating of banks') }}</th>
                                    <th colspan="7" width="55%" style="text-align: center">{{ trans('app.from') }}</th>
                                </tr>
                                <tr>
                                    <th class="text-center">
                                        <a href="/report/bussiness/past/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                            <span class="vert-header">{{ trans('app.b past') }}</span>
                                        </a>
                                    </th>
                                    <th class="text-center">
                                        <a href="/report/bussiness/guarantee/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                            <span class="vert-header">{{ trans('app.b guarantee') }}</span>
                                        </a>
                                    </th class="text-center">
                                    <th class="text-center">
                                        <a href="/report/bussiness/family/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                            <span class="vert-header">{{ trans('app.b family') }}</span>
                                        </a>
                                    </th>
                                    <th class="text-center">
                                        <a href="/report/bussiness/home/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                            <span class="vert-header">{{ trans('app.b home') }}</span>
                                        </a>
                                    </th>
                                    <th class="text-center">
                                        <a href="/report/bussiness/kontur/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                            <span class="vert-header">{{ trans('app.b kontur') }}</span>
                                        </a>
                                    </th>
                                    <th class="text-center">
                                        <a href="/report/bussiness/execution/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                            <span class="vert-header">{{ trans('app.b execution') }}</span>
                                        </a>
                                    </th>
                                    <th class="text-center">
                                        <a href="/report/bussiness/monthly/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                            <span class="vert-header">{{ trans('app.b monthly report') }}</span>
                                        </a>
                                    </th>
                                </tr>
                                <tr>
                                    <th>{{ $weight->b_past }}</th>
                                    <th>{{ $weight->b_guarantee }}</th>
                                    <th>{{ $weight->b_family }}</th>
                                    <th>{{ $weight->b_home }}</th>
                                    <th>{{ $weight->b_kontur }}</th>
                                    <th>{{ $weight->b_execution }}</th>
                                    <th>{{ $weight->b_m_report }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($reports))
                                    @foreach ($reports as $item)

                                    @php
                                        if(!empty($item->b_past)){
                                            $past = json_decode($item->b_past)->final_result;
                                        }else{
                                            $past = null;
                                        }
                                        if(!empty($item->b_kontur)){
                                            $kontur = json_decode($item->b_kontur)->final_result;
                                        }else{
                                            $kontur = null;
                                        }
                                        if(!empty($item->b_family)){
                                            $family = json_decode($item->b_family)->final_result;
                                        }else{
                                            $family = null;
                                        }
                                        if(!empty($item->b_guarantee)){
                                            $guarantee = json_decode($item->b_guarantee)->final_result;
                                        }else{
                                            $guarantee = null;
                                        }
                                        if(!empty($item->b_home)){
                                            $home = json_decode($item->b_home)->final_result;
                                        }else{
                                            $home = null;
                                        }
                                        if(!empty($item->b_m_report)){
                                            $report = json_decode($item->b_m_report)->final_result;
                                        }else{
                                            $report = null;
                                        }
                                        if(!empty($item->b_execution)){
                                            $execution = json_decode($item->b_execution)->final_result;
                                        }else{
                                            $execution = null;
                                        }
                                    @endphp
                                        <tr>
                                            <td></td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ generateMfo($item->mfo_id) }}</td>
                                            <td style="text-align: center">
                                                {{ number_format(getAveragebussiness($home, $kontur, $family, $guarantee, $past, $report, $execution, $monthyear, $item->bank_id), 2) }}
                                            </td>
                                            <td style="text-align: center">
                                                {{ number_format($past, 2) }}
                                                <a href="/report/bussiness/past/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center">
                                                {{ number_format($guarantee, 2) }}
                                                <a href="/report/bussiness/guarantee/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center">
                                                {{ number_format($family, 2) }}
                                                <a href="/report/bussiness/family/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center">
                                                {{ number_format($home, 2) }}
                                                <a href="/report/bussiness/home/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center">
                                                {{ number_format($kontur, 2) }}
                                                <a href="/report/bussiness/kontur/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center">
                                                {{ number_format($execution, 2) }}
                                                <a href="/report/bussiness/execution/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center">
                                                {{ number_format($report, 2) }}
                                                <a href="/report/bussiness/monthly/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
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
<script>
    $('document').ready(function(){
        var t = $('#myTable').DataTable({
            "searching": true,
            "paging": false,
            "bInfo": false,
            "scrollY":  '800px',
            "fixedHeader": {
                header: true,
            },
            "language": {
                "search": '{{ trans('app.search') }}'
            },
            "order": [[ 3, "desc" ]],
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

        $('input.datepicker').datepicker({
            format:'yyyy-mm',
            startView: 'months',
            minViewMode: 'months',
            autoclose:1,
            startView:'1',
            endDate: new Date()
        });

        $('.excel-button').click(function(){
            $('.buttons-excel').click();
        });
        $('.pdf-button').click(function(){
            $('.buttons-pdf').click();
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