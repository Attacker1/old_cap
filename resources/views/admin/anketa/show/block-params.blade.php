<div style="flex-basis: 50%;">
    <div class="anketa-head" style="background-color:#e7fcf7; border: 1.5px solid #3699ff;">ПАРАМЕТРЫ</div>
    <div class="question-answer-wrapper" style="border: 1px solid #3699ff;">

        @include('admin.anketa.show.item', ['label'=>'Рост/', 'question' =>$anketa['bioHeight'] ?? ''])
        @include('admin.anketa.show.item', ['label'=>'Вес', 'question' =>$anketa['bioWeight'] ?? '', 'class' => 'top-0'])
        @include('admin.anketa.show.item', ['label'=>'Возраст/', 'question' => $anketa['bioBirth'] ?? ''])
        @include('admin.anketa.show.item', ['label'=>'Профессия', 'question' => $anketa['occupation'], 'class' => 'top-0'])
        @include('admin.anketa.show.item', ['label'=>'Цвет волос', 'question' => $anketa['hairColor']])
        @include('admin.anketa.show.item', ['label'=>'Верх', 'question' => $anketa['topSizeStyle']])
        @include('admin.anketa.show.item', ['label'=>'Низ', 'question' => $anketa['bottomSizeStyle']])
        @include('admin.anketa.show.item', ['label'=>'Замеры', 'question' => $anketa['chestWeistHips']])
        @include('admin.anketa.show.item', ['label'=>'(ОГ/ОТ/ОБ)', 'question' => null, 'class' => 'top-0'])
        @include('admin.anketa.show.item', ['label'=>'Тип фигуры', 'question' => $anketa['figura']])
        <?php
            $fotos_html = '';
            if (!empty($fotos_url) && is_array($fotos_url))
            for($i = 0; $i < count($fotos_url); ) {
                $fotos_html.='<a class="modal-show-foto" data-url="'.$fotos_url[$i].'" style= "cursor:pointer">фото '.++$i.'</a> ';
            }
        ?>
        @include('admin.anketa.show.item', ['label'=>'Фото', 'question' => $fotos_html])
        @include('admin.anketa.show.item', ['label'=>'(ссылки)', 'question' => null, 'class' => 'top-0'])
    </div>
</div>
