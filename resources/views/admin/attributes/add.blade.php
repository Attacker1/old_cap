@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('admin.catalog.attributes.index') }}"><i class="fas fa-window-close text-danger"></i></a>
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

    <form name="form" method="post" action="{{ route('admin.catalog.attributes.create') }}">
        {{ csrf_field() }}

    <div class="row">

        <div class="input-group mb-1 col-sm-7">
            <div class="input-group-prepend">
                <span class="input-group-text" id="name_label">Наименование</span>
            </div>
            <input type="text" name="name" id="name" value="{{ old('name') }}"  class="form-control " autocomplete="off"  required aria-label="" aria-describedby="name_label">
        </div>

        <div class="input-group mb-1 col-sm-3"> <div class="input-group-prepend"><span class="input-group-text" id="preset_label">сет</span></div>
            <select name="preset_id" class="form-control" aria-label=""
                    aria-describedby="preset_label">
                <option value="1" selected>Не применяется</option>
                @foreach($presets as $k=>$v)
                    <option value="{{ $k }}"> {{ $v }}</option>
                @endforeach </select>
        </div>

        <div class="col-sm-2 form-group" id="add_condition">
            <input class="btn form-control btn-warning float-right" type="button"
                   value="Добавить">
        </div>

        <!-- Наборы свойств -->
        <div class="col-sm-12">
            <h4 class="mb-2">Свойства</h4>
        </div>

        <!-- Наборы свойств -->
        <div class="col-sm-12 form-group">
            <div class="row">
                <div class="col-sm-9 ">
                    <div class="row conditions">
                    </div>
                </div>
            </div>
        </div>
        <!-- END Наборы свойств -->

        <div class="input-group mb-3 col-sm-12">
        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Создать</button>
        </div>



    </div>
    </form>
    </div>
    </div>
@endsection
@section('scripts')

    <script>
        $(document).ready(function () {

            $(document).on('click', '#add_condition', function () {

                var html = '';
                html =  '<div class="col-sm-12 parent"><div class="row "><div class="input-group mb-1 col-sm-6"> ' +
                    '<div class="input-group-prepend"><span class="input-group-text" id="preset_label">значение</span></div>' +
                    '<input class="form-control" name="params[value][]" value="" placeholder="" autocomplete="off" required></div>' +
                    '<div class="col-sm-1 form-group"><button type="button" class="btn btn-sm btn-outline-danger delete_row__"><i class="fa fa-trash-o"></i></button></div>' +
                    ' </div></div>';
                $('.conditions').append(html)

            });

            $(document).on('click', '.delete_row__', function () {
                $(this).closest('.parent').remove();
            });

        });
    </script>

@endsection


