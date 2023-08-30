@extends('admin.main')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('admin.catalog.products.index') }}"><button class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Закрыть</button></a>
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

    <form name="form" method="post" action="{{ route('admin.catalog.products.test') }}" enctype="multipart/form-data"  >
        {{ csrf_field() }}

        <input type="file" name="file">

        <div class="input-group mb-3 col-sm-12">
        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Добавить</button>
        </div>

    </div>
    </form>


@endsection
@section('scripts')


@endsection


