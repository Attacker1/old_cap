@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('admin.manage.users.index') }}"><i class="fas fa-chevron-circle-left text-danger"></i></a>
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

    <form name="form" method="post" action="{{ route('admin.manage.users.edit', $data->id) }}">
        {{ csrf_field() }}

        <div class="row">

            <div class="input-group mb-3 col-sm-8">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="name_label">Имя</span>
                </div>
                <input type="text" name="name" id="name" value="{{ @$data->name }}"  class="form-control " autocomplete="off"  aria-label="" aria-describedby="name_label" required>
            </div>

            <div class="input-group mb-3 col-sm-4">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="car">Статус:</span>
                </div>
                <select  name="disabled" class="form-control" id="disabled" required>
                    <option value="0" @if(@$data->name != 1) selected @endif>Активен</option>
                    <option value="1" @if(@$data->name == 1) selected @endif>Блокирован</option>
                </select>
            </div>

            <div class="input-group mb-3 col-sm-4">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="email_label">Login/email:</span>
                </div>
                <input type="email" name="email" id="email" value="{{ @$data->email }}"  class="form-control " autocomplete="off"  aria-label="" aria-describedby="email_label" required>
            </div>

            <div class="input-group mb-3 col-sm-4">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="password_label">Пароль:</span>
                </div>
                <input type="password" name="password" id="password" value="" placeholder="Для смены пароля, заполнить поле"  class="form-control " autocomplete="off"  aria-label="" aria-describedby="password_label" >
            </div>

            <div class="input-group mb-3 col-sm-4">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="car">Роль:</span>
                </div>
                <select  name="role_id" class="form-control" id="role_id" required>
                    @foreach(@$roles as $k=>$v)
                        <option value="{{ $k }}" @if(@$data->roles()->first()->id == $k) selected @endif>{{ $v }}</option>
                    @endforeach
                </select>
            </div>

            <div class="input-group mb-3 col-sm-4">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="amo_name_label">Амо стилист:</span>
                </div>
                <input type="text" name="amo_name" id="amo_name" value="{{ @$data->amo_name }}"  class="form-control " autocomplete="off"  aria-label="" aria-describedby="amo_name_label" >
            </div>

            <div class="input-group mb-3 col-sm-12">
                <button type="submit" class="btn btn-success mr-1 mb-1 waves-effect waves-light">Сохранить</button>
            </div>

        </div>
    </form>
        </div>
    </div>
@endsection



