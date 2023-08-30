@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('quantity.index') }}"><i class="fas fa-window-close text-danger"></i></a>
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

            <form name="form" method="post" action="{{ route('quantity.edit',$data->id) }}">
                {{ csrf_field() }}

                <div class="row">

                    <div class="input-group mb-3 col-sm-6">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="name_label">Кол-во</span>
                        </div>
                        <input type="number" min="0" max="999" name="amount" id="amount" value="{{ @$data->amount }}"
                               class="form-control " autocomplete="off" required aria-label=""
                               aria-describedby="name_label">
                    </div>

                    <div class="input-group mb-3 col-sm-6">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="hex_label">Product_id</span>
                        </div>
                        <input type="text" name="product_id" id="product_id" value="{{ @$data->product_id }}"
                               class="form-control " autocomplete="off" required aria-label=""
                               aria-describedby="hex_label">
                    </div>

                    <div class="input-group mb-3 col-sm-12">
                        <button type="submit" class="btn btn-success mr-1 mb-1 waves-effect waves-light">Сохранить
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
@endsection



