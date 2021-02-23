@extends('layouts.app')

@section('content')

<div class="content-w">
    <div class="content-i">
        <div class="content-box">
            <div class="row justify-content-md-center">
                <div class="col-lg-4">
                    <div class="element-wrapper">
                        <h6 class="element-header">{{ $title }}</h6>
                        <div class="element-box">
                            <form method="POST" action="/region/save">
                                @csrf
                                <div class="form-group">
                                    <label>{{ trans('app.regions name') }}</label> 
                                    <input name="name" class="form-control" type="text" placeholder="Name..." />
                                </div>
                                <button class="btn btn-primary" type="submit" style="margin-top: 15px;">{{ trans('app.save') }}</button>
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