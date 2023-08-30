@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('quantity.index') }}"><i class="fas fa-window-close text-danger"></i></a>
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

            <form name="form" method="post" action="{{ route('quantity.create') }}">
                {{ csrf_field() }}

                <div class="row">

                    <div class="input-group mb-3 col-sm-6">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="name_label">Кол-во</span>
                        </div>
                        <input type="number" min="0" max="999" name="amount" id="amount" value="" class="form-control "
                               autocomplete="off" required aria-label="" aria-describedby="name_label">
                    </div>

                    <div class="input-group mb-3 col-sm-6">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="hex_label">Product_id</span>
                        </div>
                        <input type="text" name="product_id" id="product_id" value="" class="form-control "
                               autocomplete="off" required aria-label="" aria-describedby="hex_label">
                    </div>

                    <div class="col-sm-12 form-group tags-input bootstrap-tagsinput" id="myTags">
            <span class="data">
                    <span class="tag"><span class="text"></span><span class="close">&times;</span></span>
                </span>

                        <span class="autocomplete">
                    <input type="text">
                    <div class="autocomplete-items">
                    </div>
                </span>
                    </div>


                    <div class="input-group mb-3 col-sm-12">
                        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Добавить
                        </button>
                    </div>


                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <link rel="stylesheet" href="/assets/css/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="/assets/css/bootstrap-colorselector.min.css">
    <script src="/assets/js/bootstrap-tagsinput.js"></script>
    <script>
        function runSuggestions(element, query) {

            let sug_area = $(element).parents().eq(2).find('.autocomplete .autocomplete-items');
            if (query.length >= "2") {
                $.ajax({
                    type: 'get',
                    url: '{{ route('quantity.product.search') }}',
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
@endsection



