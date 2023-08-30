<div style="flex-basis: 50%; margin-right: 20px">
    <div class="anketa-head" style="background-color:#e7ebec; border: 1px solid #d2d2d2;">СТИЛЬ</div>
    <div class="question-answer-wrapper" style="border: 1px solid #d2d2d2;">
        @include('admin.anketa.show.item', ['label'=>'Стиль в выходные/', 'question' =>$anketa['styleOnWeekend']])
        @include('admin.anketa.show.item', ['label'=>'в работе', 'question' =>$anketa['styleOnWork'], 'class' => 'top-0'])
        @include('admin.anketa.show.item', ['label'=>'Цветовая гамма', 'question' =>$anketa['choosingPalletes25']])
        @include('admin.anketa.show.item', ['label'=>'Стиль джинс/', 'question' =>$anketa['modelsJeans']])
        @include('admin.anketa.show.item', ['label'=>'посадка/', 'question' =>$anketa['trousersFit'], 'class' => 'top-0'])
        @include('admin.anketa.show.item', ['label'=>'длина', 'question' =>$anketa['trouserslength'], 'class' => 'top-0'])
        @include('admin.anketa.show.item', ['label'=>'Юбки, платья', 'question' =>$anketa['dressesType']])
        @include('admin.anketa.show.item', ['label'=>'Бижутерия', 'question' =>$anketa['earsPierced']])
        @include('admin.anketa.show.item', ['label'=>'', 'question' =>$anketa['bijouterie'], 'class' => 'top-0'])
        @include('admin.anketa.show.item', ['label'=>'', 'question' =>$anketa['jewelry'], 'class' => 'top-0'])
        @include('admin.anketa.show.item', ['label'=>'Магазины, где обычно покупает', 'question' =>$anketa['whereUsuallyBuyClothes']])
    </div><!--question-answer-wrapper-->
</div>
