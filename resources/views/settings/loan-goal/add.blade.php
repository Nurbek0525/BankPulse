@extends('layouts.app')

@section('content')

<main class="main-content">
    <div class="app-loader"><i class="icofont-spinner-alt-4 rotate"></i></div>
    <div class="main-content-wrap">
        <header class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        </header>
        <div class="page-content">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="/settings/loan-goal/add">
                                @csrf
                                <div class="form-group">
                                    <input name="name" class="form-control" type="text" placeholder="{{ trans('app.loan goal name') }}" />
                                </div>
                                <div class="form-group">
                                    <input name="code" class="form-control" type="text" placeholder="{{ trans('app.loan goal code') }}" />
                                </div>
                                <button class="btn btn-primary">{{ trans('app.save') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="{{ URL::asset('resources/views/layouts/assets/js/jquery-3.3.1.min.js') }}"></script>
<script>
    $('document').ready(function(){
        
    })
</script>
@endsection