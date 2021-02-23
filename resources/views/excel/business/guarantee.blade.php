@extends('layouts.app')

@section('content')

<div class="content-w height">
    <div class="content-i">
        <div class="content-box">
            <div class="element-wrapper">
                <div class="element-box">
                    <div class="row">
                        <div class="main-content-wrap" style="width: 100%">
                            <header class="page-header">
                            <h5 class="form-header form-header-font-size">{{ $title }}</h5>
                            </header>
                            <div class="page-content">
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
                                <form method="POST" action="/excel/business/guarantee" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3 col-xl-2">
                                               <button class="form-control clickbutton" >
                                                <span class="upload">{{ trans('app.upload excel file') }}</span>
                                                </button>
                                                <input type='file' name="import" style="display:none">
                                            </div>
                                            <div class="col-md-3 col-xl-2">
                                                 <input readonly="true" name="monthyear" class="form-control datepicker" style="text-align: center;" type="text" placeholder="{{ trans('app.select month') }}" />
                                            </div>
                                            <div class="col-md-3 col-xl-2">
                                                {{-- <button class="btn btn-primary">Add</button> --}}
                                            </div>
                                        </div>
                                         <div>
                                                <button type="submit" class="mt-3 btn btn-primary">{{ trans('app.import') }}</button>
                                         </div>
                                    </div>                        
                                </form>
                                    </div>
                                </div>
                                @if(!empty($allrows))
                                    <div class="card">
                                        <div class="card-header">
                                            <h3>{{ $allrows." elementdan ".$successrows." element bajarildi! " }}</h3>
                                            <h3 class="text-danger">{{ (count($errorrows))." elementda xatolik topildi" }}</h3>
                                        </div>
                                        @if(!empty($errorrows))
                                            <div class="card-body">
                                                <table id="myTable" class="table data-table">
                                                    <tbody>
{{--                                                         @if(!empty($errorrows))
                                                            @foreach($errorrows as $data)
                                                                <tr>
                                                                    @foreach ($data as $item)
                                                                        <td>{{ $item }}</td>
                                                                    @endforeach
                                                                </tr>
                                                            @endforeach
                                                        @endif --}}
                                                    </tbody>
                                                </table>
                                            </div> 
                                        @endif
                                    </div>
                                @endif
                            </div>
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
        $('input.datepicker').datepicker({
            format:'yyyy-mm',
            startView: 'months',
            minViewMode: 'months',
            autoclose:1,
            startView:'1',
            endDate: new Date()
        });
        $('.clickbutton').on('click', function(e){
            e.preventDefault();
            $('input[name="import"]').click();
            if($('.clickbutton').hasClass("form-control-focus")){
                $('.clickbutton').removeClass("form-control-focus")
            }
        });

        $('input[name="import"]').on('change', function(){
            var path=this.value;
            path=path.split('\\');
            var val=path[path.length-1];
            $(".upload").html(val);
        });

        $('button[type="submit"]').click(function(e){
            e.preventDefault();
            var up_prov = $('input[name="import"]').val();
            var data_prov = $('input[name="monthyear"]').val();
            if(up_prov == "" || data_prov == ""){
                if(data_prov == ""){
                    $('input[name="monthyear"]').focus();
                    $('input[name="monthyear"]').addClass("form-control-focus");
                }
                if(up_prov == ""){
                    $('.clickbutton').focus();
                    $('.clickbutton').addClass("form-control-focus");
                } 
            }else{
                $('form').submit();
            }
        });
    })
</script>
@endsection