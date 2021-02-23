@extends('layouts.app')

@section('content')

<div class="content-w">
    <div class="content-i">
        <div class="content-box">
            <div class="element-wrapper">
                <div class="element-box">
                    <div class="row">
                        <div class="col-12">
                            <form method="POST" action="/report/ijro/table" enctype="multipart/form-data">
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
                                                            <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="{{trans('app.Export to IMG')}}" 
                                                            class="btn btn-primary btn-square image-button">
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
                                                            <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="{{trans('app.Export to PDF')}}F" class="btn btn-primary btn-square pdf-button">
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
                                    <th rowspan="2" width='1%'>#</th>
                                    <th rowspan="2" width='25%'class="color-black">{{ trans('app.banks') }}</th>
                                    <th rowspan="2" width='5%'class="color-black">{{ trans('app.mfo id') }}</th>
                                    <th rowspan="2" width='5%'class="color-black">{{ trans('app.average rating of banks') }}</th>
                                    <th colspan="2"  width='8%'>{{ trans('app.changing rate of banks') }}</th>
                                    <th rowspan="2" width="3%" class="color-black" style="text-align: center; padding: 40px 5px 10px 5px;">
                                        <img src="{{ URL::asset('assetsnew/img/kisspng_bracket.png') }}" alt=""/ style="height: 90px; width: auto;">
                                    </th>
                                    <th rowspan="2" class="color-black"><span>{{ trans('app.ijro meeting execution number') }}</span></th>
                                    <th rowspan="2" class="color-black"><span>{{ trans('app.ijro letter execution number') }}</span></th>
                                    <th rowspan="2" class="color-black"><span>{{ trans('app.ijro head number') }}</span></th>
                                    <th rowspan="2" class="color-black"><span>{{ trans('app.ijro people qabul number') }}</th>
                                    <th rowspan="2" class="color-black"><span>{{ trans('app.ijro prime number') }}</span></th>
                                    <th rowspan="2" class="color-black"><span>{{ trans('app.ijro out of number') }}</span></th>
                                </tr>
                                
                                <tr style="height: 50px">
                                    <th class="color-black"><span>{{ trans('app.change in rating') }}</span></th>
                                    <th class="color-black"><span>{{ trans('app.change in percent') }}</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($reports))
                                    @foreach ($reports as $item)
                                        <tr>
                                            <td></td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ generateMfo($item->mfo_id) }}</td>
                                            @php
                                                $json = json_decode($item->ijro);
                                                $meeting_execution = (empty($json->meeting_execution))?0:$json->meeting_execution;
                                                $letter_execution = (empty($json->letter_execution))?0:$json->letter_execution;
                                                $head_number = (empty($json->head_number))?0:$json->head_number;
                                                $people_qabul = (empty($json->people_qabul))?0:$json->people_qabul;
                                                $prime_number = (empty($json->prime_number))?0:$json->prime_number;
                                                $out_of_number = (empty($json->out_of_number))?0:$json->out_of_number;
                                                if(!empty($item->ijro)){
                                                    $average = getAverageijro($meeting_execution, $letter_execution, $head_number, $people_qabul, $prime_number, $out_of_number, $monthyear, $item->bank_id);
                                                }else{
                                                    $average = 0;
                                                }
                                                $average = number_format($average, 2);
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
                                            <td style="text-align: center">
                                                {{ $average }}
                                            </td>
                                            
                                            <td style="text-align: center">
                                                {{ $rate_diff??'' }}
                                                @if(!empty($diff_icon))
                                                    <i style="color:{{ $diff_color }}" class="os-icon os-icon-arrow-{{$diff_icon}}"></i>
                                                @endif
                                            </td>
                                            <td style="text-align: center">
                                                {{ $rate_percent?number_format($rate_percent, 2):'' }}
                                                @if(!empty($percent_icon))
                                                    <i style="color:{{ $percent_color }}" class="os-icon os-icon-arrow-{{$percent_icon}}"></i>
                                                @endif
                                            </td>
                                            <td></td>
                                            <td style="text-align: center">
                                                {{ $meeting_execution }}
                                            </td>
                                            <td style="text-align: center">
                                                {{ $letter_execution }}
                                            </td>
                                            <td style="text-align: center">
                                                {{ $head_number }}
                                            </td>
                                            <td style="text-align: center">
                                                {{ $people_qabul }}
                                            </td>
                                            <td style="text-align: center">
                                                {{ $prime_number }}
                                            </td>
                                            <td style="text-align: center">
                                                {{ $out_of_number }}
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
            "scrollY": 800,
            "fixedHeader": {
                header: true,
            },
            "columnDefs": [
                { "orderable": false, "targets": 6 },
                { "orderable": false, "targets": 0 }
            ],
            "language": {
                "search": '{{ trans('app.search') }}'
            },
            "order": [[ 3, 'desc' ]] ,
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
                "columnDefs": [
                    { "orderable": false, "targets": 6 },
                    { "orderable": false, "targets": 0 }
                ],
                "language": {
                    "search": '{{ trans('app.search') }}'
                },
                "order": [[ 3, 'desc' ]] ,
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
                "scrollY": 800,
                "fixedHeader": {
                    header: true,
                },
                "columnDefs": [
                    { "orderable": false, "targets": 6 },
                    { "orderable": false, "targets": 0 }
                ],
                "language": {
                    "search": '{{ trans('app.search') }}'
                },
                "order": [[ 3, 'desc' ]] ,
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
                $('.buttons-excel').click();
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
                $('.buttons-pdf').click();
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