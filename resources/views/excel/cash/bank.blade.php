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
                            <form method="POST" action="/excel/fillial" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Excel fileni yuklang</label> 
                                    <input name="import" class="form-control" type="file" placeholder="Name..." />
                                </div>
                                <button class="btn btn-primary">Import</button>
                            </form>
                        </div>
                    </div>
                    @if(!empty($errorrows))
                        <div class="card">
                            <div class="card-header">
                                <h3>{{ $allrows." elementdan ".$successrows." element bajarildi! " }}</h3>
						        <h3 class="text-danger">{{ (count($errorrows)-2)." elementda xatolik topildi" }}</h3>
                            </div>
                            <div class="card-body">
                                <table id="myTable" class="table data-table">
                                    <tbody>
                                       {{--  @if(!empty($errorrows))
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach($errorrows as $data)
                                                @if($i != 1)
                                                    <tr>
                                                        @foreach ($data as $item)
                                                            <td>{{ $item }}</td>
                                                        @endforeach
                                                    </tr>
                                                @endif
                                                @php
                                                    $i++;
                                                @endphp
                                            @endforeach
                                            
                                        @endif --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
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