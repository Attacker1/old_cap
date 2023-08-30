@extends('admin.main')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('admin.catalog.brands.index') }}"><button class="btn btn-sm btn-danger"><i class="fa fa-close"></i></button></a>
    </div>
@endsection

@section('content')

    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form name="form" method="post" action="{{ route('admin.catalog.brands.create') }}">
        {{ csrf_field() }}

    <div class="row">

        <div class="input-group mb-3 col-sm-12">
            <div class="input-group-prepend">
                <span class="input-group-text" id="name_label">Наименование</span>
            </div>
            <input type="text" name="name" id="name" value="{{ old('name') }}"  class="form-control " autocomplete="off" required aria-label="" aria-describedby="name_label">
        </div>

        <div class="input-group mb-3 col-sm-12">
        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Добавить</button>
        </div>



    </div>
    </form>

@endsection




