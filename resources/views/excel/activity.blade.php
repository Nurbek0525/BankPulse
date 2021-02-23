@extends('layouts.app')

@section('content')

<main class="main-content">
    <div class="app-loader"><i class="icofont-spinner-alt-4 rotate"></i></div>
    <div class="main-content-wrap">
        <header class="page-header">
        <h3 class="page-title">{{ $title }}</h3>
        </header>
        <div class="page-content">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            @if(!empty($type))
                                @if($type == 'error')
                                    <div class="alert alert-danger" role="alert">
                                        {{ $message }}
                                    </div>
                                    @foreach ($filetypes as $type)
                                    <div class="alert alert-danger" role="alert">
                                        .{{ $type }}
                                    </div>
                                    @endforeach
                                @endif
                            @endif
                            <form method="POST" action="/excel/activity" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>{{ trans('app.upoad excel file') }}</label> 
                                    <input name="import" class="form-control rounded" type="file" placeholder="Name..." />
                                </div>
                                <button class="btn btn-primary">{{ trans('app.import') }}</button>
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
        $('input.datepicker').datepicker({
            format:'yyyy-mm-dd',
            autoclose:1,
            startView:'1',
            endDate: new Date()
        })
    })
</script>
@endsection