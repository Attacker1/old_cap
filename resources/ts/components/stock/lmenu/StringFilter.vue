<template>
    <Collapse>
        <CollapseItem>
            <template slot='title'><span class="collapse-title-text">{{ attribute.name }}</span></template>
<!--            <Tree :option="params"
                  v-model="valueAttribute"
                  toggleOnSelect
                  :multiple="true"
                  :filterable="true"
                  @choose="choose"
                  ref="treeFilters"
            >
            </Tree>-->
            <p class="checkbox-filter__checkall">
                <Checkbox :checked="valueAttribute.length == params.length" @click.native="checkAll">Выделить все</Checkbox>
            </p>
            <p><Checkbox class="checkbox-filter" v-model="valueAttribute" :datas="params" ></Checkbox></p>
        </CollapseItem>
    </Collapse>
</template>

<script lang="ts">
import {Component, Vue, Prop, Watch} from 'vue-property-decorator'
    import {IAttributes} from "../../../types/stock";
    import {ProductsListStore} from "../../../store/modules/stock/productsList";

    @Component
    export default class StringFilter extends Vue {
        @Prop() readonly attribute!: IAttributes;
        @Prop() readonly value!: any;

        valueAttribute: any = [];

        get params() {
            let datas : any[] = [];
            this.attribute.values?.forEach(function(item: string) {
                datas.push(item.replace(/['"]+/g, '').toLowerCase());
            })
            return datas;
        }

        /*choose(data) {
            console.log(data)
            this.$emit('changeValue', {value: data, attribute_id: this.attribute.id, type: 'choose' });
        }*/

        @Watch('valueAttribute', {deep: true})
        onChangeValueAttribute() {
            this.$emit('changeValue', {value: this.valueAttribute, attribute_id: this.attribute.id, type: 'choose' });
        }

        checkAll() {
            if (this.valueAttribute.length == this.params.length) {
                this.valueAttribute = [];
            } else {
                this.valueAttribute = this.params;
            }
        }

        @Watch('value', {deep: true})
        onChangeValue() {
            this.valueAttribute = this.value;
        }
    }
</script>
<style lang="less">
    .checkbox-filter {
        margin-top: -20px !important;

        &__checkall {
            margin-bottom: 30px !important;
            margin-top: 0 !important;
            padding-bottom: 10px;
            border-bottom: 1px solid #eeeeee;
        }

        label {
            display: block !important;
            margin-bottom: 5px;
            cursor: pointer;
        }
    }
</style>