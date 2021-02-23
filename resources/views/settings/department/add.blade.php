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
                            <form method="POST" action="/settings/department/add?key={{ $key }}">
                                @csrf
                                <div class="form-group">
                                    <label>{{ trans ('app.enter departments name') }} </label> 
                                    <input name="name" class="form-control" type="text" placeholder="{{ trans('app.enter departments name') }}" />
                                </div>
                                <div class="form-group">
                                    <label>{{ trans ('app.key word') }} </label> 
                                    <input name="code" class="form-control" type="text" placeholder="{{ trans('app.key word') }}" />
                                </div>
                                @if($key == 'sub')
                                    <div class="form-group">
                                        <label>{{ trans ('app.Select department') }} </label> 
                                        <select class="selectpicker form-control" name="department" data-live-search="true" data-placeholder="Select department" required>
                                            <option value="" disabled selected hidden>{{ trans('app.select department') }}</option>
                                            @if(!empty($departments))
                                                @foreach ($departments as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                @endif
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