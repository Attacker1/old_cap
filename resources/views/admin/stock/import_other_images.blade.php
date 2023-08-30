@extends('admin.layout-metronic.default')
@section('title'){{ $title }}@endsection
@section('breadcrumb'){{ $title }}@endsection
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

            <form name="form" method="post" action="{{ route('stock.other_images.import') }}" enctype="multipart/form-data">
                {{ csrf_field() }}

                <code>Поля: <b>uuid (fixed)</b> | <b>Url  (fixed)</b></b></code>
                <div class="row">

                    <div class="input-group mb-3 col-sm-12">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="xlsFile_label">XLS файл</span>
                        </div>
                        <input type="file" name="xlsFile" id="xlsFile" class="form-control " required aria-label="" aria-describedby="xlsFile_label">
                    </div>

                    <div class="input-group mb-3 col-sm-12">
                        <button type="submit" class="btn btn-success mr-1 mb-1 waves-effect waves-light">Импорт</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection




