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

    <form name="form" method="post" action="{{ route('leads.store') }}">
        {{ csrf_field() }}

    <div class="row">

        <div class="input-group mb-2 col-sm-3">
        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Оплатить</button>
        </div>



    </div>
    </form>
        </div>
    </div>

@endsection



