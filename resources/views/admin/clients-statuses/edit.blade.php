@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection


@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-body">
    <div class="row justify-content-center mb-4">
        <form method="POST" action="{{route('clients-statuses.update', $statusData->id)}}" class="col-md-7">
        {{ csrf_field() }}
            @method('PUT')
            <div class="form-group">
                <label for="inputUserName">Название</label>
                <input type="text" name="name" class="form-control" value="{{$statusData->name}}" id="inputClientName" aria-describedby="nameHelp" placeholder="Название" required autofocus>
                @if ($errors->has('name'))
                    <small class="form-text text-danger font-weight-bold">{{ $errors->first('name') }}</small>
                @endif
            </div>

            <button type="submit" class="btn btn-primary waves-effect">Сохранить</button>
            <a href="{{route('admin.clients-statuses.list')}}" class="btn btn-outline-secondary text-bold text-dark waves-effect ml-3">К списку</a>
        </form>
    </div>
    </div>
    </div>
@endsection
