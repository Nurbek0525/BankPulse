@extends('layouts.app')

@section('content')

<div class="content-w">
    <div class="content-i">
        <div class="content-box">
            <div class="element-wrapper">
                <div class="element-box">
                    <div class="row">
                        <div class="col-12">
                            <form method="POST" action="/report/business/table" enctype="multipart/form-data">
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
                                            <div class="col-md-2"></div>
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
                                            
                                            <div class="col-md-3">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="Export to IMG" class="btn btn-primary btn-square image-button">
                                                                <i class="fa fa-file-picture-o"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="Export to Excel"  class="btn btn-primary btn-square excel-button">
                                                                <i class="fa fa-file-excel-o"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="Export to PDF" class="btn btn-primary btn-square pdf-button">
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
                                    <th rowspan="3" width='1%' class="color-black">#</th>
                                    <th rowspan="3" width='25%'class="color-black">{{ trans('app.banks') }}</th>
                                    <th rowspan="3" width='8%'class="color-black">{{ trans('app.mfo id') }}</th>
                                    <th rowspan="2" width='5%'class="color-black">{{ trans('app.average rating of banks') }}</th>
                                    <th rowspan="3" width="3%"class="color-black" style="text-align: center; padding: 40px 5px 10px 5px;">
                                        <img src="{{ URL::asset('assetsnew/img/kisspng_bracket.png') }}" alt=""/ style="height: 90px; width: auto;">
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-center">
                                        <span class="vert-header color-black">{{ trans('app.b past') }}</span>
                                    </th>
                                    <th class="text-center">
                                       <span class="vert-header color-black">{{ trans('app.b guarantee') }}</span>
                                    </th class="text-center">
                                    <th class="text-center">
                                        <span class="vert-header color-black">{{ trans('app.b family') }}</span>
                                     </th>
                                    <th class="text-center">
                                        <span class="vert-header color-black">{{ trans('app.b home') }}</span>
                                    </th>
                                    <th class="text-center">
                                       <span class="vert-header color-black">{{ trans('app.b kontur') }}</span>
                                    </th>
                                    <th class="text-center">
                                         <span class="vert-header color-black">{{ trans('app.b execution') }}</span>
                                    </th>
                                    <th class="text-center">
                                       <span class="vert-header color-black">{{ trans('app.b monthly report') }}</span>
                                    </th>
                                </tr>
                                <tr>
                                    <th>100%</th>
                                    <th>{{ $weight->b_past }}%
                                        <a href="/report/business/past/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                            <span class="os-icon os-icon-zoom-in table-icon-color"></span>
                                        </a>
                                    </th>
                                    <th>{{ $weight->b_guarantee }}%
                                         <a href="/report/business/guarantee/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                            <span class="os-icon os-icon-zoom-in table-icon-color"></span>
                                        </a>
                                    </th>
                                    <th>{{ $weight->b_family }}%
                                         <a href="/report/business/family/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                            <span class="os-icon os-icon-zoom-in table-icon-color"></span>
                                        </a>
                                    </th>
                                    <th>{{ $weight->b_home }}%
                                         <a href="/report/business/home/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                            <span class="os-icon os-icon-zoom-in table-icon-color"></span>
                                        </a>
                                    </th>
                                    <th>{{ $weight->b_kontur }}%
                                        <a href="/report/business/kontur/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                            <span class="os-icon os-icon-zoom-in table-icon-color"></span>
                                        </a>
                                    </th>
                                    <th>{{ $weight->b_execution }}%
                                        <a href="/report/business/execution/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                            <span class="os-icon os-icon-zoom-in table-icon-color"></span>
                                        </a>
                                    </th>
                                    <th>{{ $weight->b_m_report }}%
                                        <a href="/report/business/monthly/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                            <span class="os-icon os-icon-zoom-in table-icon-color"></span>
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($reports))
                                    @foreach ($reports as $item)

                                    @php
                                        if(!empty($item->b_past)){
                                            $past = json_decode($item->b_past);
                                        }else{
                                            $past = new stdClass;
                                            $past->final_result = 0;
                                        }
                                        if(!empty($item->b_kontur)){
                                            $kontur = json_decode($item->b_kontur);
                                        }else{
                                            $kontur = new stdClass;
                                            $kontur->final_result = 0;
                                        }
                                        if(!empty($item->b_family)){
                                            $family = json_decode($item->b_family);
                                        }else{
                                            $family = new stdClass;
                                            $family->final_result = 0;
                                        }
                                        if(!empty($item->b_guarantee)){
                                            $guarantee = json_decode($item->b_guarantee);
                                        }else{
                                            $guarantee = new stdClass;
                                            $guarantee->final_result = 0;
                                        }
                                        if(!empty($item->b_home)){
                                            $home = json_decode($item->b_home);
                                        }else{
                                            $home = new stdClass;
                                            $home->final_result = 0;
                                        }
                                        if(!empty($item->b_m_report)){
                                            $report = json_decode($item->b_m_report);
                                        }else{
                                            $report = new stdClass;
                                            $report->final_result = 0;
                                        }
                                        if(!empty($item->b_execution)){
                                            $execution = json_decode($item->b_execution);
                                        }else{
                                            $execution = new stdClass;
                                            $execution->final_result = 0;
                                        }
                                    @endphp
                                        <tr>
                                            <td></td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ generateMfo($item->mfo_id) }}</td>
                                            <td style="text-align: center">
                                                {{ number_format(getAveragebusiness($home->final_result, $kontur->final_result, $family->final_result, $guarantee->final_result, $past->final_result, $report->final_result, $execution->final_result, $monthyear, $item->bank_id), 2) }}
                                            </td>
                                            <td></td>
                                            <td style="text-align: center; color:{{ $past->color ?? '#000' }}">
                                                {{ number_format($past->final_result, 2) }}
                                                <a href="/report/business/past/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center; color:{{ $guarantee->color ?? '#000' }}">
                                                {{ number_format($guarantee->final_result, 2) }}
                                                <a href="/report/business/guarantee/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center; color:{{ $family->color ?? '#000' }}">
                                                {{ number_format($family->final_result, 2) }}
                                                <a href="/report/business/family/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center; color:{{ $home->color ?? '#000' }}">
                                                {{ number_format($home->final_result, 2) }}
                                                <a href="/report/business/home/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center; color:{{ $kontur->color ?? '#000' }}">
                                                {{ number_format($kontur->final_result, 2) }}
                                                <a href="/report/business/kontur/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center; color:{{ $execution->color ?? '#000' }}">
                                                {{ number_format($execution->final_result, 2) }}
                                                <a href="/report/business/execution/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center; color:{{ $report->color ?? '#000' }}">
                                                {{ number_format($report->final_result, 2) }}
                                                <a href="/report/business/monthly/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id }}">
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
<div class="outer-table" style="display:none">
    <div id="table-header">
        <img src="" alt="">
    </div>
    <div id="table-body">
        <img src="" alt="">
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
            "scrollY":  '800px',
            "fixedHeader": {
                header: true,
            },
            "columnDefs": [
                { "orderable": false, "targets": 4 }
            ],
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
        var table_head = html2canvas(document.querySelector(".dataTables_scrollHead div table thead"), {allowTaint:true, useCORS: true}).then(function(canvas) {
            console.log(canvas);
            //document.querySelector("#table-header").appendChild(canvas);
            var image = canvas.toDataURL("image/png");
            image.split(',')[1];
            $("#table-header img").attr('src', image);
        });

        var table_body = html2canvas(document.querySelector("#myTable"), {allowTaint:true, useCORS: true}).then(function(canvas) {
            console.log(canvas);
            //document.querySelector("#table-body").appendChild(canvas);
            var image = canvas.toDataURL("image/png");
            image.split(',')[1];
            $("#table-body img").attr('src', image);
            // return image;
        });

        $('.image-button').click(function(){
            $('.outer-table').css('display', 'block');
            var outer_box = document.querySelector('.outer-table');
            html2canvas(outer_box, {allowTaint:true, useCORS: true, logging: true}).then(function(canvas) {
                console.log(canvas);
                //document.querySelector(".outer-table").appendChild(canvas);
                var image = canvas.toDataURL("image/png", 1.0).replace("image/png", "image/octet-stream");
                //image.split(',')[1];
                //console.log(image);
                image.crossOrigin = 'anonymous';
                var link = document.createElement('a');
                link.download = "my-imag.png";
                link.href = image;
                link.click();
            });
            $('.outer-table').css('display', 'none');
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