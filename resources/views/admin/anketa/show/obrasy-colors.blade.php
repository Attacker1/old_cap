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
            @include('admin.anketa.show.obrasy')
            @include('admin.anketa.show.colors')
            @include('admin.anketa.show.foto')
            @include('admin.anketa.show.references')
        </div>
    </div>

</div>
