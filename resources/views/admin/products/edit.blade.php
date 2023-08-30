@extends('admin.main')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ url()->previous() }}"><button class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Закрыть</button></a>
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
        <div class="dropzone form-group" id="image-1">
            <div class=" dz-message" data-dz-message><span></span>
            </div>
        </div>
{{--        <div class="dropzone col-sm-8" id="image-2"><div class=" dz-message" data-dz-message><span>Дополнительные фото</span></div></div>--}}
    </div>

    <form name="form" method="post" id="product-form" action="{{ route('admin.catalog.products.edit',$data->id) }}" enctype="multipart/form-data"  >
        {{ csrf_field() }}

        <div class="row">

            <div class="input-group mb-1 col-sm-6">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="name_label">Название:</span>
                </div>
                <input type="text" name="name" id="name" value="{{ @$data->name }}"  class="form-control " autocomplete="off" required aria-label="" aria-describedby="name_label">
            </div>

            <div class="input-group mb-1 col-sm-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="car">Категория:</span>
                </div>
                <select  name="category_id" class="form-control" id="category_id" >
                    @foreach(@$categories as $k=>$v)
                        <option value="{{ $k }}" @if($data->category_id == $k) selected @endif>{{ $v }}</option>
                    @endforeach
                </select>
            </div>

            <div class="input-group mb-1 col-sm-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="car">Бренд:</span>
                </div>
                <select  name="brand_id" class="form-control" id="brand_id" >
                    @foreach(@$brands as $k=>$v)
                        <option value="{{ $k }}"  @if($data->brand_id == $k) selected @endif>{{ $v }}</option>
                    @endforeach
                </select>
            </div>

            <div class="input-group mb-1 col-sm-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="sku_label">Артикул:</span>
                </div>
                <input type="text"  name="sku" id="sku" value="{{ @$data->sku }}"  class="form-control " autocomplete="off" required aria-label="" aria-describedby="sku_label">
            </div>

            <div class="input-group mb-1 col-sm-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="price_label">Цена</span>
                </div>
                <input type="text"  name="price" id="price" value="{{ @$data->price }}"  class="form-control " autocomplete="off" required aria-label="" aria-describedby="price_label">
            </div>

            <div class="input-group col-sm-12 form-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="price_label">Теги</span>
                </div>
                <select name="tags[]" id="tags" multiple data-role="tagsinput">
                </select>
                <div id="search-tag_"></div>
            </div>

            <div class="col-sm-12 form-group tags-input bootstrap-tagsinput" id="myTags">
            <span class="data">
                    <span class="tag"><span class="text" ></span><span class="close">&times;</span></span>
                </span>

            <span class="autocomplete">
                    <input type="text">
                    <div class="autocomplete-items">
                    </div>
                </span>
            </div>

            <div class="input-group mb-1 col-sm-4">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="car">Видимость:</span>
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
                <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Сохранить</button>
            </div>

        </div>
    </form>

    <div class="col-sm-2 form-group" id="add_modifications">
        <input class="btn form-control btn-warning" type="button" value="Добавить">
    </div>
    <!-- Наборы модификаций -->
    <div class="col-sm-12 mt-2 form-group">
        <h4 class="mt-2">Модификации</h4>

    </div>

    <!-- Наборы модификаций -->
    <div class="col-sm-12 form-group">
        <div class="row">
            <div class="col-sm-9 ">
                <div class="row modifications">
                </div>
            </div>
        </div>
    </div>
    <!-- END Наборы модификаций -->


