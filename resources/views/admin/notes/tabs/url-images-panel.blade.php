
<div class="customizer invisible d-none d-md-block"><a class="customizer-toggle d-flex align-items-center justify-content-center" href="javascript:void(0);">
        <i class="fa fa-image"></i></a><div class="customizer-content ps">
        <!-- Customizer header -->
        <div class="customizer-header px-2 pt-1 pb-0 position-relative">
            <h4 class="mb-0">Фото из интернета</h4>
        </div>
        <hr>
        <!-- Styling & Text Direction -->
        <div class="customizer-styling-direction px-2">
            <div class="d-flex">
                <div class="input-group mb-1 col-sm-12">
                    <form name="form" method="post" action="{{ route('notes.attach',$data->id) }}">
                        {{ csrf_field() }}
                        <div class="input-group ">
                            <input type="text" name="img_url" class="form-control" placeholder="Фото из интернет" aria-label="img_url_label" autocomplete="off">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-sm btn-outline-primary waves-effect" >Загрузить</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <hr>

{{--        <div class="customizer-styling-direction px-2">--}}
{{--            <div class="d-flex">--}}
{{--                <div class="input-group mb-1 col-sm-12">--}}
{{--                    <button class="btn btn-outline-primary m-1 save-url-advice_"><i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить сочетания</button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

    </div>

</div>