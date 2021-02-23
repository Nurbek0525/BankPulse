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
                            
                        {{-- <div class="app-loader"><i class="icofont-spinner-alt-4 rotate"></i></div> --}}
                         <div class="page-content">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <form method="POST" action="/bank/update/{{ $bank->id }}/main"  enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label>{{ trans('app.Asosiy bank nomini kiriting') }}</label> 
                                                <input name="name" class="form-control rounded" type="text" placeholder="{{ trans('app.mainbank name') }}..." value="{{ $bank->name }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.address') }} </label> 
                                                    <textarea name="address" class="form-control rounded" placeholder="{{ trans('app.address') }}" >{{ $bank->address }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.Telefon raqam') }} </label> 
                                                    <input name="phone" class="form-control rounded" type="text" placeholder="+998( )..."  value="{{ $bank->phone }}"  />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.Photo') }} </label> 
                                                    <input name="photo" class="form-control rounded" type="file" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.Logo') }} </label> 
                                                    <input name="logo" class="form-control rounded" type="file" />
                                                </div>
                                                <button class="btn btn-primary mt-3">{{ trans('app.save') }} </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                
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