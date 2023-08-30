<template>
    <div class="answers"><label class="checkbox-input"><span>На каждый день <br>(встречи с друзьями, прогулка по городу)</span>
        <input type="checkbox" value="0"></label><label class="checkbox-input"><span>На работу и бизнес-встречи</span>
        <input type="checkbox" value="1"></label><label class="checkbox-input"><span>Для прогулок с детьми, семейные вечера</span>
        <input type="checkbox" value="2"></label><label class="checkbox-input"><span>Домашняя одежда <br>(и для работы на удаленке, и до магазина дойти)</span>
        <input type="checkbox" value="3"></label><label class="checkbox-input"><span>Вечерние образы <br>(дни рождения, свадьбы, корпоративы)</span>
        <input type="checkbox" value="4"></label><label class="checkbox-input"><span>Для путешествий, отпуска <br>(море, горы, и т.д)</span>
        <input type="checkbox" value="5"></label><label class="checkbox-input"><span>Разнообразить гардероб <br>(сделать его ярче, женственнее)</span>
        <input type="checkbox" value="6"></label><label class="checkbox-input"><span>Собрать новый гардероб <br>(кардинальная смена стиля)</span>
        <input type="checkbox" value="7"></label><label class="checkbox-input on"><span>Добавить больше платьев</span>
        <input type="checkbox" value="8"></label><label class="checkbox-input on"><span>Добавить больше верха <br>(футболки, пиджаки, свитшоты)</span>
        <input type="checkbox" value="9"></label><label class="checkbox-input on"><span>Добавить больше низа <br>(юбки, джинсы, шорты)</span>
        <input type="checkbox" value="10"></label><label class="checkbox-input"><span>Другое</span> <input
        type="checkbox" value="11"></label> <textarea placeholder="Другое" class="text-checkbox"></textarea></div>
</template>

<script lang="ts">

import {Component, Vue} from "vue-property-decorator";

@Component({})

export default class CheckboxWidePlus extends Vue {

}
</script>


