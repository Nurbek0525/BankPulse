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
                            <form method="POST" action="/settings/account-sheet/add">
                                @csrf
                                <div class="form-group">
                                    <input name="name" class="form-control" type="text" placeholder="{{ trans('app.Account sheet name') }}" />
                                </div>
                                <div class="form-group">
                                    <input name="code" class="form-control" type="text" placeholder="{{ trans('app.Account sheet code') }}" />
                                </div>
                                <button class="btn btn-primary" style="margin-top: 15px">{{ trans('app.save') }}</button>
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
        
    })
</script>
@endsection