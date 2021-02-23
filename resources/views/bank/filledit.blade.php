@extends('layouts.app')

@section('content')

<div class="content-w">
    <div class="content-i">
        <div class="content-box">
            <div class="row justify-content-md-center">
                <div class="col-lg-9">
                    <div class="element-wrapper">
                        <h6 class="element-header">{{ $title }}</h6>
                        <div class="element-box">
                            
                        <div class="app-loader"><i class="icofont-spinner-alt-4 rotate"></i></div>
                       
                        <form action="/bank/update/{{ $bank->id }}/fill" method="POST">
                            @csrf
                            <div class="page-content">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>{{ trans('app.fillial name') }}</label> 
                                                    <input value="{{ $bank->name }}" name="name" class="form-control rounded" type="text" placeholder="{{ trans('app.bank name') }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.fillial short name') }}</label> 
                                                    <input value="{{ $bank->short_name }}" name="short_name" class="form-control rounded" type="text" placeholder="{{ trans('app.fillial short name') }}" />
                                                </div>
                                                <div class="form-group headbank">
                                                    <label>{{ trans('app.select main bank') }}</label> 
                                                    <select class="selectpicker rounded" name="mainbank" data-live-search="true" >
                                                        <option value="" selected disabled hidden>{{ trans('app.select main bank') }}</option>
                                                        @if(!empty($mainbanks))
                                                            @foreach ($mainbanks as $item)
                                                                <option {{ ($bank->mainbank_id == $item->id)?'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.mfo id') }}</label> 
                                                    <input value="{{ $bank->mfo_id }}" name="mfo_id" class="form-control rounded" type="text" placeholder="{{ trans('app.mfo id') }}..." />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.fillial stir') }}</label> 
                                                    <input value="{{ $bank->stir_inn }}" name="inn" class="form-control rounded" type="text" placeholder="{{ trans('app.fillial stir') }}..." />
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>{{ trans('app.select region') }}</label> 
                                                    <select class="selectpicker rounded" name="region" data-live-search="true" required>
                                                        <option value="" selected disabled hidden>{{ trans('app.select region') }}</option>
                                                        @if(!empty($regions))
                                                            @foreach ($regions as $item)
                                                                <option {{ ($bank->region_id == $item->id)?'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.select city') }}</label> 
                                                    <select class="selectpicker rounded" name="city" data-live-search="true" required>
                                                        @if(!empty($cities))
                                                            @foreach ($cities as $item)
                                                                <option {{ ($bank->city_id == $item->id)?'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.address') }}</label> 
                                                    <textarea  rows='1' name="address" class="form-control rounded" placeholder="{{ trans('app.address') }}...">{{ $bank->address }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.phone') }}</label> 
                                                    <input value="{{ $bank->phone }}" name="phone" class="form-control rounded" type="text" placeholder="+998( )..." />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.web site') }}</label> 
                                                    <input value="{{ $bank->web_site }}" name="website" class="form-control rounded" type="text" placeholder="www..." />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.select region work') }}</label> 
                                                    <select class="selectpicker rounded" name="region_work_id" data-live-search="true" required>
                                                        <option value="" selected disabled hidden>{{ trans('app.select region work') }}</option>
                                                        @if(!empty($regions))
                                                            @foreach ($regions as $item)
                                                                <option {{ ($bank->region_work_id == $item->id)?'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>{{ trans('app.edited_at') }}</label> 
                                                    <input readonly="true" name="edited" class="form-control datepicker rounded"  type="text" placeholder="" value="{{ date('d-m-Y', strtotime($bank->edited_at)) }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>{{ trans('app.started at') }}</label> 
                                                    <input readonly="true" name="opened" class="form-control datepicker rounded"  type="text" placeholder="" value="{{ date('d-m-Y', strtotime($bank->started_at)) }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2   ">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>{{ trans('app.index') }}</label> 
                                                    <input  name="index" class="form-control rounded"  type="text" placeholder="" value="{{ !empty($bank->index)?$bank->index:'' }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12 mt-3">
                                        <button class="btn btn-primary" type="submit">{{ trans('app.save') }}</button>
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

<script src="{{ URL::asset('resources/views/layouts/assets/js/jquery-3.3.1.min.js') }}"></script>
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