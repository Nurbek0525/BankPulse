@extends('layouts.app')

@section('content')

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
                                    <a href="/settings/activity-code/add" class="btn btn-primary btn-square excel-button">
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
                    <div class="table-responsive">
                        <table id="myTable" class="table table-striped table-lightfont">
                            <thead>
                                <tr>
                                    <th width='3%'>#</th>
                                    <th width="8%">{{ trans('app.activity code id') }}</th>
                                    <th width='50%'>{{ trans('app.activity name') }}</th>
                                    {{-- <th width='10%'>{{ trans('app.action') }}</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($activity_codes))
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($activity_codes as $item)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td style="text-align: center">{{ $item->code }}</td>
                                            <td>{{ $item->name }}</td>
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

        $('input[name="search"]').on( 'keyup', function () {
            t.search($(this).val()).draw();
        } );
    })
</script>
@endsection