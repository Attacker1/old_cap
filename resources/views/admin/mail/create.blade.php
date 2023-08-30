@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('mail.templates.index') }}">
            <i class="fas fa-chevron-circle-left text-danger"></i>
        </a>
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

            <form name="form" method="post" action="{{ route('mail.templates.create') }}">
                {{ csrf_field() }}

                <div class="row">

                    <div class="input-group mb-3 col-sm-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="name_label">Наименование</span>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control "
                               autocomplete="off" required aria-label="" aria-describedby="name_label">
                    </div>

                    <div class="input-group mb-3 col-sm-8">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="hex_label">Примечание</span>
                        </div>
                        <input type="text" name="description" id="description" value="{{ old('description') }}"
                               class="form-control " autocomplete="off" required aria-label=""
                               aria-describedby="description_label">
                    </div>

                    <div class="form-group mb-3 col-sm-12">
                        <label for="html">HTML</label>
                        <textarea type="text" name="html" id="html" class="form-control form-control-solid"
                                  rows="6">{{ old('html') }}</textarea>
                    </div>

                    <div class="form-group mb-3 col-sm-12">
                        <label for="text">TEXT</label>
                        <textarea type="text" name="text" id="text" class="form-control form-control-solid"
                                  rows="6">{{ old('text') }}</textarea>
                    </div>

                    <div class="form-group col-sm-12">
                        <label>Обязательные параметры (с новой строки)</label>
                        <textarea type="text" name="params" id="params" class="form-control form-control-solid"
                                  rows="6">{{ old('params') }}</textarea>
                    </div>

                    <div class="input-group mb-3 col-sm-4">
                        <button type="submit" class="btn btn-success mr-1 mb-1 waves-effect waves-light">Добавить
                        </button>
                    </div>


                </div>
            </form>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function () {

        });
    </script>
@endsection



