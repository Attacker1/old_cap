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

        <div class="card card-custom">
            <div class="card-header  mb-3">
                <div class="card-title">
                            <span class="card-icon">
                                <i class="flaticon2-line-chart text-primary"></i>
                            </span>
                    <h3 class="card-label">Проверка <small>статусы и в доставке</small></h3>
                </div>
            </div>

            <div class="row">

                <div class="input-group mb-2 col-sm-4">

                    <a href="{{ route('boxberry.amo') }}">
                        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">AMO > Boxberry
                        </button>
                    </a>

                </div>

                <div class="input-group mb-2 col-sm-4">
                    <form name="form" method="post" action="{{ route('boxberry.delivering') }}">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Заказы в
                            Доставке
                        </button>
                    </form>
                </div>
            </div>

        </div>



        <div class="card card-custom">
            <div class="card-header  mb-3">
                <div class="card-title">
                            <span class="card-icon">
                                <i class="flaticon2-line-chart text-primary"></i>
                            </span>
                    <h3 class="card-label">Информация <small>по заказу</small></h3>
                </div>
            </div>
                <form name="form" method="post" action="{{ route('boxberry.info') }}">
                    {{ csrf_field() }}
                    <div class="row">

                        <div class="input-group mb-2 col-sm-4">

                            <div class="input-group-prepend">
                                <span class="input-group-text" id="delivery_point_id_title">ID заказа</span>
                            </div>

                            <input type="text" name="order_id" id="order_id" title="" value=""
                                   class="form-control " autocomplete="off" aria-label="" required>
                        </div>

                        <div class="input-group mb-2 col-sm-4">
                            <button type="submit" class="btn btn-success  waves-effect waves-light">Проверить</button>
                        </div>

                    </div>
                </form>
        </div>

        <div class="card card-custom">
            <div class="card-header  mb-3">
                <div class="card-title">
                            <span class="card-icon">
                                <i class="flaticon2-line-chart text-primary"></i>
                            </span>
                    <h3 class="card-label">Удаление <small>заказа</small></h3>
                </div>
            </div>
            <form name="form" method="post" action="{{ route('boxberry.destroy') }}">
                {{ csrf_field() }}
                <div class="row">

                    <div class="input-group mb-2 col-sm-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="delivery_point_id_title">ID заказа</span>
                        </div>

                        <input type="text" name="order_id" id="order_id" title="" value=""
                               class="form-control " autocomplete="off" aria-label="" required>
                    </div>

                    <div class="input-group mb-2 col-sm-4">
                        <button type="submit" class="btn btn-danger mr-1 mb-1 waves-effect waves-light">Удалить заказ</button>
                    </div>

                </div>
            </form>
        </div>




    </div>

@endsection



