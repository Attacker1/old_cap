@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('client.settings.index') }}"><i class="fas fa-window-close text-danger"></i></a>
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

            <form name="form" method="post" action="{{ route('client.settings.store') }}">
                {{ csrf_field() }}

                <div class="row">

                    <div class="input-group mb-2 col-sm-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="name_label">Наименование</span>
                        </div>
                        <input type="text" name="name" id="name" value="{{ @$data->name }}"  class="form-control " autocomplete="off"  required aria-label="" aria-describedby="name_label">
                    </div>


                    <div class="col-sm-2 mb-2 form-group" id="add_condition">
                        <input class="btn form-control btn-warning float-right" type="button"
                               value="Добавить">
                    </div>

                    <!-- Наборы параметров -->
                    <div class="col-sm-12 mt-4 mb-4">
                        <h4 class="mb-2">Параметры</h4>
                    </div>

                    <div class="col-sm-12 form-group">
                        <div class="row">
                            <div class="col-sm-9 " >
                                <div class="row conditions">
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- END Наборы параметров -->
                    <div class="input-group mb-3 col-sm-12">
                        <button type="submit" class="btn btn-success mr-1 mb-1 waves-effect waves-light">Сохранить</button>
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
                html =  '<div class="col-sm-12 parent"><div class="row ">' +
                    '<div class="input-group mb-1 col-sm-4"> ' +
                    '<div class="input-group-prepend"><span class="input-group-text" id="preset_label">Наименование</span></div>' +
                    '<input class="form-control" name="new[title][]" value="" placeholder="Наименование" autocomplete="off" required></div>' +
                    '<div class="input-group mb-1 col-sm-4"> ' +
                    '<div class="input-group-prepend"><span class="input-group-text" id="preset_label">Значение</span></div>' +
                    '<input class="form-control" name="new[value][]" value="" placeholder="Значение" autocomplete="off" required></div>' +
                    '<div class="col-sm-1 mb-1 form-group"><button type="button" class="btn btn-sm btn-outline-danger delete_row__"><i class="fa fa-trash-o"></i></button></div>' +
                    ' </div></div>';
                $('.conditions').append(html)

            });

            $(document).on('click', '.delete_row__', function () {
                $(this).closest('.parent').remove();
            });

        });
    </script>
@endsection


