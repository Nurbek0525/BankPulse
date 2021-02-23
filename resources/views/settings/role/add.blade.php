@extends('layouts.app')

@section('content')

<div class="content-w height">
    <div class="content-i">
        <div class="content-box">
            <div class="row justify-content-md-center">
                <div class="col-lg-8">
                    <div class="element-wrapper">
                        <h6 class="element-header">{{ $title }}</h6>
                        <div class="element-box">
                            <form method="POST" action="/settings/role/add/{{ $role->id }}">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 col-md-6">
                                                        <div class="form-group">
                                                            <input type="hidden" name="id" value="{{ $role->id }}">
                                                            <label>{{ trans('app.role name') }}</label> 
                                                            <input name="name" class="form-control form-control" type="text" placeholder="{{ $role->name }}" <?=($role->status == 'active')?'value="'.$role->name.'"':'' ?> />
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.Select role position') }}</label> 
                                                            <select class="selectpicker form-control" name="position" data-live-search="true" required>
                                                                <option value="" selected disabled hidden> {{ $role->position }}</option>
                                                                <option <?=($role->position == 'country')?'selected':'' ?> value="country">{{ trans('app.Respublika') }}</option>
                                                                <option <?=($role->position == 'region')?'selected':'' ?> value="region">{{ trans('app.Region') }}</option>
                                                                <option <?=($role->position == 'moderator')?'selected':'' ?> value="moderator">{{ trans('app.Moderator') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-12">
                                                        <table id="myTable" class="table data-table">
                                                            <thead>
                                                                <tr>
                                                                    <th width='5%'>#</th>
                                                                    <th width="10">{{ trans('app.role name') }}</th>
                                                                    <th width="10">{{ trans('app.view') }}</th>
                                                                    <th width="10">{{ trans('app.create') }}</th>
                                                                    <th width="10">{{ trans('app.edit') }}</th>
                                                                    <th width='10%'>{{ trans('app.delete') }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if(!empty($accessrights))
                                                                    @php
                                                                        $i = 1;
                                                                    @endphp
                                                                    @foreach ($accessrights as $item)
                                                                        <tr>
                                                                            <td>{{ $i }}</td>
                                                                            <td>{{ $item->name }}</td>
                                                                            <td>
                                                                                <div class="custom-control custom-checkbox mb-2 mt-2">
                                                                                    <input <?=($item->view == 1)?'checked="checked"':'' ?> type="checkbox" class="custom-control-input accessrights" access_id="{{ $item->id }}" access_type="view" id="view{{ $item->id }}">
                                                                                    <label class="custom-control-label" for="view{{ $item->id }}"></label>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="custom-control custom-checkbox mb-2 mt-2">
                                                                                    <input <?=($item->create == 1)?'checked="checked"':'' ?> type="checkbox" class="custom-control-input accessrights" access_id="{{ $item->id }}" access_type="create" id="create{{ $item->id }}">
                                                                                    <label class="custom-control-label" for="create{{ $item->id }}"></label>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="custom-control custom-checkbox mb-2 mt-2">
                                                                                    <input <?=($item->edit == 1)?'checked="checked"':'' ?> type="checkbox" class="custom-control-input accessrights" access_id="{{ $item->id }}" access_type="edit" id="edit{{ $item->id }}">
                                                                                    <label class="custom-control-label" for="edit{{ $item->id }}"></label>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="custom-control custom-checkbox mb-2 mt-2">
                                                                                    <input <?=($item->delete == 1)?'checked="checked"':'' ?> type="checkbox" class="custom-control-input accessrights" access_id="{{ $item->id }}" access_type="delete" id="delete{{ $item->id }}">
                                                                                    <label class="custom-control-label" for="delete{{ $item->id }}"></label>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        @php
                                                                            $i++;
                                                                        @endphp
                                                                    @endforeach
                                                                    
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="col-12 col-md-12">
                                                        <button class="btn btn-primary">{{ trans('app.save') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
        $('#myTable').DataTable({
            "searching": false,
            "ordering": false,
            "paging": false,
            "info": false
            
        });
    })
    $(document).ready(function(){
        $('input.accessrights[type="checkbox"]').click(function(){
            if($(this).prop("checked") == true){
                var access_type = $(this).attr('access_type');
                var access_id = $(this).attr('access_id');
                var value = 1;
                var url = '{!! url('/settings/accessrights/change')!!}';
                $.ajax({
                    type: 'GET',
                    url: url,
                    data : {access_type:access_type,value:value,access_id:access_id},
                    success: function (response){  
                    },

                });
            }else if($(this).prop("checked") == false){
                var access_type = $(this).attr('access_type');
                var value = 0;
                var access_id = $(this).attr('access_id');
                var url = '{!! url('/settings/accessrights/change')!!}';
                $.ajax({
                    type: 'GET',
                    url: url,
                    data : {access_type:access_type,value:value,access_id:access_id},
                    success: function (response){   
                    },
                });
            }
        });
    });

</script>
@endsection