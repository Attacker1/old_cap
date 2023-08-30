<template>

    <Collapse>
        <CollapseItem>
            <template slot='title'><span class="collapse-title-text">Цвет</span></template>
            <Tree :option="paramColors"
                  v-model="valueColors"
                  toggleOnSelect
                  :multiple="true"
                  :filterable="true"
                  ref="treeColors"
            >
                <template slot="item" slot-scope="{item}">
                    <span v-if="item.hex!=''" v-bind:style="{ backgroundColor: '#' + item.hex }" class="item-color"></span>
                </template>
            </Tree>
        </CollapseItem>
    </Collapse>

</template>

<script lang="ts">
    import {Component, Vue} from 'vue-property-decorator'

    @Component
    export default class LMenu extends Vue {

        $refs!: {
            treeColors: HTMLFormElement
        }

        data() {
            return {

                valueColors: null,
                paramColors: {
                    keyName: 'id',
                    parentName: 'parent',
                    titleName: 'title',
                    dataMode: 'list',
                    datas: [
                        { id: 1, title: 'Бежевый', hex:'f5f5dc'},
                        { id: 2, title: 'Белый', hex:'ffffff'},
                        { id: 3, title: 'Бордовый', hex:'9b2d30'},
                        { id: 4, title: 'Голубой', hex:'42aaff'},
                        { id: 5, title: 'Желтый', hex:'ffff00'},
                        { id: 6, title: 'Зеленый', hex:'008000'},
                        { id: 7, title: 'Золотой', hex:'ffd700'},
                        { id: 8, title: 'Капучино', hex:'d0aa9b'},
                        { id: 9, title: 'Коричневый', hex:'964b00'},
                        { id: 10, title: 'Красный', hex:'ff0000'},
                        { id: 11, title: 'Оранжевый', hex:'ffa500'},
                        { id: 12, title: 'Розовый', hex:'ffc0cb'},
                        { id: 13, title: 'Серебряный', hex:'c0c0c0'},
                        { id: 14, title: 'Серо-голубой', hex:'77a1b5'},
                        { id: 15, title: 'Серый', hex:'808080'},
                        { id: 16, title: 'Синий', hex:'0000ff'},
                        { id: 17, title: 'Сиреневый', hex:'c8a2c8'},
                        { id: 18, title: 'Фиолетовый', hex:'8b00ff'},
                        { id: 19, title: 'Фуксия', hex:'f754e1'},
                        { id: 20, title: 'Хаки', hex:'806b2a'},
                        { id: 21, title: 'Мультиколор', hex:''},
                        { id: 22, title: 'Черный', hex:'000000'}
                    ]
                }
            }
        }
    }
</script>

