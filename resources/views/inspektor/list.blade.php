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
                        <div class="col-12">
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
                                                {{-- @if($user->role_id == 'admin') --}}
                                                
                                                    <a href="/user/add" class="btn btn-primary btn-square excel-button">
                                                        <i class="fa fa-plus"></i>
                                                        <span style="margin-left: 5px; text-transform: capitalize;">{{ trans('app.add') }}</span>
                                                    </a>
                                               
                                                {{-- @endif --}}
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
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="myTable" class="table table-striped table-lightfont">
                            <thead>
                                <tr>
                                    <th width='2%'>#</th>
                                    <th width='25%'>{{ trans('app.name and lastname') }}</th>
                                    <th width='8%'>{{ trans('app.phone') }} </th>
                                    <th width='25%'>{{ trans('app.region and city') }}</th>
                                    <th width='25%'>{{ trans('app.address') }}</th>
                                    <th width='15%'>{{ trans('app.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($users))
                                    @foreach ($users as $item)
                                        <tr>
                                            <td></td>
                                            <td>
                                                <div class="form-group avatar-box d-flex align-items-center" style="float: left; margin: 0">
                                                    @if(!empty($item->photo))
                                                        <img class="rounded-500 mr-4" src="{{ URL::asset('users/'.$item->photo) }}" style="width: 50px; height: 50px"/>
                                                    @else
                                                        <img class="rounded-500 mr-4" src="{{ URL::asset('resources/views/layouts/assets/content/user-400-1.jpg') }}" style="width: 50px; height: 50px"/>
                                                    @endif
                                                    {{ $item->firstname.' '.$item->lastname }}
                                                </div>
                                                
                                            </td>
                                            <td>{{ $item->phone }}</td>
                                            <td>{{ $item->region_name.' '.$item->city_name }}</td>
                                            <td>{{ $item->address }}</td>
                                            <td>
                                                <a href="/user/edit/{{ $item->id }}" class="btn edit-btn btn-success btn-square">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="/user/delete/{{ $item->id }}" class="btn edit-btn btn-danger btn-square">
                                                    <i class="fa fa-trash"></i>
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
<script src="{{ URL::asset('/assets/js/jquery-3.3.1.min.js') }}"></script>
<script>
    $('document').ready(function(){
        var t = $('#myTable').DataTable({
            "searching": true,
            "paging": true,
            "bInfo": true,
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