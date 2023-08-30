@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('maincol') col-sm-12 @endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
<div class="float-right">
    <a href="{{ route('admin.catalog.products.create') }}"><button class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Добавить</button></a>
</div>
@endsection

@section('content')

    <div class="card card-custom gutter-b ">
        <div class="card-body">
    <!-- Фильтры -->
    <div class="sidebar">

        <form action="{{ route('admin.catalog.products.index') }}">
            {{ csrf_field() }}
            <input type="hidden" name="action" value="search">
            <div class="form-group input-group input-group-merge">

                <input type="text" class="form-control search-product" name="shop-search" id="shop-search" placeholder="Поиск товаров" aria-label="Поиск" aria-describedby="shop-search" autocomplete="off">
                <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="feather icon-search"></i>
                        </span>
                </div>
            </div>
        </form>

            <div>
                <form action="{{ route('admin.catalog.products.index') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="action" value="filters">
                    <input type="hidden" name="filters" >
                    <input type="hidden" name="price-from" id="price-from" >
                    <input type="hidden" name="price-to" id="price-to">
                <div class="col-sm-12  ">

                    <h6 class="filter-title mt-0">Цена</h6>
                    <div id="price-slider" class="form-group"></div>

                    <!-- Price Filter -->
{{--                    <div class="multi-range-price">--}}
{{--                        <h6 class="filter-title mt-0">Цена</h6>--}}
{{--                        <ul class="list-unstyled price-range" id="price-range">--}}
{{--                            <li>--}}
{{--                                <div class="custom-control custom-radio">--}}
{{--                                    <input type="radio" id="priceAll" name="price-range" class="custom-control-input"  @if(!empty($filters['price-range']) && $filters['price-range'] == '0:99000') checked="" @endif @if(empty($filters)) checked="" @endif value="0:99000">--}}
{{--                                    <label class="custom-control-label" for="priceAll">Все</label>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <div class="custom-control custom-radio">--}}
{{--                                    <input type="radio" id="priceRange2" name="price-range" class="custom-control-input" value="0:1000" @if(!empty($filters['price-range']) && $filters['price-range'] == '0:1000') checked="" @endif>--}}
{{--                                    <label class="custom-control-label" for="priceRange2">0 - 1000</label>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <div class="custom-control custom-radio">--}}
{{--                                    <input type="radio" id="priceARange3" name="price-range" class="custom-control-input" value="1000:3000" @if(!empty($filters['price-range']) && $filters['price-range'] == '1000:3000') checked="" @endif>--}}
{{--                                    <label class="custom-control-label" for="priceARange3">1000 - 3000</label>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <div class="custom-control custom-radio">--}}
{{--                                    <input type="radio" id="priceRange4" name="price-range" class="custom-control-input" value="3000:99000" @if(!empty($filters['price-range']) && $filters['price-range'] == '3000:99000') checked="" @endif>--}}
{{--                                    <label class="custom-control-label" for="priceRange4">&gt; 3000</label>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
                    <!-- Price Filter ends -->

                    <!-- Categories Starts -->
                    <div id="product-categories" class="form-group">
                        <h6 class="filter-title">Категория</h6>
                        <select multiple name="categories[]" class="form-control" size="10">
                            @foreach(\App\Http\Models\Catalog\Category::array() as $k=>$v )
                                <option value="{{ $k }}" @if(!empty($filters['categories']) && in_array($k,$filters['categories'])) selected @endif> {{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Categories Ends -->

                    <!-- Brands starts -->
                    <div class="brands">
                        <h6 class="filter-title">Бренды</h6>
                        <ul class="list-unstyled brand-list">
                            @foreach(\App\Http\Models\Catalog\Brand::array() as $k=>$v )
                                @if(!empty($v))
                                <li>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input"  name="brands[]" value="{{ $k }}" id="productBrand{{ $k }}" @if(!empty($filters['brands']) && in_array($k,$filters['brands'])) checked @endif>
                                        <label class="custom-control-label" for="productBrand{{ $k }}">{{ $v }}</label>
                                    </div>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <!-- Brand ends -->

                    <!-- Сбросить/Применить Filters Starts -->
                    <div id="clear-filters" class="">
                        <div class="form-group"><button type="submit" class="btn btn-block btn-success waves-effect waves-float waves-light">Применить</button></div>
                        <div class="form-group "><a href="{{ route('admin.catalog.products.index') }}"  class="btn btn-block btn-primary waves-effect waves-float waves-light">Сбросить</a></div>
                    </div>
                    <!-- Сбросить/Применить Filters Ends -->
                </div>
              </form>

            </div>


        </div>

    </div>
    </div>
    </div>
    </div>
    </div>
    </div>

{{-- Товары --}}

    <div class="col-sm-7 ecommerce-application">
        @if(!$products->count())
            <div class="col-sm-12">
                <div class="w-100 alert alert-primary">
                    <h4>Упс!</h4>
                    <p>Нет результатов по вашим критериям</p>
                    <a href="{{ route('admin.catalog.products.index') }}"  class="mt-2 btn  btn-primary waves-effect waves-float waves-light">Сбросить фильтры и поиск</a>
                </div>
            </div>
        @endif

    <section id="ecommerce-products" class="grid-view">

            @foreach($products as $item)
            <div class="card ecommerce-card">
                <div class="item-img text-center">
                    <a href="{{ route('admin.catalog.products.show',$item->id) }}">
                        @if($img = @$item->attachments()->where('main',1)->first()->url)
                        <img class="img-fluid card-img-top" src="{{ @$img }}" alt="img-placeholder"></a>
                        @else
{{--                            Заменить на свою иконку / пока нашел похожую --}}
                        <img class="img-fluid card-img-top" src="https://media.flaticon.com/dist/min/img/collections/collection-tour.svg" alt="img-placeholder"></a>
                        @endif
                </div>
                <div class="item-wrapper item-price-wrapper">
                <div class="item-price badge badge-danger">{{ number_format(round($item->price), 0, '.', ' ')  }} руб.</div>
                </div>
                <div class="card-body">
                    <h6 class="item-name">
                        <a class="text-body" href="{{ route('admin.catalog.products.show',$item->id) }}"> {{ @$item->name ?? 'Нет названия' }}</a>
                        <span class="card-text item-company">Бренд <a href="javascript:void(0)" class="company-name">{{ @$item->brands->name ?? 'Нет бренда' }}</a></span>
                    </h6>
                    <p class="card-text item-description text-dark">
                        {{ @$item->amo_name ?? '—' }}
                    </p>                    <p class="card-text item-description">
                        {{ @$item->content ?? 'Описание товара' }}
                    </p>

                    <div class="item-wrapper">
                        <span class="item-sku text-success">#{{ @$item->sku ?? 'Артикул' }}</span>
                        <span class="item-sku text-primary"> {{ @$item->brands->name ?? 'Нет бренда' }}</span>

                    </div>
                </div>
                @if(auth()->guard('admin')->user()->can('edit-products'))
                <hr>
                <div class="mb-1">
                    <div class="float-left"><a href="{{ route('admin.catalog.products.edit',$item->id) }}"
                            title="Редактирование "><i class="ml-3 fa far fa-edit text-primary"></i>
                            Редакт.
                        </a>
                    </div>

                    <div class="float-right mr-1">
                        <a  data-route-destroy = "{{route('admin.catalog.products.destroy', @$item->id) }}"
                            data-form-csrf =  "{{ csrf_token() }}"
                            data-name =  "{{ @$item->name }}"
                            title="Удалить товар " class="modal-product-delete">
                            <i class="ml-1 feather icon-trash"></i>
                            Удалить
                        </a>
                    </div>
                </div>
                @endif
                <div class="item-options text-center">
                    <div class="item-wrapper">
                        <div class="item-cost">
                            <h4 class="item-price">{{ number_format(round($item->price), 0, '.', ' ')  }} руб.</h4>
                        </div>
                    </div>
                    <a href="javascript:void(0)" class="btn btn-primary btn-cart waves-effect waves-float waves-light">
                        <i class="feather icon-shopping-cart"></i>
                        <span class="add-to-cart">В записку</span>
                    </a>
                </div>
            </div>

            @endforeach

        </section>

        <section id="ecommerce-pagination">
            <div class="row">
                <div class="col-sm-12">
                    <nav aria-label="Product pagination">
                        <ul class="pagination justify-content-center mt-2">
                        {{ $products->onEachSide(12)->links() }}
                        </ul>
                    </nav>
                </div>
            </div>
        </section>
    </section>
    </div>

@endsection

@section('scripts')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.4/nouislider.css" integrity="sha512-GfBcf4DnNHwnnhpzrx2q6r+5WT8ZaAHjv6VjAdUnyo/3+pNljzp78wKwgzOE5+T+wkXcD/AtneDBbIUCuZazyg==" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.4/nouislider.min.js" integrity="sha512-0Z2o7qmtl7ixxWcEQxxTCT8mX4PsdffSGoVJ7A80zqt6DvdEHF800xrsSmKPkSoUaHtlIhkLAhCPb/tkf78SCA==" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="/assets/css/shop.css">
    <style>
        #dataTables_filter {
            margin-top: -20px !important;
        }
        .dataTables_filter input{background-color: #ededed; margin-left: 10px; padding: 4px; border-top: 0; border-left: 0; border-right:0;  border-bottom: 1px; margin-top: -4px; margin-bottom: 20px; }
    </style>




    <script>
        var slider = document.getElementById('price-slider');

        @if(!empty($filters) && isset($filters['price-from']) && isset($filters['price-to']))
            var price = [{{ (int) $filters['price-from'] }}, {{ (int) $filters['price-to'] }}]
        @else
            var price =[0, 10000]
        @endif

        noUiSlider.create(slider, {
            start: price,
            connect: true,
            tooltips: true,
            snap: true,
            range: {
                'min': 0,
                '10%': 1000,
                '20%': 2000,
                '30%': 3000,
                '40%': 4000,
                '50%': 5000,
                '60%': 6000,
                '70%': 7000,
                '80%': 8000,
                '90%': 9000,
                'max': 10000
            }
        });

        slider.noUiSlider.on('update', function (values, handle) {
            $('#price-from').val(values[0]);
            $('#price-to').val(values[1]);
        });


        $(function () {

            $(document).on('click','.modal-product-delete',function (e) {
                let url = $(this).data('route-destroy');
                let name = $(this).data('name');
                let token = $(this).data('form-csrf');

                swal.fire({
                    text: 'Вы действительно хотите удалить товар ' + name + '?',
                    showCancelButton: true,
                    confirmButtonColor: '#c7131c',
                    cancelButtonColor: '#b4b4b4',
                    cancelButtonText: 'Отмена',
                    confirmButtonText: 'Удалить'
                }).then(function (result) {
                    if (result.value===true) {
                        let form = $('<form action="' + url + '" method="post"><input type="hidden" name="_token" value="' + token + '"> "</form>');
                        $('body').append(form);
                        form.submit();
                    }
                });
            });


            $(document).on('click','.product_to_note__',function (e) {
                e.preventDefault();
                console.log($(this).data('product-id'));
                var prod_id = $(this).data('product-id');

                Swal.fire({
                    title: 'Добавить в записку?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#63d667',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Добавить'
                }).then(function () {

                        $.ajax({
                            type: "POST",
                            url: "{{ route('notes.add') }}/" + prod_id,
                            data: {'prod_id': prod_id},
                            cache: false,
                            success: function (response) {
                                swal(
                                    "Успешно!",
                                    "Товар добавлен в записку",
                                    "success"
                                )
                            },
                            failure: function (response) {
                                swal(
                                    "Ошибка!",
                                    "",
                                    "error"
                                )
                            }
                        });
                    },
                    function (dismiss) {
                        if (dismiss === "cancel") {
                            swal(
                                "Отмена",
                                "Отмена добавления в записку стилиста товара",
                                "error"
                            )
                        }
                    });
            });


            $( document ).ajaxStart( function() {
                $('.body-block-loaders').removeClass('d-none');
            } ).ajaxStop ( function(){
                $('.body-block-loaders').addClass('d-none');
            });


        });
    </script>
@endsection
