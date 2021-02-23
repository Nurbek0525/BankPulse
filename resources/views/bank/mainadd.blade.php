@extends('layouts.app')

@section('content')

<div class="content-w height">
    <div class="content-i">
        <div class="content-box">
            <div class="row justify-content-md-center">
                <div class="col-lg-4">
                    <div class="element-wrapper">
                        <h6 class="element-header">{{ $title }}</h6>
                        <div class="element-box">
                            <form method="POST" action="/bank/save?type=main">
                                @csrf
                                <div class="form-group">
                                    <label>{{ trans('app.Asosiy bank nomini kiriting') }}</label> 
                                    <input name="name" class="form-control" type="text" placeholder="{{ trans('app.mainbank name') }}..." />
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('app.address') }} </label> 
                                    <textarea name="address" class="form-control" placeholder="{{ trans('app.address') }}"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('app.Telefon raqam') }} </label> 
                                    <input name="phone" class="form-control" type="text" placeholder="+998( )..." />
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('app.Photo') }} </label> 
                                    <input name="photo" class="form-control" type="file" />
                                </div>
                                <button class="btn btn-primary" type="submit" style="margin-top:15px">{{ trans('app.save') }} </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
<script>
    $('document').ready(function(){
        
    })
</script>
@endsection