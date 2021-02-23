@extends('layouts.app')

@section('content')

<div class="content-w height">
    <div class="content-i">
        <div class="content-box">
            <div class="row justify-content-md-center">
                <div class="col-md-10">
                    <div class="element-wrapper">
                        <div class="element-box">
                            <div class="row">
                                <div class="col-md-8">
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
                                <div class="col-md-4" style="text-align: right;">
                                    <div class="form-group" style="margin-top: 5px">
                                        <a href="{!! url('/settings/weight/add')!!}" class="btn btn-primary btn-square excel-button">
                                            <i class="fa fa-plus"></i>
                                            <span style="margin-left: 5px; text-transform: capitalize;">{{ trans('app.add') }}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="myTable" class="table table-striped table-lightfont">
                                    <thead>
                                        <tr>
                                            <th width='2%'>#</th>
                                            <th width='10%' style="text-align: center">{{ trans('app.cash') }}</th>
                                            <th width='10%' style="text-align: center">{{ trans('app.inspeksiya') }}</th>
                                            <th width='12%' style="text-align: center">{{ trans('app.business') }}</th>
                                            <th width='15%' style="text-align: center">{{ trans('app.currency') }}</th>
                                            <th width='10%' style="text-align: center">{{ trans('app.ijro') }}</th>
                                            <th width='10%' style="text-align: center">{{ trans('app.time') }}</th>
                                            <th width='25%' style="text-align: center">{{ trans('app.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($weights))
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($weights as $item)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td style="text-align: center">{{ $item->cash }}</td>
                                                    <td style="text-align: center">{{ $item->inspeksiya }}</td>
                                                    <td style="text-align: center">{{ $item->business }}</td>
                                                    <td style="text-align: center">{{ $item->currency }}</td>
                                                    <td style="text-align: center">{{ $item->ijro_head }}</td>
                                                    <td>{{ trans('app.month'.$item->month).' '.$item->year }}</td>
                                                    <td>
                                                        <a href="/settings/weight/view?id={{ $item->id }}" class="btn edit-btn btn-primary btn-square">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a href="/settings/weight/list?id={{ $item->id }}" class="btn edit-btn btn-success btn-square">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <div class="btn edit-btn btn-primary btn-square">
                                                           <i class="fa fa-refresh"></i><input type="hidden" name="generate" value="0"> 
                                                        </div>
                                                        <a href="/settings/weight/delete/{{ $item->id }}" class="btn edit-btn btn-danger btn-square">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ URL::asset('/assets/js/jquery-3.3.1.min.js') }}"></script>
<script>
    $('document').ready(function(){
        $('#myTable').DataTable({
            "searching": false,
            "paging": false,
            "bInfo": false           
        });
    })
</script>
<style type="text/css">
    .page-box .app-container .main-content .main-content-wrap{
        margin: 0 10px
    }
</style>
@endsection