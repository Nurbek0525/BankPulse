@extends('layouts.app')

@section('content')

<div class="content-w">
    <div class="content-i">
        <div class="content-box">
            <div class="element-wrapper">
                <div class="element-box">
                    <div class="row">
                        <div class="col-12">
                            <form method="POST" action="/report/inspeksiya/outof/table" enctype="multipart/form-data">
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
                                    <th rowspan="2" width='25%'class="color-black">{{ trans('app.banks') }}</th>
                                    <th rowspan="2" width='10%'class="color-black">{{ trans('app.mfo id') }}</th>
                                    <th rowspan="2" width='25%'class="color-black">{{ trans('app.average rating of banks') }}</th>
                                    
                                </tr>
                                <tr>
                                    <th class="color-black">{{ trans('app.allcredit') }}</th>
                                    <th class="color-black">{{ trans('app.problemcredit') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($reports))
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($reports as $item)
                                        @php
                                            $data = json_decode($item->i_out_of);
                                            if(!empty($data)){
                                                $allcredit = $data->allcredit;
                                                $problemcredit = $data->problemcredit;
                                                $out_of_percent = ((empty($allcredit))?0:($problemcredit/$allcredit))*100;
                                                if($out_of_percent < 0.1000 && $out_of_percent >= 0){
                                                    $rate = 100*($weight->i_out_of/100);
                                                }elseif($out_of_percent >= 0.10001 && $out_of_percent <= 0.50099){
                                                    $rate = 95*($weight->i_out_of/100);
                                                }elseif($out_of_percent > 0.50001 && $out_of_percent < 1.00099){
                                                    $rate = 90*($weight->i_out_of/100);
                                                }elseif($out_of_percent >= 1.00001 && $out_of_percent < 2.00099){
                                                    $rate = 80*($weight->i_out_of/100);
                                                }elseif($out_of_percent >= 2.00001 && $out_of_percent < 3.00099){
                                                    $rate = 70*($weight->i_out_of/100);
                                                }elseif($out_of_percent >= 3.00001 && $out_of_percent < 4.00099){
                                                    $rate = 60*($weight->i_out_of/100);
                                                }elseif($out_of_percent >= 4.00001 && $out_of_percent < 5.00099){
                                                    $rate = 50*($weight->i_out_of/100);
                                                }elseif($out_of_percent >= 5){
                                                    $rate = 0.00001*($weight->i_out_of/100);
                                                } 
                                            }else{
                                                $allcredit = 0;
                                                $problemcredit = 0;
                                                $rate = 0;
                                            }
                                            
                                        @endphp
                                        <tr id="{{ generateMfo($item->mfo_id) }}">
                                            <td>{{ $i }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td style="text-align: center">{{ generateMfo($item->mfo_id) }}</td>
                                            <td style="text-align: center">
                                                {{ number_format($rate, 2, ',', '.') }}   
                                            </td>
                                            <td class="amount">{{ (!empty($allcredit))?number_format(($allcredit), 0, '.', ' '):0 }}</td>
                                            <td class="amount">{{ (!empty($problemcredit))?number_format(($problemcredit), 0, '.', ' '):0 }}</td>
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
</main>
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
            "columnDefs": [
                { "orderable": false, "targets": 0 }
            ],
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