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

        <div class="row">
            {{ $data ?? '' }}
            {{ $api ?? 'Нет данных по заказу'}}
        </div>


    </div>

@endsection



