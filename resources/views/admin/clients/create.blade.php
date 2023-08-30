@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('actions')
    <a href="{{ route('clients.list') }}" class="" title="К списку">
        <i class="fas fa-chevron-circle-left text-danger"></i></a>
@endsection

@section('content')

    <div class="row">

            <form method="POST" action="{{route('clients.store')}}" >
                {{ csrf_field() }}
    <div class="card card-custom gutter-b  offset-3 col-sm-6 " id="kt_card_main">
        <div class="card-header ">
            <div class="card-title">
    <div class="row justify-content-center mb-4 col-sm-12">

            <div class="form-group col-sm-6">
                <label>Имя</label>
                <input type="text" name="name" class="form-control bg-light" value="{{ old('name') }}" placeholder="Имя" required autofocus autocomplete="off" maxlength="255">
                @if ($errors->has('name'))
                    <small class="form-text text-danger font-weight-bold">{{ $errors->first('name') }}</small>
                @endif
            </div>
            <div class="form-group col-sm-6">
                <label>Фамилия</label>
                <input type="text" name="second_name" class="form-control bg-light" value="{{ old('second_name') }}" placeholder="Фамилия" autofocus autocomplete="off" maxlength="255">
                @if ($errors->has('second_name'))
                    <small class="form-text text-danger font-weight-bold">{{ $errors->first('second_name') }}</small>
                @endif
            </div>
            <div class="form-group col-sm-6">
                <label>Телефон</label>
                <input type="text" name="phone" class="form-control bg-light" value="{{ old('phone') }}" id="inputPhone" required autofocus autocomplete="off" maxlength="20">
                @if ($errors->has('phone'))
                    <small class="form-text text-danger font-weight-bold">{{ $errors->first('phone') }}</small>
                @endif
            </div>
            <div class="form-group col-sm-6">
                <label>E-mail</label>
                <input type="text" name="email" class="form-control bg-light" value="{{ old('email') }}" placeholder="E-mail" autofocus autocomplete="off" maxlength="255">
                @if ($errors->has('email'))
                    <small class="form-text text-danger font-weight-bold">{{ $errors->first('email') }}</small>
                @endif
            </div>
            <div class="form-group  col-sm-6">
                <label>Реферальный код</label>
                <input 
                    type="text" name="referal_code" class="form-control bg-light"
                    value=" @if($errors->has('referal_code')) {{ old('referal_code') }} @else {{ $referal_code }} @endif" placeholder="Реферальный код" required autofocus autocomplete="off" maxlength="15">
                @if ($errors->has('referal_code'))
                    <small class="form-text text-danger font-weight-bold">{{ $errors->first('referal_code') }}</small>
                @endif
            </div>
            <div class="form-group mb-3  col-sm-6">
                <label>Статус</label>
                <select class="form-control bg-light" style="width:200px" name="status" >
                    <option value="">Не выбрано</option>
                    @foreach($client_statuses as $status)
                        <option value="{{$status->id}}" @if( $status->id == old('client_status_id')) selected @endif>{{$status->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group  col-sm-12">
                <label>Комментарии</label>
                <textarea name="comments" class="form-control bg-light" placeholder="Комментарии" autofocus autocomplete="off" maxlength="2000">{{old('comments')}}</textarea>
                @if ($errors->has('comments'))
                    <small class="form-text text-danger font-weight-bold">{{ $errors->first('comments') }}</small>
                @endif
            </div>

            <button type="submit" class="btn btn-primary waves-effect">Сохранить</button>
            <a href="{{route('clients.list')}}" class="btn btn-outline-secondary text-bold text-dark waves-effect ml-3">К списку</a>

    </div>
    </div>
    </div>
    </div>

            </form>
    </div>

@endsection
