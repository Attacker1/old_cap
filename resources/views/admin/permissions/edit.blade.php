@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('manage.permissions.index') }}"><i class="fas fa-window-close text-danger"></i></a>
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

    <form name="form" method="post" action="{{ route('manage.permissions.edit',$data->id) }}">
        {{ csrf_field() }}

        <div class="row">

            <div class="input-group mb-3 col-sm-12">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="name_label">Наименование прав</span>
                </div>
                <input type="text" name="name" id="name" value="{{ @$data->name }}"  class="form-control " autocomplete="off"  aria-label="" aria-describedby="name_label" required>
            </div>

            <div class="input-group mb-3 col-sm-12">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="slug_label">Системное имя(Slug)</span>
                </div>
                <input type="text" name="slug" id="slug" value="{{ @$data->slug }}"  class="form-control " autocomplete="off"  aria-label="" aria-describedby="slug_label" required>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
            </div>
            <div class="input-group mb-3 col-sm-12">
                <button type="submit" class="btn btn-success mr-1 mb-1 waves-effect waves-light">Сохранить</button>
            </div>
        </div>

    </form>
        </div>
    </div>

@endsection



