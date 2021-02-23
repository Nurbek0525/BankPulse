@extends('layouts.app')

@section('content')

<div class="content-w">
    <div class="content-i">
        <div class="content-box">
            <div class="element-wrapper">
                <div class="element-box">
                    @if(!empty($type) && $type == 'error')
                        <div class="element-box-content">
                        <div class="alert alert-danger" role="alert"><strong>{{ trans('app.'.$type) }}</strong> {{ $message }}</div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-12">
                            <form method="POST" action="/report/mainbanks/table" enctype="multipart/form-data">
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
                                            {{-- <div aria-hidden="true" class="onboarding-modal modal fade animated" id="onboardingSlideModal" role="dialog" tabindex="-1"><div class="modal-dialog modal-centered" role="document">
                                                    <div class="lds-spinner">
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                    </div>
                                                </div>
                                            </div>  --}}
                                           
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
                                                            class="btn btn-primary btn-square image-button" >
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
                    
                    <div class="table-responsive">
                        <table id="myTable" class="table table-striped table-lightfont">
                            <thead>
                                <tr>
                                    <th rowspan="2" width='1%'>#</th>
                                    <th rowspan="2" width='20%'>{{ trans('app.banks') }}</th>
                                    <th  width='5%'>{{ trans('app.average rating of banks') }}</th>
                                    <th colspan="2" width='10%'>{{ trans('app.changing rate of banks') }}</th>
                                    <th rowspan="2" width="3%" style="text-align: center;  padding: 40px 5px 10px 5px;">
                                         <img src="{{ URL::asset('assetsnew/img/kisspng_bracket.png') }}" alt=""/ style="height: 90px; width: auto;">
                                    </th>
                                    <th width="10%" class="text-center">
                                       <span>{{ trans('app.cash report') }}</span>
                                    </th>
                                    <th width="10%" class="text-center">
                                        <span>{{ trans('app.business report') }}</span>
                                    </th>
                                    <th width="10%" class="text-center">
                                       <span>{{ trans('app.inspeksiya report') }}</span>
                                    </th>
                                    <th width="10%" class="text-center">
                                       <span>{{ trans('app.currency report') }}</span>
                                    </th>
                                    <th width="10%" class="text-center">
                                       <span>{{ trans('app.ijro report') }}</span>
                                    </th>
                                </tr>
                                <tr>
                                    @if(!empty($weight))
                                        <th>100%</th>
                                        <th >{{ trans('app.change in rating') }}</th>
                                        <th>{{ trans('app.change in percent') }}</th>
                                        <th>{{ $weight->cash }}%
                                            <a class="sort_href" href="/report/mainbank-cash/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                                <span class="os-icon os-icon-zoom-in table-icon-color"></span>
                                            </a>
                                        </th>
                                        <th>{{ $weight->business }}%
                                            <a class="sort_href" href="/report/mainbank-business/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                                <span class="os-icon os-icon-zoom-in table-icon-color"></span>
                                            </a>
                                        </th>
                                        <th>{{ $weight->inspeksiya }}%
                                            <a class="sort_href" href="/report/mainbank-inspeksiya/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                                <span class="os-icon os-icon-zoom-in table-icon-color"></span>
                                            </a>
                                        </th>
                                        <th>{{ $weight->currency }}%
                                            <a class="sort_href" href="/report/mainbank-currency/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                                <span class="os-icon os-icon-zoom-in table-icon-color"></span>
                                            </a>
                                        </th>
                                        <th>{{ $weight->ijro_head }}%
                                            <a class="sort_href" href="/report/ijro/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}">
                                                <span class="os-icon os-icon-zoom-in table-icon-color"></span>
                                            </a>
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($mainbankss))
                                    @foreach ($mainbankss as $item)
                                    @php
                                        if(!empty($item->cash)){
                                            $cash = $item->cash;
                                        }else{
                                            $cash = new stdClass;
                                            $cash->final_result = 0;
                                        }
                                        if(!empty($item->inspeksiya)){
                                            $inspeksiya = $item->inspeksiya;
                                        }else{
                                            $inspeksiya = new stdClass;
                                            $inspeksiya->final_result = 0;
                                        }
                                        if(!empty($item->business)){
                                            $business = $item->business;
                                        }else{
                                            $business = new stdClass;
                                            $business->final_result = 0;
                                        }
                                        if(!empty($item->currency)){
                                            $currency = $item->currency;
                                        }else{
                                            $currency = new stdClass;
                                            $currency->final_result = 0;
                                        }
                                        if(!empty($item->ijro)){
                                            $ijro = $item->ijro;
                                        }else{
                                            $ijro = new stdClass;
                                            $ijro->final_result = 0;
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
                                            <td style="text-align: center; color:{{ $cash->color??'#000' }}">
                                                {{ number_format($cash->final_result, 2) }}
                                                <a href="/report/cash/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id??'' }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center; color:{{ $business->color??'#000' }}">
                                                {{ number_format($business->final_result, 2) }}
                                                <a href="/report/business/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id??'' }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center; color:{{ $inspeksiya->color??'#000' }}">
                                                {{ number_format($inspeksiya->final_result, 2) }}
                                                <a href="/report/inspeksiya/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id??'' }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center; color:{{ $currency->color??'#000' }}">
                                                {{ number_format($currency->final_result, 2) }}
                                                <a href="/report/currency/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id??'' }}">
                                                    <span class="os-icon os-icon-zoom-in" style="padding: 0"></span> 
                                                </a>
                                            </td>
                                            <td style="text-align: center; color:{{ $ijro->color??'#000' }}">
                                                {{ number_format($ijro->final_result, 2) }}
                                                <a href="/report/ijro/table{{ (!empty($monthyear))?'?monthyear='.$monthyear:'' }}&mfo={{ $item->mfo_id??'' }}">
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
<script src="{{ URL::asset('assetsnew/js/html5Canvas.js') }}"></script>
<script>
    $('document').ready(function(){
        $('.floated-colors-btn').on('click', function(e){
            var generate = $('input[name="generate"]');
            generate.val(1);
            $('input[type="submit"]').click();

        })
        var t = $('#myTable').DataTable({
            "searching": true,
            "paging": false,
            "bInfo": false,
            "scrollY":  '500px',
            "fixedHeader": {
                header: true,
            },
            "columnDefs": [
                { "orderable": false, "targets": 5 },
                { "orderable": false, "targets": 0 }
            ],
            "language": {
                "search": '{{ trans('app.search') }}'
            },
            "order": [[ 2, "desc" ]],
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

        $('.sort_href').click(function(event){
          event.stopPropagation();
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
                "scrollY":  false,
                "fixedHeader": {
                    header: true,
                },

                "columnDefs": [
                    { "orderable": false, "targets": 5 },
                    { "orderable": false, "targets": 0 }
                ],

                "language": {
                    "search": '{{ trans('app.search') }}'
                },

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
                "scrollY": 500,
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
                window.location.href = '{!! url('/export/excel/monthly-all-rating') !!}?monthyear='+monthyear;
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
                window.location.href = '{!! url('/export/pdf/monthly-all-rating') !!}?monthyear='+monthyear;
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