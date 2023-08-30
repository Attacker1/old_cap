@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('category.translator.index') }}"><i class="fas fa-window-close text-danger"></i></a>
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

    <form name="form" method="post" action="{{ route('category.translator.store') }}">
        {{ csrf_field() }}

    <div class="row">

        <div class="input-group mb-3 col-sm-6">
            <div class="input-group-prepend">
                <span class="input-group-text" id="ms_name_label">Мой склад</span>
            </div>
            <input type="text" name="ms_name" id="ms_name" value="{{ old('ms_name') }}"  class="form-control " autocomplete="off" required aria-label="" aria-describedby="ms_name_label">
        </div>
        <div class="input-group mb-3 col-sm-6">
            <div class="input-group-prepend">
                <span class="input-group-text" id="cap_name_label">ЛК (Capsula)</span>
            </div>
            <input type="text" name="cap_name" id="name" value="{{ old('cap_name') }}"  class="form-control " autocomplete="off" required aria-label="" aria-describedby="cap_name_label">
        </div>

        <div class="input-group mb-3 col-sm-12">
        <button type="submit" class="btn btn-success mr-1 mb-1 waves-effect waves-light">Добавить</button>
        </div>


    </div>
    </form>
        </div>
    </div>

@endsection




