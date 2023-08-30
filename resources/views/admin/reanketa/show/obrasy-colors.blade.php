<div class="card card-custom col-lg-12 mb-6">
    <div class="card-header card-header-tabs-line">
        <div class="card-title ">Образы</div>
        <div class="card-toolbar">
            <ul class="nav nav-tabs nav-bold nav-tabs-line">

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_1">Образы</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_2">Цвета и принты</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_3">Фото</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_4">Референсы</a>
                </li>

            </ul>
        </div>
    </div>
    <div class="card-body col-12 pt-4">
        <div class="tab-content">
            @include('admin.reanketa.show.obrasy')
            @if(!empty($arr_colors)) @include('admin.reanketa.show.colors') @endif
            @include('admin.reanketa.show.foto')
            {{--@include('admin.reanketa.show.references')--}}
        </div>
    </div>

</div>

@section('scripts')
    <script>
        $('.modal-show-foto').on('click', function () {
            swal.fire({
                imageUrl: $(this).data('url'),
                showCloseButton: true
            });
        });

        $('[href="#kt_tab_pane_1"]').on('click', function () {
            $('.card-header-tabs-line').find('.card-title').html('Образы');
        });
        $('[href="#kt_tab_pane_2"]').on('click', function () {
            $('.card-header-tabs-line').find('.card-title').html('Цвета');
        });
        $('[href="#kt_tab_pane_3"]').on('click', function () {
            $('.card-header-tabs-line').find('.card-title').html('Фото');
        });
        $('[href="#kt_tab_pane_4"]').on('click', function () {
            $('.card-header-tabs-line').find('.card-title').html('Референсы');
        });


    </script>

@endsection

