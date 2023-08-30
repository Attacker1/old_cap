<template>
    <Collapse>
        <CollapseItem ref="collapseBrands">
            <template slot='title'>
                <span class="collapse-title-text">Бренды</span>
            </template>
            <Tree :option="paramBrands"
                  v-model="valueBrands"
                  toggleOnSelect
                  :multiple="true"
                  :filterable="true"
                  ref="treeBrands"
            >
            </Tree>
            <div class="stock-link" v-on:click="closeCollapseBrand(); scrollIntoView();">свернуть</div>
        </CollapseItem>
    </Collapse>
</template>

<script lang="ts">
    import {Component, Vue} from 'vue-property-decorator'

    @Component

    export default class LMenu extends Vue {

        $refs!: {
            collapseBrands: HTMLFormElement
        }
        $ScrollIntoView: any

        data() {
            return {
                valueBrands: null,
                paramBrands: {
                    keyName: 'id',
                    parentName: 'parent',
                    titleName: 'title',
                    dataMode: 'list',
                    datas: [
                        { id: 1, title: '2littlegirls'},
                        { id: 2, title: '43878'},
                        { id: 3, title: 'AFINA'},
                        { id: 4, title: 'AIKA'},
                        { id: 5, title: 'Aim Clothing'},
                        { id: 6, title: 'Akhmadullina Dreams'},
                        { id: 7, title: 'Anna Kitch'},
                        { id: 8, title: 'Antiga'},
                        { id: 9, title: 'April Is Mine'},
                        { id: 10, title: 'Argent'},
                        { id: 11, title: 'Arny Praht'},
                        { id: 12, title: 'ASMI'},
                        { id: 13, title: 'Bawer'},
                        { id: 14, title: 'Bazalini'},
                        { id: 15, title: 'Beauty Style'},
                        { id: 16, title: 'Befree'},
                        { id: 17, title: 'Bellart'},
                        { id: 18, title: 'Bjanka'},
                        { id: 19, title: 'BURLO'},
                        { id: 20, title: 'Calista'},
                        { id: 21, title: 'Capsula'},
                        { id: 22, title: 'Care of You'},
                        { id: 23, title: 'Charuel'},
                        { id: 24, title: 'CHIC'},
                        { id: 25, title: 'Concept Club'},
                        { id: 26, title: 'Daisyknit'},
                        { id: 27, title: 'Diva'},
                        { id: 28, title: 'Divalli'},
                        { id: 29, title: 'Dorothy Perkins'},
                        { id: 30, title: 'Elardis'},
                        { id: 31, title: 'Elis'},
                        { id: 32, title: 'Elisabetta Franchi'},
                        { id: 33, title: 'emkafashion'},
                        { id: 34, title: 'Eniland'},
                        { id: 35, title: 'Exclaim'},
                        { id: 36, title: 'Fashion Wear'},
                        { id: 37, title: 'Fly'},
                        { id: 38, title: 'Forte'},
                        { id: 39, title: 'Gap'},
                        { id: 40, title: 'Hass'},
                        { id: 41, title: 'Hass Fashion'},
                        { id: 42, title: 'HYR'},
                        { id: 43, title: 'I.P.A.'},
                        { id: 44, title: 'Imocean'},
                        { id: 45, title: 'Imperial'},
                        { id: 46, title: 'Inspire Girls'},
                        { id: 47, title: 'Jacqueline de Yong'},
                        { id: 48, title: 'Jacqueline de Young'},
                        { id: 49, title: 'Janes\'s Story'},
                        { id: 50, title: 'Jlab'},
                        { id: 51, title: 'JUVELARTO'},
                        { id: 52, title: 'KAPSULA'},
                        { id: 53, title: 'Kemo Bis'},
                        { id: 54, title: 'KITTIN'},
                        { id: 55, title: 'Kivi'},
                        { id: 56, title: 'Ksi Ksi'},
                        { id: 57, title: 'La Trevi'},
                        { id: 58, title: 'Le Tresor'},
                        { id: 59, title: 'Levi\'s'},
                        { id: 60, title: 'Levi\'s®'},
                        { id: 61, title: 'Lichi'},
                        { id: 62, title: 'Lime'},
                        { id: 63, title: 'Lita Felichi'},
                        { id: 64, title: 'lombee'},
                        { id: 65, title: 'Lorani'},
                        { id: 66, title: 'Love Republic'},
                        { id: 67, title: 'Macabu'},
                        { id: 68, title: 'Mango'},
                        { id: 69, title: 'Marianna Jewelry'},
                        { id: 70, title: 'Meet me'},
                        { id: 71, title: 'Mila brand'},
                        { id: 72, title: 'Mira Fedotova'},
                        { id: 73, title: 'Mollis'},
                        { id: 74, title: 'Mosko jeans'},
                        { id: 75, title: 'Murmurizm'},
                        { id: 76, title: 'MyFlaffico'},
                        { id: 77, title: 'Naya'},
                        { id: 78, title: 'Nero Lab'},
                        { id: 79, title: 'Nice Studio'},
                        { id: 80, title: 'No Rules Brand'},
                        { id: 81, title: 'O.TAFINTSEVA'},
                        { id: 82, title: 'OVS'},
                        { id: 83, title: 'Oysho'},
                        { id: 84, title: 'Pelloro'},
                        { id: 85, title: 'Peony Moscow'},
                        { id: 86, title: 'po Pogode'},
                        { id: 87, title: 'PUMUSHE'},
                        { id: 88, title: 'Rodier'},
                        { id: 89, title: 'Sandro'},
                        { id: 90, title: 'Savage'},
                        { id: 91, title: 'Sela'},
                        { id: 92, title: 'SELFMADE'},
                        { id: 93, title: 'Semiotique'},
                        { id: 94, title: 'Seven Senses'},
                        { id: 95, title: 'SIMPCLO'},
                        { id: 96, title: 'Stradivarius'},
                        { id: 97, title: 'Stylish'},
                        { id: 98, title: 'SWEEWE'},
                        { id: 99, title: 'TEST'},
                        { id: 100, title: 'Tezenis'},
                        { id: 101, title: 'The One'},
                        { id: 102, title: 'The One by Svetlana Ermak'},
                        { id: 103, title: 'The Select'},
                        { id: 104, title: 'tokmak'},
                        { id: 105, title: 'Tom Farr'},
                        { id: 106, title: 'toppies'},
                        { id: 107, title: 'Topshop'},
                        { id: 108, title: 'TOPTOP'},
                        { id: 109, title: 'Trends Brands'},
                        { id: 110, title: 'Ubrand'},
                        { id: 111, title: 'United Colors of Beneton'},
                        { id: 112, title: 'Urban Tiger'},
                        { id: 113, title: 'Varvarka Petite'},
                        { id: 114, title: 'VIAMORE'},
                        { id: 115, title: 'Viaville'},
                        { id: 116, title: 'Victoria Kuksina'},
                        { id: 117, title: 'Whitney'},
                        { id: 118, title: 'WO.T.WO.Y'},
                        { id: 119, title: 'YANSOO'},
                        { id: 120, title: 'Yansstudio'},
                        { id: 121, title: 'YOU'},
                        { id: 122, title: 'YOUNGSOON'},
                        { id: 123, title: 'Zara'},
                        { id: 124, title: 'Zarina'},
                        { id: 125, title: 'ZMRA'},
                        { id: 126, title: 'Сахарок'}

                    ]
                }
            }
        }

        closeCollapseBrand(data) {
            this.$refs.collapseBrands.isActive = false;
        }

        scrollIntoView() {
            this.$ScrollIntoView(this.$el.querySelector('.collapse-title-text'), {
                time: 500,
                align: {
                }
            });
        }
    }
</script>

