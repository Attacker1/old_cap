@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('manage.tags.index') }}"><i class="fas fa-chevron-circle-left text-danger"></i></a>
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

            <form name="form" method="post" action="{{ route('manage.tags.add') }}">
                {{ csrf_field() }}

                <div class="flex-column">

                    <div class="input-group mb-3 col-sm-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="name_label">Название</span>
                        </div>
                        <input type="text" name="name" id="name" value="{{old('name') }}"  class="form-control " autocomplete="off"  aria-label="" aria-describedby="name_label" required>
                    </div>

                    <div class="input-group mb-3 col-sm-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="color_label">Цвет</span>
                        </div>
                        <input type="color" name="color" id="color" value="{{old('name') }}"  class="form-control " autocomplete="off"  aria-label="" aria-describedby="name_label" required>
                    </div>

                    <div class="input-group mb-3 col-sm-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="car">Тип</span>
                        </div>
                        <select name="type" class="form-control" id="type" required>
                            <option value="lead" >Сделка</option>
                        </select>
                    </div>

                    <div class="input-group mb-3 col-sm-12">
                        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Добавить</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection



