<template>
    <Collapse>
        <CollapseItem>
            <template slot='title'><span class="collapse-title-text">Одежда</span></template>
            <Tree :option="paramClothes"
                  v-model="valueClothes"
                  @open="openClothes"
                  toggleOnSelect
                  ref="treeClothes"
            >
            </Tree>
        </CollapseItem>
    </Collapse>
</template>

<script lang="ts">
    import {Component, Prop, Vue} from 'vue-property-decorator'

    @Component
    export default class LMenu extends Vue {

        $refs!: {
            treeClothes: HTMLFormElement
        }

        data() {
            return {
                valueClothes: null,
                value: 1,
                paramClothes: {
                    keyName: 'id',
                    parentName: 'parent',
                    titleName: 'title',
                    dataMode: 'list',
                    datas: [
                        { id: 1, title: 'Нижняя одежда', disabled: true},
                        { id: 2, title: 'Платья/ костюмы/ комбинезоны', disabled: true},
                        { id: 3, title: 'Топы нижний слой', disabled: true},
                        { id: 4, title: 'Топы верхний слой', disabled: true},
                        { id: 5, title: 'Аксессуары', disabled: true},
                        { id: 6, title: 'Брюки', disabled: true, parent: '1'},
                        { id: 7, title: 'Джинсы', parent: '1'},
                        { id: 8, title: 'Шорты', disabled: true, parent: '1' },
                        { id: 9, title: 'Юбки', disabled: true, parent: '1' },
                        { id: 10, title: 'Платья', disabled: true, parent: '2' },
                        { id: 67, title: 'Костюмы', disabled: true, parent: '2' },
                        { id: 11, title: 'Комбинезоны', disabled: true, parent: '2' },
                        { id: 12, title: 'Рубашки', disabled: true, parent: '3' },
                        { id: 13, title: 'Футболки', disabled: true, parent: '3' },
                        { id: 14, title: 'Водолазки', parent: '3' },
                        { id: 15, title: 'Боди', disabled: true, parent: '3' },
                        { id: 16, title: 'Топы с длинными рукавами', parent: '3' },
                        { id: 17, title: 'Топы с короткими рукавами', parent: '3' },
                        { id: 18, title: 'Жакеты', parent: '4' },
                        { id: 19, title: 'Трикотаж', disabled: true, parent: '4' },
                        { id: 20, title: 'Толстовки', parent: '4' },
                        { id: 21, title: 'Верхняя летняя одежда', parent: '4' },
                        { id: 22, title: 'Галантерея', disabled: true, parent: '5' },
                        { id: 23, title: 'Бижутерия', disabled: true, parent: '5' },
                        { id: 24, title: 'Сумки', disabled: true, parent: '5' },
                        { id: 25, title: 'брюки длинные', parent: '6' },
                        { id: 26, title: 'брюки укороченные', parent: '6' },
                        { id: 27, title: 'брюки по щиколотку', parent: '6' },
                        { id: 28, title: 'джоггеры', parent: '6' },
                        { id: 29, title: 'леггинсы', parent: '6' },
                        { id: 30, title: 'мини шорты', parent: '8' },
                        { id: 31, title: 'шорты до середины бедра', parent: '8' },
                        { id: 32, title: 'шорты до колена', parent: '8' },
                        { id: 33, title: 'мини юбки', parent: '9' },
                        { id: 34, title: 'миди юбки', parent: '9' },
                        { id: 35, title: 'макси юбки', parent: '9' },
                        { id: 36, title: 'мини', parent: '10' },
                        { id: 37, title: 'миди', parent: '10' },
                        { id: 38, title: 'макси', parent: '10' },
                        { id: 68, title: 'мини', parent: '67' },
                        { id: 69, title: 'миди', parent: '67' },
                        { id: 70, title: 'макси', parent: '67' },
                        { id: 39, title: 'мини', parent: '11' },
                        { id: 40, title: 'миди', parent: '11' },
                        { id: 41, title: 'макси', parent: '11' },
                        { id: 42, title: 'рубашки', parent: '12' },
                        { id: 43, title: 'блузы', parent: '12' },
                        { id: 44, title: 'футболки', parent: '13' },
                        { id: 45, title: 'майки', parent: '13' },
                        { id: 46, title: 'лонгсливы', parent: '13' },
                        { id: 47, title: 'без рукавов', parent: '15' },
                        { id: 48, title: 'с коротким рукавом', parent: '15' },
                        { id: 49, title: 'с длинным рукавом', parent: '15' },
                        { id: 50, title: 'пуловеры', parent: '19' },
                        { id: 51, title: 'кардиганы', parent: '19' },
                        { id: 52, title: 'свитеры', parent: '19' },
                        { id: 53, title: 'жилеты', parent: '19' },
                        { id: 54, title: 'ремни', parent: '22' },
                        { id: 55, title: 'шапки', parent: '22' },
                        { id: 56, title: 'шарфы', parent: '22' },
                        { id: 57, title: 'платок', parent: '22' },
                        { id: 58, title: 'палантины', parent: '22' },
                        { id: 59, title: 'украшения для волос', parent: '23' },
                        { id: 60, title: 'кольца', parent: '23' },
                        { id: 61, title: 'браслеты', parent: '23' },
                        { id: 62, title: 'колье', parent: '23' },
                        { id: 63, title: 'серьги', parent: '23' },
                        { id: 64, title: 'экокожа', parent: '24' },
                        { id: 65, title: 'кожа', parent: '24' },
                        { id: 66, title: 'текстильные и плетенные', parent: '24' }
                    ]
                },
                prices: {
                  start: 0,
                  end: 10000
                }
            }
        }

        openClothes(data) {

            //чтобы не было открыто несколько одновременно
            if(typeof(data.parent) == "undefined") {

                let status = this.$refs.treeClothes.treeObj[data.id].status.opened;
                this.$refs.treeClothes.foldAll
                if (status) {
                    this.$refs.treeClothes.expand([data.id])
                }
            }
        }
    }
</script>

