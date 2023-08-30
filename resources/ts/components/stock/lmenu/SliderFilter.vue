<template>
    <Collapse>
        <CollapseItem>
            <template slot='title'><span class="collapse-title-text slider-component__title">{{ title }}</span>
            </template>
            <Cell class="slider-component">
                <!--                <p class="collapse-title-text">{{ title }}</p>-->
                <Slider class="slider-component__slider" v-model="localValue" :range="range" multiple></Slider>
                <div class="slider-component__inputs">
                    <div class="h-input-group" v-width="100">
                        <span class="h-input-addon">от:</span>
                        <input type="number" v-model="localValue.start"/>
                    </div>
                    <div class="h-input-group ml-5" v-width="100">
                        <span class="h-input-addon">до:</span>
                        <input type="number" v-model="localValue.end"/>
                    </div>
                </div>
            </Cell>
        </CollapseItem>
    </Collapse>

</template>

<script lang="ts">
import {Component, Vue, Prop, Watch} from 'vue-property-decorator'

@Component
export default class LMenu extends Vue {
    @Prop({default: 'Название'}) readonly title!: String;
    @Prop({default: 0}) readonly attribute_id!: number;
    @Prop({
        default() {
            return {start: 0, end: 200}
        }
    }) readonly range!: any;
    @Prop({
        default() {
            return {start: 0, end: 200}
        }
    }) readonly value!: any;

    localValue = this.value;

    @Watch('localValue', {deep: true})
    onChangeDiapason() {
        this.$emit('changeValue', {value: this.localValue, attribute_id: this.attribute_id, type: 'double'})
    }



    @Watch('value', {deep: true})
    onChangeValue() {
        this.localValue = this.value;
    }
}
</script>

<style lang="less">
.slider-component {
    padding: 0 6px;

    &__title {
        overflow: hidden;
    }

    &__inputs {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
    }

    &__slider {
        margin: 5px;
        padding: 5px;
    }
}
</style>