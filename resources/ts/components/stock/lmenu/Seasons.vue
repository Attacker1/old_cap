<template>
    <Collapse>
        <CollapseItem>
            <template slot='title'><span class="collapse-title-text">Сезон</span></template>
            <Tree :option="paramSeasons"
                  v-model="valueSeasons"
                  toggleOnSelect
                  :multiple="true"
                  :filterable="true"
                  ref="treeSeasons"
            >
            </Tree>
        </CollapseItem>
    </Collapse>
</template>

<script lang="ts">
    import {Component, Vue} from 'vue-property-decorator'

    @Component

    export default class LMenu extends Vue {

        data() {
            return {

                valueSeasons: null,
                paramSeasons: {
                    keyName: 'id',
                    parentName: 'parent',
                    titleName: 'title',
                    dataMode: 'list',
                    datas: [
                        { id: 1, title: 'На любой сезон'},
                        { id: 2, title: 'Зима'},
                        { id: 3, title: 'Демисезон'},
                        { id: 4, title: 'Лето'},
                        { id: 5, title: 'Осень'},
                    ]
                },
            }
        }
    }
</script>

