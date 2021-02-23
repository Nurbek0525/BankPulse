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
                            <form method="POST" action="/bank/save?type=head">
                                @csrf
                                <div class="form-group">
                                    <label>{{ trans ('app.bank name') }} </label> 
                                    <input name="name" class="form-control" type="text" placeholder="Name..." />
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('app.Bosh bankni tanlang') }} </label> 
                                    <select class="selectpicker" name="mainbank" data-live-search="true" >
                                        <option value="" selected disabled hidden> {{ trans('app.Bosh bank tanlang') }} </option>
                                        @if(!empty($mainbanks))
                                            @foreach ($mainbanks as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('app.Viloyatni tanlang') }} </label> 
                                    <select class="selectpicker" name="region" data-live-search="true" required>
                                        <option value="" selected disabled hidden> {{ trans('app.Viloyat tanlang') }}</option>
                                        @if(!empty($regions))
                                            @foreach ($regions as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('app.Tuman/Shaxar ni tanlang') }} </label> 
                                    <select class="selectpicker" name="city" data-live-search="true" required>
                                        @if(!empty($cities))
                                            @foreach ($cities as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('app.address') }} </label> 
                                    <textarea name="address" class="form-control" placeholder="Address"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('app.Telefon raqam') }} </label> 
                                    <input name="phone" class="form-control" type="text" placeholder="+998( )..." />
                                </div>
                                <button class="btn btn-primary">{{ trans('app.save') }} </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
<script>
    $('document').ready(function(){
        $('select[name="region"]').on('change', function(){
            var state = $(this).val();
            $.ajax({
                method: 'GET',
                url:'/bank/getcity',
                data:'region='+state,
                success:function(data){
                    $('select[name="city"]').html(data);
                    $('select[name="city"]').selectpicker('refresh');
                }
            });
        });
    })
</script>
@endsection