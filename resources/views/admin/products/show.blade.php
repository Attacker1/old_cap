@extends('admin.main')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ url()->previous() }}"><button class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Закрыть</button></a>
    </div>
@endsection

@section('content')

    <div class="card-body">
        <div class="row my-2">
            <div class="col-12 col-md-5 d-flex align-items-center justify-content-center mb-2 mb-md-0">
                <div class="row">
                <div class="col-sm-9 d-flex align-items-center justify-content-center">
                    <img src="{{ $files->where('main',1)->first()->url}} }}" class="img-fluid product-img" alt="product image" width="400">
                </div>

{{--                    Здесь будут миниатюры доп фото--}}

{{--                    <div class="col-sm-3 imgSmall" data-ui="gallery">--}}
{{--                        <div data-ui="image-container-item" data-color="1000N">--}}

{{--                            <a href="#" data-img="https://cdn-cdnv.oodji.com/upload/iblock/30f/4d1209725bcc11e780db1c98ec18de6b_0134d37dde3911e780e01c98ec18de6b.jpg/resize/433x2000/?_cvc=1616737531" data-img_b="https://cdn-cdnv.oodji.com/upload/iblock/30f/4d1209725bcc11e780db1c98ec18de6b_0134d37dde3911e780e01c98ec18de6b.jpg?_cvc=1616737531" data-alt="Рубашка с нагрудным карманом и рукавом 3/4 oodji #SECTION_NAME# (белый), 13K01005B/42083/1000N - вид 4">                                                                                        <picture>--}}
{{--                                    <source srcset="https://cdn-cdnv.oodji.com/upload_webp/iblock/30f/4d1209725bcc11e780db1c98ec18de6b_0134d37dde3911e780e01c98ec18de6b.jpg/resize/95x150/?_cvc=1616737531" type="image/webp">--}}
{{--                                    <img loading="lazy" src="https://cdn-cdnv.oodji.com/upload_jpeg/iblock/30f/4d1209725bcc11e780db1c98ec18de6b_0134d37dde3911e780e01c98ec18de6b.jpg/resize/95x150/?_cvc=1616737531" alt="Рубашка с нагрудным карманом и рукавом 3/4 oodji #SECTION_NAME# (белый), 13K01005B/42083/1000N - вид 4" title="Рубашка с нагрудным карманом и рукавом 3/4 oodji #SECTION_NAME# (белый)">--}}
{{--                                </picture>--}}
{{--                            </a>--}}
{{--                            <a href="#" data-img="https://cdn-cdnv.oodji.com/upload/iblock/ff0/4d1209725bcc11e780db1c98ec18de6b_0134d37cde3911e780e01c98ec18de6b.jpg/resize/433x2000/?_cvc=1616737531" data-img_b="https://cdn-cdnv.oodji.com/upload/iblock/ff0/4d1209725bcc11e780db1c98ec18de6b_0134d37cde3911e780e01c98ec18de6b.jpg?_cvc=1616737531" data-alt="Рубашка с нагрудным карманом и рукавом 3/4 oodji #SECTION_NAME# (белый), 13K01005B/42083/1000N - вид 5">                                                                                        <picture>--}}
{{--                                    <source srcset="https://cdn-cdnv.oodji.com/upload_webp/iblock/ff0/4d1209725bcc11e780db1c98ec18de6b_0134d37cde3911e780e01c98ec18de6b.jpg/resize/95x150/?_cvc=1616737531" type="image/webp">--}}
{{--                                    <img loading="lazy" src="https://cdn-cdnv.oodji.com/upload_jpeg/iblock/ff0/4d1209725bcc11e780db1c98ec18de6b_0134d37cde3911e780e01c98ec18de6b.jpg/resize/95x150/?_cvc=1616737531" alt="Рубашка с нагрудным карманом и рукавом 3/4 oodji #SECTION_NAME# (белый), 13K01005B/42083/1000N - вид 5" title="Рубашка с нагрудным карманом и рукавом 3/4 oodji #SECTION_NAME# (белый)">--}}
{{--                                </picture>--}}
{{--                            </a>--}}
{{--                            <a href="#" data-img="https://cdn-cdnv.oodji.com/upload/iblock/1f4/4d1209725bcc11e780db1c98ec18de6b_0134d37bde3911e780e01c98ec18de6b.jpg/resize/433x2000/?_cvc=1616737531" data-img_b="https://cdn-cdnv.oodji.com/upload/iblock/1f4/4d1209725bcc11e780db1c98ec18de6b_0134d37bde3911e780e01c98ec18de6b.jpg?_cvc=1616737531" data-alt="Рубашка с нагрудным карманом и рукавом 3/4 oodji #SECTION_NAME# (белый), 13K01005B/42083/1000N - вид 6">                                                                                        <picture>--}}
{{--                                    <source srcset="https://cdn-cdnv.oodji.com/upload_webp/iblock/1f4/4d1209725bcc11e780db1c98ec18de6b_0134d37bde3911e780e01c98ec18de6b.jpg/resize/95x150/?_cvc=1616737531" type="image/webp">--}}
{{--                                    <img loading="lazy" src="https://cdn-cdnv.oodji.com/upload_jpeg/iblock/1f4/4d1209725bcc11e780db1c98ec18de6b_0134d37bde3911e780e01c98ec18de6b.jpg/resize/95x150/?_cvc=1616737531" alt="Рубашка с нагрудным карманом и рукавом 3/4 oodji #SECTION_NAME# (белый), 13K01005B/42083/1000N - вид 6" title="Рубашка с нагрудным карманом и рукавом 3/4 oodji #SECTION_NAME# (белый)">--}}
{{--                                </picture>--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                </div>
            </div>
            <div class="col-12 col-md-7">
                <h4>{{ @$data->name }}</h4>
                <span class="card-text item-company">Бренд: <a href="javascript:void(0)" class="company-name">{{ @$data->brands->name }}</a></span>
                <div class="ecommerce-details-price d-flex flex-wrap mt-1">
                    <h4 class="item-price mr-1">{{ number_format(round($data->price), 0, '.', ' ')  }} руб.</h4>
                    <ul class="unstyled-list list-inline pl-1 border-left">
                        <p class="card-text">Артикул: <span class="text-success">{{ @$data->sku ?? 'Не указан' }}</span></p>
                    </ul>
                </div>
                <hr>
                <h6>Описание:</h6>
                <p class="card-text">
                    {{ @$data->content }}

                </p>
                <hr>
                <div class="product-color-options">
                    <h6>Доступные расцветки:</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="d-inline-block selected">
                            <div class="color-option b-primary">
                                <div class="filloption bg-primary"  ></div>
                            </div>
                        </li>
                        <li class="d-inline-block">
                            <div class="color-option b-success">
                                <div class="filloption bg-success"></div>
                            </div>
                        </li>
                        <li class="d-inline-block">
                            <div class="color-option b-danger">
                                <div class="filloption bg-danger"></div>
                            </div>
                        </li>
                        <li class="d-inline-block">
                            <div class="color-option b-warning">
                                <div class="filloption bg-warning"></div>
                            </div>
                        </li>
                        <li class="d-inline-block">
                            <div class="color-option b-info">
                                <div class="filloption bg-info"></div>
                            </div>
                        </li>
                    </ul>
                </div>
                <hr>
                <div class="product-tags">
                    <h6>Теги:</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="d-inline-block selected">
                            <div class="badge badge-primary">Новинка</div>
                        </li>
                        <li class="d-inline-block">
                            <div class="badge badge-primary">Осень</div>
                        </li>
                        <li class="d-inline-block">
                            <div class="badge badge-primary">Часто покупают</div>
                        </li>
                    </ul>
                </div>
                <hr>
                <div class="d-flex flex-column flex-sm-row pt-1">
                    <a href="javascript:void(0)" class="btn btn-primary btn-cart mr-0 mr-sm-1 mb-1 mb-sm-0 waves-effect waves-float waves-light">
                        <i class="ficon feather icon-shopping-cart"></i><span> В записку</span>
                    </a>
                    <a href="javascript:void(0)" class="btn btn-outline-secondary btn-wishlist mr-0 mr-sm-1 mb-1 mb-sm-0 waves-effect">
                        <i class="ficon feather icon-heart"></i><span> Отложить</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection


