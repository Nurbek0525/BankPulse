@extends('layouts.app')

@section('content')

<div class="content-w">
    <div class="content-i">
        <div class="content-box">
            <div class="element-wrapper">
                <div class="element-box">
                    <div class="row">
                        <div class="col-12">
                            <form method="POST" action="/report/bussiness/family/table" enctype="multipart/form-data">
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
                                    <th rowspan="2" width='5%'>#</th>
                                    <th rowspan="2" width='25%'>{{ trans('app.banks') }}</th>
                                    <th rowspan="2" width='10%'>{{ trans('app.mfo id') }}</th>
                                    <th rowspan="2" width='25%'>{{ trans('app.average rating of banks') }}</th>
                                    <th colspan="4" width="35%" style="text-align: center">{{ trans('app.from') }}</th>
                                </tr>
                                <tr>
                                    <th>{{ trans('app.credit approved not given') }}</th>
                                    <th>{{ trans('app.product delivered not credit given') }}</th>
                                    <th>{{ trans('app.money exist credit not given') }}</th>
                                    <th>{{ trans('app.credit given in wrong way') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($reports))
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($reports as $item)
                                        @php
                                            $data = json_decode($item->b_family);
                                            $array_filter = (object) array_filter((array) $data, 'strlen');
                                        @endphp
                                        <tr id="{{ generateMfo($item->mfo_id) }}">
                                            <td>{{ $i }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ generateMfo($item->mfo_id) }}</td>
                                            <td style="text-align: center">{{ (count((array) $array_filter) > 1)?number_format($data->exist_case - ($data->credit_50 + $data->credit_50_60 + $data->credit_60_70 + $data->credit_70_80), 2, '.', ','):0 }}</td>
                                            <td style="text-align: center">{{ (count((array) $array_filter) > 1)?number_format(($data->credit_50), 0, '.', ','):'' }}</td>
                                            <td style="text-align: center">{{ (count((array) $array_filter) > 1)?number_format(($data->credit_50_60), 0, '.', ','):'' }}</td>
                                            <td style="text-align: center">{{ (count((array) $array_filter) > 1)?number_format(($data->credit_60_70), 0, '.', ','):'' }}</td>
                                            <td style="text-align: center">{{ (count((array) $array_filter) > 1)?number_format(($data->credit_70_80), 0, '.', ','):'' }}</td>
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