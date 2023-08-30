<template>

    <Collapse>
        <CollapseItem>
            <template slot='title'><span class="collapse-title-text">Размер</span></template>
            <Tree :option="paramSizes"
                  v-model="valueSizes"
                  toggleOnSelect
                  :multiple="true"
                  :filterable="true"
                  ref="treeSizes"
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

                valueSizes: null,
                paramSizes: {
                    keyName: 'id',
                    parentName: 'parent',
                    titleName: 'title',
                    dataMode: 'list',
                    datas: [
                        { id: 1, title: '36'},
                        { id: 2, title: '37'},
                        { id: 3, title: '38'},
                        { id: 4, title: '39'},
                        { id: 5, title: '41'},
                        { id: 6, title: '42'},
                        { id: 7, title: '43'},
                        { id: 8, title: '44'},
                        { id: 9, title: '45'},
                        { id: 10, title: '46'}
                    ]
                }
            }
        }
    }
</script>

