@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('admin.catalog.categories.index') }}"><i class="fas fa-window-close text-danger"></i></a>
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

    <form name="form" method="post" action="{{ route('admin.catalog.categories.edit',$data->id) }}">
        {{ csrf_field() }}

    <div class="row">

        <div class="input-group mb-3 col-sm-4">
            <div class="input-group-prepend">
                <span class="input-group-text" id="name_label">Наименование</span>
            </div>
            <input type="text" name="name" id="name" value="{{ @$data->name }}"  class="form-control " autocomplete="off"  aria-label="" aria-describedby="name_label">
        </div>

        <div class="input-group mb-3 col-sm-4">
            <div class="input-group-prepend">
                <span class="input-group-text" id="slug_label">url</span>
            </div>
            <input type="text" name="slug" id="slug" value="{{ @$data->slug }}"  class="form-control " autocomplete="off" required aria-label="" aria-describedby="slug_label">
        </div>

        <div class="input-group mb-3 col-sm-4">
            <div class="input-group-prepend">
                <span class="input-group-text" id="parent_id_title">Род. категория:</span>
            </div>
            <select  name="parent_id" class="form-control " id="parent_id" >
                @foreach($menu as $k=>$v)
                    <option value="{{ $k }}" @if($data->parent_id == $k) selected @endif>{{ @$v }}</option>
                @endforeach
            </select>
        </div>

        <div class="input-group mb-3 col-sm-4">
            <div class="input-group-prepend">
                <span class="input-group-text" id="car">Видимость:</span>
            </div>
            <select  name="visible" class="form-control" id="visible" >
                <option value="0" @if($data->visible != 1) selected @endif>ДА</option>
                <option value="1" @if($data->visible == 1) selected @endif >НЕТ</option>
            </select>
        </div>

        <!-- Наборы характеристик -->
        <div class="col-sm-12">
        <h4 class="mb-2">Атрибуты</h4>
        @if($attributes)
            @foreach($attributes as $k=>$v)
                <div class="form-group custom-control custom-switch custom-control-inline">
                    <input type="checkbox" name="attribute_id[{{ @$v }}]" value="{{ @$k }}" class="custom-control-input" id="attributeID{{ @$k }}" @if(in_array($k,$assign_attributes)) checked @endif>
                    <label class="custom-control-label"  for="attributeID{{ @$k }}">
                    </label>
                    <span class="switch-label">{{ @$v}}</span>
                </div>
            @endforeach
        @endif
        </div>
        <!-- END Наборы характеристик -->

        <div class="input-group mb-3 col-sm-12">
        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Сохранить</button>
        </div>



    </div>
    </form>
        </div></div>
@endsection



