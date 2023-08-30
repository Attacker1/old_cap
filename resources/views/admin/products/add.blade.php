@extends('admin.main')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('admin.catalog.products.index') }}"><button class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Закрыть</button></a>
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
    <div class="row">

{{--        <div class="input-group mb-3 col-sm-4">--}}
{{--            <form action="{{ route('attachments.dropzone')  }}" class="dropzone form-group" id="image1">--}}
{{--                <div class="dz-message" data-dz-message><span>Добавьте основную фотографию</span></div>--}}
{{--                {{ csrf_field() }}--}}
{{--            </form>--}}
{{--        </div>--}}

        <div class="dropzone form-group" id="image-1">
            <div class=" dz-message" data-dz-message><span></span>
            </div>
        </div>
        <div class="dropzone col-sm-8" id="image-2"><div class=" dz-message" data-dz-message><span>Дополнительные фото</span></div></div>
    </div>

    <form name="form" method="post" action="{{ route('admin.catalog.products.create') }}" enctype="multipart/form-data"  >
        {{ csrf_field() }}

    <div class="row">

        <div class="input-group mb-1 col-sm-6">
            <div class="input-group-prepend">
                <span class="input-group-text" id="name_label">Название:</span>
            </div>
            <input type="text" name="name" id="name" value="{{ old('name') }}"  class="form-control " autocomplete="off" required aria-label="" aria-describedby="name_label">
        </div>

        <div class="input-group mb-1 col-sm-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="car">Категория:</span>
            </div>
            <select  name="category_id" class="form-control" id="category_id" >
                <option value="" disabled selected >Выбор категории</option>
                @foreach(@$categories as $k=>$v)
                    <option value="{{ $k }}" >{{ $v }}</option>
                @endforeach
            </select>
        </div>

        <div class="input-group mb-1 col-sm-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="car">Бренд:</span>
            </div>
            <select  name="brand_id" class="form-control" id="brand_id" >
                @foreach(@$brands as $k=>$v)
                    <option value="{{ $k }}" selected>{{ $v }}</option>
                @endforeach
            </select>
        </div>

        <div class="input-group mb-1 col-sm-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="sku_label">Артикул:</span>
            </div>
            <input type="text"  name="sku" id="sku" value="{{ old('sku') }}"  class="form-control " autocomplete="off" required aria-label="" aria-describedby="sku_label">
        </div>

        <div class="input-group mb-1 col-sm-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="price_label">Цена</span>
            </div>
            <input type="text"  name="price" id="price" value="{{ old('price') }}"  class="form-control " autocomplete="off" required aria-label="" aria-describedby="price_label">
        </div>

        <div class="input-group mb-1 col-sm-2">
            <div class="input-group-prepend">
                <span class="input-group-text" id="visible_title">Видимость:</span>
            </div>
            <select  name="visible" class="form-control" id="visible" >
                <option value="0" selected>ДА</option>
                <option value="1" >НЕТ</option>
            </select>
        </div>

        <!-- Наборы характеристик -->
        <div class="col-sm-12">
            <div class="attributes"></div>
        </div>
        <!-- END Наборы характеристик -->

        <input type="hidden" name="attachment1_id" id="attachment1_id">
        <input type="hidden" name="attachment2_id" id="attachment2_id">

        <div class="input-group mb-3 col-sm-12">
        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Добавить</button>
        </div>

    </div>
    </form>


@endsection
@section('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.0/min/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.0/dropzone.js"></script>

    <style>


    </style>
    <script>
        $(function () {

            $(document).on('change','#category_id',function () {
                var cat_id = $('select[name="category_id"]').val();
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    data: {process:true},
                    url: "/admin/catalog/products/attributes/" + cat_id,
                }).done(function(response){

                    $('.attributes').html('');
                    html = '';
                                    if(response.result == true) {

                                        $.each(response.data, function (index, v) {
                            $.each(v, function (i, val) {
                                // Названия
                                if (i == 'name') {
                                    html += "<h5>" + val + "</h5>";
                                }

                            if (i == 'params') {
                                $.each(val, function (i2, v2) {
                                    $.each(v2, function (i3, v3) {
                                        html += '<input type="hidden" name="preset_id" value="' + response.data[index]['presets']['id'] + '">';
                                        html += '<input type="hidden" name="attribute_id" value="' + response.data[index]['id'] + '">';
                                        html += '<div class="row col-sm-12">';
                                        html += '<div class="input-group mb-1 col-sm-2"><div class="input-group-prepend"><div class="input-group-text"><div class="vs-checkbox-con"><input type="checkbox" value="false">' +
                                            '<span class="vs-checkbox vs-checkbox-sm"><span class="vs-checkbox--check"><i class="vs-icon feather icon-check"></i></span></span></div></div></div>' +
                                            '<input type="text" name="value[attribute][' + i3 + '][]" class="form-control" value="'+v3+'" aria-label="" ></div>';

                                        $.each(response.data[index]['presets']['params']['name'], function (i4, v4) {
                                            html += '<div class="col-sm-3 mb-1 "><label>'+v4+'</label><input class="form-control" name="value[preset]['+i4+'][]" value=""></div>';
                                        });
                                        html += '</div>';
                                    });
                                    html += '</div>';
                                });

                            }
                            });
                        });
                    }
                    else{
                    }
                    $('.attributes').append(html);

                }).fail(function () {
                    swal({
                        title: 'Ошибка обработки запроса!',
                        icon: "error",
                    });
                });
            });


        });
    </script>
    <script>

        Dropzone.autoDiscover = false;

        $('.dropzone').each(function(){
            var options = $(this).attr('id').split('-');
            var dropUrl = "{{ route('attachments.dropzone')  }}";
            var dropMaxFiles = parseInt(options[1]);
            var dropParamName = 'file' + options[1];
            var indexes = "#attachment"+options[1]+"_id";
            var height = $(indexes).height();
            console.log(height);

            $(this).dropzone({

                url: dropUrl,
                maxFiles: dropMaxFiles,
                addRemoveLinks: false,
                thumbnailWidth: 150,
                thumbnailHeight: 225,
                resize: function(file) {
                    var resizeInfo = {
                        srcX: 0,
                        srcY: 0,
                        trgX: 0,
                        trgY: 0,
                        srcWidth: file.width,
                        srcHeight: file.height,
                        trgWidth: this.options.thumbnailWidth,
                        trgHeight: this.options.thumbnailHeight
                    };

                    return resizeInfo;
                },
                parallelUploads: 20,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dictResponseError: 'Error uploading file!',
                    init: function () {
                        this.on("addedfile", function(file) {
                            var removeButton = Dropzone.createElement("<i class=\"fa fa-close close-btn\"></i>");
                            var _this = this;

                            removeButton.addEventListener("click", function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                _this.removeFile(file);
                            });

                            file.previewElement.appendChild(removeButton);
                        });

                        this.on("success", function (file, response) {
                            console.log( response.uuid);
                            console.log( indexes);
                            $(indexes).attr('value',response.uuid);
                        });
                    }
            });

        });

    </script>

@endsection


