<div class="tab-pane" id="kt_tab_pane_3" role="tabpanel">

    <div class="row">

        @if(!empty($fotos_url))
        @foreach($fotos_url as $foto_url)
            <div class="col-lg-4 col-md-6 col-sm-8 pb-6 mt-3">
                <a href="javascript:" data-id="21"
                   class="anketa-tab-reference-delete fas fa  fa-crop text-danger text-danger"></a>

                <div style="position:relative;" class="m-3 p-3 resizeable" >
                    <a class="modal-show-foto" data-url="{{$foto_url}}" style="cursor:pointer">
                        <img class="card-img img-fluid mb-1" src="{{$foto_url}}">
                    </a>
                </div>
            </div>

        @endforeach
        @endif


    </div><!--row-->
    <div class="row" id="admin-tab-support-photo">
        {!! @$support_photo !!}
    </div>

    @if(auth()->guard('admin')->user()->hasPermission('anketa-tab-photo-add'))

        <div class="row mb-10">
            <div class="col">
                <hr>
            </div>
            <div class="col-auto">Добавить фото</div>
            <div class="col">
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col ">
                <div class="slim" id="anketa_tab_photo" style="max-width: 500px; margin: 0 auto"></div>
            </div>
        </div>

    @endif

</div><!--tab-pane-->
