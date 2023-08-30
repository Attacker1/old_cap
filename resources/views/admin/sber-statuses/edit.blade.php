@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('sber.statuses.index') }}"><i class="fas fa-window-close text-danger"></i></a>
    </div>
@endsection

@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-body">
    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form name="form" method="post" action="{{ route('sber.statuses.update',$data->id) }}">
        {{ csrf_field() }}

        <div class="row">

            <div class="input-group mb-3 col-sm-12">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="name_label">Cтатус для Сбербанка: </span>
                </div>
                <input type="text" name="name" id="name" value="{{ @$data->name }}"  class="form-control " autocomplete="off"  aria-label="" aria-describedby="name_label" required>
            </div>
        </div>
        <div class="row">
            @foreach($states as $k=>$v)
                <div class=" ml-5 col-sm-3 form-group custom-control custom-switch custom-control-inline">
                    <input type="checkbox" name="state[]" value="{{ $k }}" class="custom-control-input"
                           id="state-id-{{ $k }}" @if($data->leadref()->where('lead_ref_id',$k)->count()) checked @endif>
                    <label class="custom-control-label" for="state-id-{{ $k }}">
                    </label>
                    <span class="switch-label">{{ @$v }}</span>
                </div>
            @endforeach

            <div class="input-group mb-3 col-sm-12">
                <button type="submit" class="btn btn-success mr-1 mb-1 waves-effect waves-light">Сохранить</button>
            </div>

        </div>
    </form>
        </div>
    </div>
@endsection



