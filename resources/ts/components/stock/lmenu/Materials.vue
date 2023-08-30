<template>
    <Collapse>
        <CollapseItem>
            <template slot='title'><span class="collapse-title-text">Материал</span></template>
            <Tree :option="paramMaterials"
                  v-model="valueMaterials"
                  toggleOnSelect
                  :multiple="true"
                  :filterable="true"
                  ref="treeMatrerials"
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
                valueMaterials: null,
                paramMaterials: {
                    keyName: 'id',
                    parentName: 'parent',
                    titleName: 'title',
                    dataMode: 'list',
                    datas: [
                        { id: 1, title: 'Waterproof'},
                        { id: 2, title: 'Абразив'},
                        { id: 3, title: 'Акация коа'},
                        { id: 4, title: 'Акрил'},
                        { id: 5, title: 'Акрилик'},
                        { id: 6, title: 'Акрилловое стекло'},
                        { id: 7, title: 'Акрилфайбер'},
                        { id: 8, title: 'Альпака'},
                        { id: 9, title: 'Ангора'}
                    ]
                }
            }
        }
    }
</script>

