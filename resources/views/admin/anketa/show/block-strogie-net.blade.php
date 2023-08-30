<div style="flex-basis: 50%; margin-right: 20px">
    <div class="anketa-head" style="background-color:#f7d9e0; border: 1.5px solid #e43131;">ЧТО КЛИЕНТ ТОЧНО НЕ ХОЧЕТ ВИДЕТЬ В КАПСУЛЕ</div>
    <div class="question-answer-wrapper" style="border: 1px solid #ccc9c9;">
        @include('admin.anketa.show.item', ['label'=>'Цвета', 'question' =>$anketa['noColor']])
        @include('admin.anketa.show.item', ['label'=>'Принты', 'question' =>$anketa['printsDislike'], 'prints' => true])
        @include('admin.anketa.show.item', ['label'=>'Вещи в капсуле', 'question' =>$anketa['capsulaNotFirstOfAll']])
        @include('admin.anketa.show.item', ['label'=>'Аксессуары', 'question' =>$anketa['capsulaNotWantAccessories']])
        @include('admin.anketa.show.item', ['label'=>'Ткани', 'question' =>$anketa['fabricsShouldAvoid']])
        @include('admin.anketa.show.item', ['label'=>'Скрыть', 'question' =>$anketa['bodyPartsToHide']])
    </div><!--question-answer-wrapper-->
</div>