@endsection
@section('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.0/min/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.0/dropzone.js"></script>
    <link rel="stylesheet" href="/assets/css/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="/assets/css/bootstrap-colorselector.min.css">
    <script src="/assets/js/bootstrap-tagsinput.js"></script>
    <script src="/assets/js/bootstrap-colorselector.min.js"></script>

    <style>

        #image-1 {
            background: no-repeat url("{{ @$files->where('main',1)->first()->url}}")  50% / 100%;;
        }


    </style>

    <script>

        $(document).ready(function () {

            $('.modifications').append(colors)
            $(document).on('click', '#add_modifications', function () {

                var html = '';
                html =  '<div class="col-sm-12 parent"><div class="row "><div class="input-group mb-1 col-sm-4"> ' +
                    '<div class="input-group-prepend"><span class="input-group-text" id="sku_label">Артикул</span></div>' +
                    '<input class="form-control" name="params[sku][]" value="" placeholder="" autocomplete="off" required></div>' +
                    '<div class="input-group mb-1 col-sm-4"><div class="input-group-prepend"><span class="input-group-text" id="color_label">Цвет</span></div>' +
                    '<input class="form-control" name="params[color][]" value="" placeholder="" autocomplete="off" required></div>' +
                    '<div class="input-group mb-1 col-sm-4">'+ colors +'</div>' +
                    '<div class="col-sm-1 form-group"><button type="button" class="btn btn-sm btn-outline-danger delete_row__"><i class="fa fa-trash-o"></i></button></div>' +
                    ' </div></div>';
                $('.modifications').append(html)

            });

            initColors();

            $(document).on('click', '.delete_row__', function () {
                $(this).closest('.parent').remove();
            });

            function initColors() {
                $('.colorselector').colorselector({
                    callback: function (value, color, title) {
                        $("#colorValue").val(value);
                        $("#colorColor").val(color);
                        $("#colorTitle").val(title);
                    }
                });
            }

        });
    </script>
    <script type="text/javascript">
        $(function () {

            @foreach(@$data->tags()->get() as $k=>$v)
            $('#tags').tagsinput('add', '{{ @$v->name }}');
            @endforeach


            var cat_id = $('select[name="category_id"]').val();
            set(cat_id);

            $(document).on('change','#category_id',function () {
                cat_id = $('select[name="category_id"]').val();
                set(cat_id);
            });


            $('#product-form').on('keyup keypress', function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            function set(cat_id) {
                $('.attributes').html('');
                html = '';

                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    data: {process:true},
                    url: "/admin/catalog/products/attributes/" + cat_id + '/' + {{ @$data->id }},
                }).done(function(response){
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
                                                '<input type="text" name="value[attribute][' + i3 + '][]" class="form-control" value="' + v3 + '" aria-label="" ></div>';

                                            $.each(response.data[index]['presets']['params']['name'], function (i4, v4) {
                                                try {
                                                    if (response.product[i4][i3] != null) {
                                                        html += '<div class="col-sm-3 mb-1 "><label>' + v4 + '</label><input class="form-control" name="value[preset][' + i4 + '][]" value="' + response.product[i4][i3] + '"></div>';
                                                    }
                                                    else
                                                        html += '<div class="col-sm-3 mb-1 "><label>' + v4 + '</label><input class="form-control" name="value[preset][' + i4 + '][]" value=""></div>';
                                                }
                                                catch (e) {
                                                    
                                                }
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

                return html;
            }

        });
    </script>
    <script type="text/javascript">

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
                thumbnailWidth: 200,
                thumbnailHeight: 300,
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
                            // Make sure the button click doesn't submit the form:
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
    <script>
        function runSuggestions(element, query) {

            let sug_area = $(element).parents().eq(2).find('.autocomplete .autocomplete-items');
            if (query.length >= "2") {
                $.ajax({
                    type: 'get',
                    url: '{{ route('admin.catalog.products.tag.search') }}',
                    data: {search: query},
                    success: function (response) {
                        tag_input_suggestions_data = response.data;
                        $.each(response.data, function (key, value) {
                            let template = $('<span class="tag badge badge-primary ml-1">' + value.name + '<span data-role="remove"></span></span>').hide();
                            sug_area.append(template);
                            template.show()
                        })
                    }
                });
            }

        };

        $(function () {
           let _tag_input_suggestions_data = null;
            $.fn.tagsValues = function (method /*, args*/) {
                var data = [];
                $(this).find(".data .tag .text").each(function (key, value) {
                    let v = $(value).attr('_value');
                    data.push(v);
                });
                return data;
            };
            $('.tags-input').click(function () {
                $(this).find('input').focus();
            });
            $(document).on("click", ".tags-input .data .tag .closest", function () {
                $(this).parent().remove()

            });

            $(document).on("click", ".tags-input .autocomplete-items div", function () {
                let index = $(this).index()
                let data = _tag_input_suggestions_data[index];
                let data_holder = $(this).parents().eq(4).find('.data')
                _add_input_tag(data_holder, data.id, data.name)
                $('.tags-input .autocomplete-items').html('');

            })

            $(".tags-input input").on("keydown", function (event) {
                if (event.which == 13) {
                    let data = $(this).val()
                    if (data != "") _add_input_tag(this, data, data)
                }
            });


            $(".tags-input input").on("focusout", function (event) {
                $(this).val("")
                var that = this;
                setTimeout(function () {
                    $(that).parents().eq(2).find('.autocomplete .autocomplete-items').html("");
                }, 500);
            });


            function _add_input_tag(el, data, text) {
                let template = '<span class="tag badge badge-primary"><span class="text" _value=' + data + '>' + text + '</span><span data-role="remove" class="closest"></span></span>\n';
                $(el).parents().eq(2).find('.data').append(template);
                $(el).val('')
            }

            $(".tags-input input").on("keyup", function (event) {
                var query = $(this).val()

                if (event.which == 8) {
                    if (query == "") {
                        $('.tags-input .autocomplete-items').html('');
                        return;
                    }
                }

                $('.tags-input .autocomplete-items').html('');
                runSuggestions($(this), query)

            });

        });

    </script>
    <script>


        var colors = '<select class="colorselector">' +
            '<option value="106" data-color="#FFFFFF">Белый</option>' +
            '<option value="47" data-color="#7367f0">Синий</option>' +
            '<option value="87" data-color="#f71f20">Красный</option>' +
            '<option value="15" data-color="#ff00b1">Розовый</option>' +
            '<option value="24" data-color="#19d46c">Зеленый</option>' +
            '<option value="78" data-color="#000000">Черный</option>' +
            '</select>';

        $("#setColor").click(function(e) {
            $(".colorselector").colorselector("setColor", "#008B8B");
        });

        $("#setValue").click(function(e) {
            $(".colorselector").colorselector("setValue", 18);
        });
    </script>

@endsection


