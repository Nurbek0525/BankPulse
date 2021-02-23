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
                                    <a href="/city/add" class="btn btn-primary btn-square excel-button">
                                    <i class="fa fa-plus"></i>
                                    <span style="margin-left: 5px; text-transform: capitalize;">{{ trans('app.add') }}</span>
                                    </a>
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
                    <div class="table-responsive">
                        <table id="myTable" class="table table-striped table-lightfont">
                            <thead>
                                <tr>
                                    <th width='3%'>#</th>
                                    <th width='35%'>{{ trans('app.tuman va shaharlar nomi') }} </th>
                                    <th width='35%'>{{ trans('app.viloyat') }} </th>
                                    {{-- <th width='20%'>{{ trans('app.action') }} </th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($cities))
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($cities as $item)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->region_name }}</td>
                                            {{-- <td>{{ $item->id }}</td> --}}
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
        $('input[name="search"]').on( 'keyup', function () {
            t.search($(this).val()).draw();
        } );
    })
</script>
@endsection