<div class="tab-pane" id="kt_tab_pane_4" role="tabpanel">

    <div class="row" id="reference-items">
{{--        @include('partials.admin.anketa.tabs.reference',['tab_reference' => $tab_reference])--}}
        {!! @$tab_reference !!}
    </div><!--row-->


    @if(auth()->guard('admin')->user()->hasPermission('anketa-reference'))

        <div class="row mb-10">
            <div class="col">
                <hr>
            </div>
            <div class="col-auto">Добавить референс</div>
            <div class="col">
                <hr>
            </div>
        </div>

        <form action="javascript:" id="tabs-reference" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col ">
                    <input type="hidden" name="client_uuid" value="{{@$client->uuid}}">
                    <input type="hidden" name="anketa_uuid" value="{{$uuid}}">
                    <textarea id="kt_maxlength_letter_1" maxlength="4000" name="comment" class="form-control bg-light"
                              type="text" rows="13" placeholder="Текст референса"></textarea>
                </div>

                <div class="col ">
                    <div class="file-upload-wrapper mb-7">
                        <input type="file" name="photo" id="reference-photo" class="file-upload"/>
                    </div>
                    <img id="image_preview_container" src="/app-assets/images/anketa/noscreen.png" alt="preview image"
                         style="max-height: 150px;">
                    <div class="input-group mt-7">
                        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Сохранить</button>
                    </div>

                </div>

            </div>
        </form>
    @endif

</div><!--tab-pane-->
