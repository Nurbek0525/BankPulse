@extends('layouts.app')

@section('content')

<div class="content-w height">
    <div class="content-i">
        <div class="content-box">
            <div class="row justify-content-md-center">
                <div class="col-lg-9">
                    <div class="element-wrapper">
                        <h6 class="element-header">{{ $title }}</h6>
                        <div class="element-box">
                            <form action="/bank/save?type=fill" method="POST">
                                @csrf
                                <div class="page-content">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.fillial name') }} </label> 
                                                        <input name="name" class="form-control" type="text" placeholder="{{ trans('app.fillial name') }}" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ trans('app.fillial short name') }}</label> 
                                                        <input  name="short_name" class="form-control" type="text" placeholder="{{ trans('app.fillial short name') }}" />
                                                    </div>
                                                    <div class="form-group headbank">
                                                        <label>{{ trans('app.Bosh bankni tanlang') }} </label> 
                                                        <select class="selectpicker form-control" name="mainbank" data-live-search="true" >
                                                            <option value="" selected disabled hidden> {{ trans('app.Bosh bank tanlang') }} </option>
                                                            @if(!empty($mainbanks))
                                                                @foreach ($mainbanks as $item)
                                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ trans('app.mfo id') }} </label> 
                                                        <input name="mfo_id" class="form-control" type="text" placeholder="MFO ID..." />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>STIR </label> 
                                                        <input name="inn" class="form-control" type="text" placeholder="STIR..." />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.select region work') }}</label> 
                                                        <select class="selectpicker form-control" name="region_work_id" data-live-search="true" required>
                                                            <option value="" selected disabled hidden>{{ trans('app.select region work') }}</option>
                                                            @if(!empty($regions))
                                                                @foreach ($regions as $item)
                                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ trans('app.Viloyatni tanlang') }}</label> 
                                                        <select class="selectpicker form-control" name="region" data-live-search="true" required>
                                                            <option value="" selected disabled hidden> {{ trans('app.Viloyat tanlang') }}</option>
                                                            @if(!empty($regions))
                                                                @foreach ($regions as $item)
                                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ trans('app.Tuman/Shaxar ni tanlang') }}</label> 
                                                        <select class="selectpicker form-control" name="city" data-live-search="true" required>
                                                            @if(!empty($cities))
                                                                @foreach ($cities as $item)
                                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ trans('app.address') }} </label> 
                                                        <textarea rows='1' name="address" class="form-control" placeholder="{{ trans('app.address') }}..."></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ trans('app.Telefon raqam') }}</label> 
                                                        <input name="phone" class="form-control" type="text" placeholder="+998( )..." />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ trans('app.Web sayt') }}</label> 
                                                        <input name="website" class="form-control" type="text" placeholder="www..." />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.edited_at') }}</label> 
                                                        <input readonly="true" name="edited" class="form-control datepicker"  type="text" placeholder="" value="{{ date('Y-m-d') }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.started at') }}</label> 
                                                        <input readonly="true" name="opened" class="form-control datepicker"  type="text" placeholder="" value="{{ date('Y-m-d') }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2   ">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.index') }}</label> 
                                                        <input  name="index" class="form-control"  type="text" placeholder="" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12">
                                            <button class="btn btn-primary" type="submit"  style="margin-top: 15px">{{ trans('app.save') }} </button>
                                        </div>
                                    </div>
                                </div> 
                            </form>
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
        
        $('select[name="region"]').on('change', function(){
            var state = $(this).val();
            $.ajax({
                method: 'GET',
                url:'/getcity',
                data:'region='+state,
                success:function(data){
                    $('select[name="city"]').html(data);
                    $('select[name="city"]').selectpicker('refresh');
                }
            });
        });
        $('input.datepicker').datepicker({
            format:'yyyy-mm-dd',
            autoclose:1,
            startView:'1',
            endDate: new Date(),
            minDate:0
        })
        $('select[name="mainbank"]').on('change', function(){
            var mainbank = $(this).val();
            $.ajax({
                method: 'GET',
                url:'/getheadbank',
                data:'mainbank='+mainbank,
                success:function(data){
                    $('select[name="headbank"]').html(data);
                    $('select[name="headbank"]').selectpicker('refresh');
                }
            });
        });
    })
</script>
@endsection