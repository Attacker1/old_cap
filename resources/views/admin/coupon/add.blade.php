@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('coupon.index') }}"><i class="fas fa-chevron-circle-left text-danger"></i></a>
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

            <form name="form" method="post" action="{{ route('coupon.store') }}">
                {{ csrf_field() }}

                <div class="row">

                    <div class="input-group mb-3 col-sm-6">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="name_label">Наименование</span>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control "
                               autocomplete="off" required aria-label="" aria-describedby="name_label">
                    </div>

                    <div class="input-group mb-3 col-sm-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="type_label">Тип купона</span>
                        </div>
                        <input type="text" name="type" id="type" value="fixed" class="form-control " autocomplete="off"
                               required aria-label="" aria-describedby="type_label">
                    </div>

                    <div class="input-group mb-3 col-sm-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="price_label">Цена</span>
                        </div>
                        <input type="number" min="0" name="price" id="price" value="{{ old('price') }}"
                               class="form-control " autocomplete="off" required aria-label=""
                               aria-describedby="price_label">
                    </div>

                    <div class="input-group mb-3 col-sm-12">
                        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Добавить
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection




