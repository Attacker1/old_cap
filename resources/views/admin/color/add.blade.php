@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('admin.color.index') }}"><button class="btn btn-sm btn-danger"><i class="fa fa-close"></i></button></a>
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

    <form name="form" method="post" action="{{ route('admin.color.store') }}">
        {{ csrf_field() }}

    <div class="row">

        <div class="input-group mb-3 col-sm-6">
            <div class="input-group-prepend">
                <span class="input-group-text" id="name_label">Наименование</span>
            </div>
            <input type="text" name="name" id="name" value=""  class="form-control " autocomplete="off" required aria-label="" aria-describedby="name_label">
        </div>

        <div class="input-group mb-3 col-sm-6">
            <div class="input-group-prepend">
                <span class="input-group-text" id="hex_label">HEX</span>
            </div>
            <input type="text" name="hex" id="hex" value=""  class="form-control " autocomplete="off" required aria-label="" aria-describedby="hex_label">
        </div>


        <div class="input-group mb-3 col-sm-12">
        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Добавить</button>
        </div>



    </div>
    </form>

@endsection
@section('scripts')

    <script>

        $(document).ready(function () {

            $(document).on('click', '#add_condition', function () {

                var html = '';
                html =  '<div class="col-sm-12 parent"><div class="row "><div class="col-sm-4 form-group">' +
                        '<input class="form-control" name="params[name][]" value="" placeholder="Значение" autocomplete="off" required></div>' +
                        '<div class="col-sm-1 form-group"><button type="button" class="btn btn-sm btn-danger delete_row__"><i class="fa fa-trash-o"></i></button></div>' +
                        ' </div></div>';
                $('.conditions').append(html)

            });

            $(document).on('click', '.delete_row__', function () {
                $(this).closest('.parent').remove();
            });

        });
    </script>

@endsection



