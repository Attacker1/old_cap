@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-body">

    <div class="card card-custom gutter-b">
        <div class="card-body">
    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form name="form" method="post" action="{{ route('bonuses.store') }}">
        {{ csrf_field() }}
        <div class="row">

            <div class="input-group mb-3 col-sm-6">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="client_label">ID Клиента / Телефон:</span>
                </div>
                <input type="text" name="client_id" id="client_id" value=""
                       class="form-control " autocomplete="off" aria-label="" aria-describedby="client_label">
            </div>

            <div class="input-group mb-3 col-sm-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="points_label">Сумма бонусов:</span>
                </div>
                <input type="number" min="0" name="points" id="points" value=""
                       class="form-control " autocomplete="off" aria-label="" aria-describedby="points_label">
            </div>

            <div class="input-group mb-3 col-sm-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="promocode_label">Промокод:</span>
                </div>
                <input type="text" name="promocode" id="promocode" value=""
                       class="form-control " autocomplete="off" aria-label="" aria-describedby="promocode_label">
            </div>

            <div class="input-group mb-3  col-sm-6">
                <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Добавить</button>
                <a href="{{ url()->previous() }}"><button type="button" class="btn btn-danger"><i class="fa far fa-close"></i>Закрыть</button></a>
            </div>

        </div>
    </form>
        </div>
    </div>
    </div>
    </div>
@endsection



