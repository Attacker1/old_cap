{{--  Сопроводительное письмо + управление --}}

<form name="form" method="post" action="{{ route('notes.save',$data->id) }}">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-8 form-group">
            <h5>Основной текст</h5>
            <textarea id="content" name="content" class="form-control" type="text" rows="18" placeholder="Проверьте перед печатью, что текст поместился на странице">
        {!! $data->content !!}
    </textarea>
        </div>
        <div class="clearfix border-bottom form-group"></div>
        <div class="col-sm-8 form-group">
            <h5>Текст к картинкам из интернет</h5>
            <textarea id="content_advice" name="content_advice" class="form-control" placeholder="Проверьте перед печатью, что текст поместился на странице" type="text" rows="19">
        {!! $data->content_advice !!}
    </textarea>
        </div>

        <div class="col-sm-12 form-group p-1">
            <button class="btn btn-outline-primary save-product-advice_"><i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить письма</button>
        </div>
    </div>
</form>

<style>
    .tox .tox-edit-area__iframe {
        background-color: #eee;
    }
</style>
{{-- END Сопроводительное письмо + управление  --}}

