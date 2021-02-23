@extends('layouts.app')

@section('content')
@php
    $user = Auth::user();
@endphp
<div class="content-w height">
    <div class="content-i">
        <div class="content-box">
            <div class="element-wrapper">
                <div class="element-box">
                    <div class="row">
                        <div class="col-md-8">
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
                        <div class="col-md-4" style="text-align: right;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                    @if($type == 'main')
                                    <a href="/bank/add?type=main" class="btn btn-primary btn-square excel-button">
                                        <i class="fa fa-plus"></i>
                                        <span style="margin-left: 5px; text-transform: capitalize;">{{ trans('app.add') }}</span>
                                    </a>
                                    @else
                                    <a href="/bank/add?type=fill" class="btn btn-primary btn-square excel-button">
                                        <i class="fa fa-plus"></i>
                                        <span style="margin-left: 5px; text-transform: capitalize;">{{ trans('app.add') }}</span>
                                    </a>
                                    @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="search" class="form-control " placeholder="{{ trans('app.search') }}" />
                                     </div>
                                </div> 

                            </div>
                        </div>   
                    </div>
                    <div class="table-responsive">
                        @if($type == 'main')
                            <table id="myTable" class="table table-striped table-lightfont">
                                <thead>
                                    <tr>
                                        <th width='5%'>#</th>
                                        <th width='25%'>{{ trans('app.bank name') }}</th>
                                        <th width='25%'>{{ trans('app.phone') }}</th>
                                        <th width='30%'>{{ trans('app.address') }}</th>
                                        <th width='20%'>{{ trans('app.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($banks))
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($banks as $item)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td style="text-align: center;">{{ $item->phone }}</td>
                                                <td>{{ $item->address }}</td>
                                                <td>
                                                    <a href="/bank/edit/{{ $item->id }}/main" class="btn edit-btn btn-success btn-square">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="/bank/delete/{{ $item->id }}/main" class="btn edit-btn btn-error btn-square">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                        
                                    @endif
                                </tbody>
                            </table>
                        @else
                            @if($user == 'admin')
                                <div class="row">
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>{{ trans('app.select region') }}</label> 
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="selectpicker rounded" name="region" data-live-search="true">
                                                <option value="" selected disabled hidden>{{ trans('app.select region') }}</option>
                                                <option value="all">{{ trans('app.all') }}</option>
                                                @if(!empty($regions))
                                                    @foreach($regions as $region)
                                                        <option value="{{ $region->name }}">{{ $region->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <table id="myTable" width="100%" class="table table-striped table-lightfont">
                                <thead>
                                    <tr>
                                        <th width="1%">#</th>
                                        <th width="20%">{{ trans('app.fillial name') }}</th>
                                        <th width="10%">{{ trans('app.mfo id') }}</th>
                                        <th width="10%">{{ trans('app.Telefon raqam') }}</th>
                                        <th width="15%">{{ trans('app.viloyat/shaxar') }}</th>
                                        <th width="29%">{{ trans('app.address') }}</th>
                                        <th width="15%">{{ trans('app.harakat') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($banks))
                                        @foreach ($banks as $item)
                                            <tr>
                                                <td></td>
                                                <td>{{ $item->short_name }}</td>
                                                <td>{{ generateMfo($item->mfo_id) }}</td>
                                                <td>{{ $item->phone }}</td>
                                                <td>{{ $item->region_name }}</td>
                                                <td>{{ $item->index.' '.$item->address}}</td>
                                                <td>
                                                    <a href="/bank/edit/{{ $item->id }}/fill" class="btn edit-btn btn-success btn-square">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="/bank/delete/{{ $item->id }}/fill" class="btn edit-btn btn-danger btn-square">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        @endif
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
            "paging": true,
            "bInfo": true,
            "pageLength": 20,
            "language": {
                "infoFiltered": "",
                "search": '{{ trans('app.search') }}',
                "lengthMenu": '{{ trans('app.showing').' _MENU_ '.trans('app.from elements') }}',
                "info": '{{ trans('app.showing from').' _PAGE_ '.trans('app.from page').' _PAGES_' }}',
                "oPaginate": {
                    "sFirst":    '{{ trans('app.first') }}',
                    "sLast":    '{{ trans('app.last') }}',
                    "sNext":    '{{ trans('app.next') }}',
                    "sPrevious": '{{ trans('app.previous') }}'
                },
            } 
        });
        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
        @if($user == 'admin')
            $.fn.dataTable.ext.search.push(
                function( settings, data, dataIndex ) {
                    var region = $('select[name="region"]').val();
                    var regions = data[2];
            
                    if ( region == 'all' ||  region == regions)
                    {
                        return true;
                    }
                    return false;
                }
            );
            $('select[name="region"]').change( function() {
                t.draw();
            });
        @endif
        $('input[name="search"]').on( 'keyup', function () {
            t.search($(this).val()).draw();
        } );

        
    })
</script>
@endsection