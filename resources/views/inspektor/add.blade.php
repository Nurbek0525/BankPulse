@extends('layouts.app')

@section('content')
<div class="content-w">
    <div class="content-i">
        <div class="content-box">
            <div class="row justify-content-md-center">
                <div class="col-lg-6">
                    <div class="element-wrapper">
                        <h6 class="element-header">{{ $title }}</h6>
                        <div class="element-box">
                            <form method="POST"  action="<?=(empty($user))?'/user/add':'/user/update/'.$user->id ?>" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label>{{ trans('app.first name') }}</label> 
                                                    <input name="firstname" value="{{ !empty($user)?$user->firstname:'' }}" class="form-control" type="text" placeholder="{{ trans('app.first name') }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.last name') }}</label> 
                                                    <input name="lastname" value="{{ !empty($user)?$user->lastname:'' }}" class="form-control" type="text" placeholder="{{ trans('app.last name') }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.middle name') }}</label> 
                                                    <input name="middlename" value="{{ !empty($user)?$user->middlename:'' }}" class="form-control" type="text" placeholder="{{ trans('app.middle name') }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.phone number') }}</label> 
                                                    <input name="phone" value="{{ !empty($user)?$user->phone:'' }}" class="form-control" type="text" placeholder="{{ trans('app.phone number') }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.image upload') }} </label> 
                                                    <input name="image" value="" class="form-control" type="file" placeholder="{{ trans('app.image upload') }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.address') }} </label> 
                                                    <textarea rows="4" name="address"  class="form-control">{{ !empty($user)?$user->address:'' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label>{{ trans('app.email') }}</label> 
                                                    <input name="email" value="{{ !empty($user)?$user->email:'' }}" class="form-control" type="email" placeholder="{{ trans('app.first name') }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.enter password') }}</label> 
                                                    <input name="password" class="form-control @error('password') is-invalid @enderror" type="password" autocomplete="new-password"  placeholder="{{ trans('app.enter password') }}" />
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.confirm password') }}</label> 
                                                    <input id="password-confirm" type="password" placeholder="{{ trans('app.confirm password') }}" class="form-control" name="password_confirmation" autocomplete="new-password">
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.select region') }}</label> 
                                                    <select class="selectpicker form-control" name="region" data-live-search="true" required>
                                                        <option value="" selected disabled>{{ trans('app.select region') }}</option>
                                                        @if(!empty($regions))
                                                            @foreach ($regions as $item)
                                                                <option {{ !empty($user)?(($item->id == $user->region_id)?'selected':''):'' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.select city') }}</label> 
                                                    <select class="selectpicker  form-control" name="city" data-live-search="true" required>
                                                        <option value="" selected disabled hidden>{{ trans('app.select city') }}</option>
                                                        @if(!empty($cities))
                                                            @foreach ($cities as $item)
                                                                <option {{ !empty($user)?(($item->id == $user->city_id)?'selected':''):'' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.select role') }}</label> 
                                                    <select class="selectpicker  form-control" name="role" data-live-search="true">
                                                        <option value="" selected disabled hidden>{{ trans('app.select role') }}</option>
                                                        @if(!empty($roles))
                                                            @foreach ($roles as $item)
                                                                <option {{ !empty($user)?(($item->id == $user->role_id)?'selected':''):'' }}  value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.select language') }}</label> 
                                                    <select class="selectpicker  form-control" name="language" data-live-search="true" required>
                                                        <option {{ !empty($user)?(($user->language == 'уз')?'selected':''):'' }} value="уз" selected>Ўзбекча</option>
                                                        <option {{ !empty($user)?(($user->language == 'uz')?'selected':''):'' }} value="uz" >O'zbekcha</option>
                                                        <option {{ !empty($user)?(($user->language == 'en')?'selected':''):'' }} value="en" >English</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary" style="margin-top: 15px">{{ trans('app.save') }}</button>
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
<script src="{{ URL::asset('/assets/js/jquery-3.3.1.min.js') }}"></script>
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