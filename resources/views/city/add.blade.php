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
                            <form method="POST" action="/city/save">
                                @csrf
                                <div class="form-group">
                                    <label>{{ trans ('app.tuman va shaharlar nomi') }} </label> 
                                    <input name="name" class="form-control" type="text" placeholder="Name..." />
                                </div>
                                <div class="form-group">
                                    <label>{{ trans ('app.Viloyatni tanlang') }} </label> 
                                    <select class="selectpicker form-control" name="region" data-live-search="true" data-placeholder="Viloyatni tanlang" required>
                                        <option value="" disabled selected hidden>Viloyatni tanlang</option>
                                        @if(!empty($regions))
                                            @foreach ($regions as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <button class="btn btn-primary" style="margin-top: 15px">{{ trans ('app.save') }} </button>
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