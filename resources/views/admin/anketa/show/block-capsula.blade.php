<div style="flex-basis: 50%; margin-right: 20px">
    <div class="anketa-head" style="background-color:#e7ebec; border: 1px solid #d2d2d2;">КАПСУЛА</div>
    <div class="question-answer-wrapper" style="border: 1px solid #ccc9c9;">

        @include('admin.anketa.show.item', ['label'=>'Цель подборки', 'question' => $anketa['whatPurpose']])
        @include('admin.anketa.show.item', ['label'=>'Сезон', 'question' => $anketa['whatSeason']])
        @include('admin.anketa.show.item', ['label'=>'Как действуем', 'question' =>$anketa['tryOtherOrSaveStyle']])
        @include('admin.anketa.show.item', ['label'=>'Вещи в капсуле', 'question' =>$anketa['capsulaFirstOfAll']])
        @include('admin.anketa.show.item', ['label'=>'Комментарий', 'question' =>$anketa['additionalNuances']])
        @include('admin.anketa.show.item', ['label'=>'', 'question' =>$anketa['prices']])
    </div>
</div>
