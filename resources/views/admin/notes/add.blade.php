@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('notes.list') }}">
            <i class="fas fa-window-close text-danger"></i>
        </a>
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

            <div class="row">

                <div class="input-group mb-1 col-sm-6">
                    <form name="form" method="post" action="{{ route('notes.list.create') }}">
                        {{ csrf_field() }}
                        <div class="input-group ">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="img_url_label" title="из AMO CRM">ID:</span>
                            </div>
                            <input type="text" name="order_id" class="form-control" placeholder="ID заказа из AMO CRM"
                                   aria-label="img_url_label" autocomplete="off">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-outline-primary waves-effect">Импорт</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('submit', 'form', function () {
                $('.body-block-loaders').removeClass('d-none');
            });
        });
    </script>
@endsection



