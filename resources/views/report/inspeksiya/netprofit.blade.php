@extends('layouts.app')

@section('content')

<div class="content-w">
    <div class="content-i">
        <div class="content-box">
            <div class="element-wrapper">
                <div class="element-box">
                    <div class="row">
                        <div class="col-12">
                            <form method="POST" action="/report/inspeksiya/netprofit/table" enctype="multipart/form-data">
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
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <a href="javascript:void(0)" class="btn btn-primary btn-square excel-button width-btn">
                                                                <i class="fa fa-file-excel-o"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <a href="javascript:void(0)" class="btn btn-primary btn-square pdf-button width-btn">
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
                                    <th rowspan="2" width='2%' class="color-black">#</th>
                                    <th rowspan="2" width='25%' class="color-black">{{ trans('app.banks') }}</th>
                                    <th rowspan="2" width='10%'class="color-black" >{{ trans('app.mfo id') }}</th>
                                    <th rowspan="2" width='25%'class="color-black">{{ trans('app.average rating of banks') }}</th>
                                    {{-- <th colspan="4" width="35%"class="color-black" style="text-align: center">{{ trans('app.from') }}</th> --}}
                                </tr>
                                <tr>
                                    <th class="color-black">{{ trans('app.all active') }}</th>
                                    <th class="color-black">{{ trans('app.average profit') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($reports))
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($reports as $item)
                                        @php
                                            $data = json_decode($item->i_net_profit);
                                            if(!empty($data)){
                                                $exist_case = $data->exist_case;
                                                $aver_profit = $data->aver_profits;
                                                $allactive = $data->allactive;
                                                $net_percent = (empty($allactive))?0:($aver_profit/$allactive)*100;
                                                $net_percent = ($net_percent < 0 )?0:$net_percent;
                                                $rate = $net_percent*$weight->i_net_profit;
                                            }else{
                                                $aver_profit = 0;
                                                $allactive = 0;
                                                $rate = 0;
                                            }
                                            
                                        @endphp
                                        <tr id="{{ generateMfo($item->mfo_id) }}">
                                            <td>{{ $i }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td style="text-align: center">{{ generateMfo($item->mfo_id) }}</td>
                                            <td style="text-align: center">{{ number_format($rate, 2, '.', ' ') }}</td>
                                            <td class="amount">{{ (!empty($allactive))?number_format($allactive, 0, '.', ' '):0 }}</td>
                                            <td class="amount">{{ (!empty($aver_profit))?number_format($aver_profit, 0, '.', ' '):0 }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
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
<script src="{{ URL::asset('/assets/js/jquery-3.3.1.min.js') }}"></script>
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
            "columnDefs": [
                { "orderable": false, "targets": 0 }
            ],
            "language": {
                "search": '{{ trans('app.search') }}'
            },
            "order": [[ 1, 'asc' ]] ,
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