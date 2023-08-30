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

    <form name="form" method="post" action="{{ route('admin.color.update',$data->id) }}">
        {{ csrf_field() }}

        <div class="row">

            <div class="input-group mb-3 col-sm-6">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="name_label">Наименование</span>
                </div>
                <input type="text" name="name" id="name" value="{{ @$data->name }}"  class="form-control " autocomplete="off" required aria-label="" aria-describedby="name_label">
            </div>

            <div class="input-group mb-3 col-sm-6">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="hex_label">HEX</span>
                </div>
                <input type="text" name="hex" id="hex" value="{{ @$data->hex }}"  class="form-control " autocomplete="off" required aria-label="" aria-describedby="hex_label">
            </div>

            <div class="input-group mb-3 col-sm-12">
                <button type="submit" class="btn btn-success mr-1 mb-1 waves-effect waves-light">Сохранить</button>
            </div>



        </div>
    </form>

@endsection
@section('scripts')

    <script>

        $(document).ready(function () {

        });
    </script>

@endsection



