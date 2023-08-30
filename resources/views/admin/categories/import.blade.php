@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('admin.catalog.categories.index') }}">
            <button class="btn btn-sm btn-danger"><i class="fa fa-close"></i></button>
        </a>
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

    <div class="card card-custom gutter-b">
        <div class="card-body">

    <form name="import" method="post" action="{{ route('admin.catalog.categories.import') }}"
          enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">

            @if ($errors->has('xlsFile'))
                <span class="form-error">{{ $errors->first('xlsFile') }}</span>
            @endif

            <div class="input-group mb-1 col-sm-4">
                <div class="input-group-prepend"><span class="input-group-text"
                                                       id="xls_label">Файл XLS</span>
                </div>
                <input class="form-control" name="xlsFile"  type="file"
                       placeholder="" autocomplete="off" required="">
            </div>
                <div class="alert alert-danger col-sm-6 offset-1">Наименования колонок: ms | capsula</div>

            <div class="col-sm-2 text-center form-group">
                <button class="btn btn-success form-control">Импорт</button>
            </div>


        </div>
    </form>
        </div>
    </div>
@endsection




