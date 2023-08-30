@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
@endsection

@section('content')

    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form name="form" method="post" action="{{ route('test.triggers') }}">
        {{ csrf_field() }}

    <div class="row">

        <div class="input-group mb-3 col-sm-4">
            <div class="input-group-prepend">
                <span class="input-group-text" id="parent_id_title">ID сделки:</span>
            </div>
            <input name="lead_id" id="lead_id" class="form-control" value="{{ old('lead_id') }}">
        </div>

        <div class="input-group mb-3 col-sm-4">
            <div class="input-group-prepend">
                <span class="input-group-text" id="parent_id_title">Статус:</span>
            </div>
            <select name="state_id" class="form-control " id="state_id">
                @if(!empty($states))
                    @foreach($states as $k=>$v)
                        <option value="{{ $k }}" >{{ @$v }}</option>
                    @endforeach
                @endif
            </select>
        </div>


        <div class="input-group mb-3 col-sm-3">
        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Выполнить</button>
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



