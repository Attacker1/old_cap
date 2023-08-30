@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('lead.corrections.index') }}"><i class="fas fa-window-close text-danger"></i></a>
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

            <form name="form" method="post" action="{{ route('lead.corrections.store') }}">
                {{ csrf_field() }}
                <div class="row">

                    <div class="input-group mb-6 col-sm-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="lead_uuid">ID сделки</span>
                        </div>
                        <input type="number" name="amo_id" id="amo_id" value="{{ @old('amo_id') }}"
                               class="form-control "  required autocomplete="off" aria-label=""
                               aria-describedby="amo_id_label">

                    </div>

                    <div class="input-group mb-6 col-sm-12">
                        <button type="submit" class="btn btn-success waves-effect waves-light">Создать коррекцию
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection



