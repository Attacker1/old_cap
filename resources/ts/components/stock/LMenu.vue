<template>

    <div class="h-panel" v-bg-color:gray4>
        <Row>
            <Cell class="clothes"><Clothes></Clothes></Cell>
            <Cell class="product-prices">
                <SliderFilter
                    :title="'Цена'"
                    :attribute_id="0"
                    :range="{start: 0, end: 10000}"
                    :value="defaultPriceValue"
                    v-on:changeValue="filterData"
                />
            </Cell>
            <Cell v-if="attributes"
                  v-for='(item, index) in attributes'
                  :key='index'>
                <StringFilter
                    :attribute="item"
                    :value="defaultCheckboxValue"
                    v-on:changeValue="filterData"
                />
            </Cell>
            <Cell class="chest-girth">
                <SliderFilter
                    :title="'Грудь в см'"
                    :attribute_id="5"
                    :value="defaultSliderValue"
                    v-on:changeValue="filterData"
                />
            </Cell>
            <Cell class="chest-girth">
              <SliderFilter
                :title="'Талия в см'"
                :attribute_id="6"
                :value="defaultSliderValue"
                v-on:changeValue="filterData"
              />
            </Cell>
            <Cell class="waist-circumference">
              <SliderFilter
                :title="'Бедра (max) в см'"
                :attribute_id="8"
                :value="defaultSliderValue"
                v-on:changeValue="filterData"
              />
            </Cell>
            <Cell class="product-length">
                <SliderFilter
                    :title="'Длина ( для верха - по спине от начала до конца) в см'"
                    :attribute_id="9"
                    :value="defaultSliderValue"
                    v-on:changeValue="filterData"
                />
            </Cell>
            <Cell type="flex" class="submit-filters">
                <Button color="primary" @click="clearFilter()">Очистить</Button>
                <Button color="primary" @click="getFilterData()">Найти</Button>
            </Cell>
        </Row>
    </div>

</template>

<script lang="ts">
    import {Component, Vue} from 'vue-property-decorator'
    import Clothes from './lmenu/Clothes.vue'
    import Colors from './lmenu/Colors.vue'
    import Seasons from './lmenu/Seasons.vue'
    import Sizes from './lmenu/Sizes.vue'
    import Materials from './lmenu/Materials.vue'
    import Brands from './lmenu/Brands.vue'
    import SliderFilter from "./lmenu/SliderFilter.vue";
    import {IAttributes} from "../../types/stock";
    import {ProductsListStore} from "../../store/modules/stock/productsList";
    import {AttributeStore} from "../../store/modules/stock/attribute";
    import StringFilter from "./lmenu/StringFilter.vue";

    @Component({
        components: {
          SliderFilter,
            Colors,
            Clothes,
            Seasons,
            Sizes,
            Materials,
            Brands,
            StringFilter
        }
    })

    export default class LMenu extends Vue {
        timeoutObject;
        findData;
        defaultCheckboxValue = [];
        defaultPriceValue = {start: 0, end: 10000};
        defaultSliderValue = {start: 0, end: 200};

        setTimer() {
            this.findData = 500;
        }

        get attributes(): IAttributes[] {
            return AttributeStore.attributes;
        }

        get filters(): any {
            return ProductsListStore.filters;
        }

        async beforeCreate() {
            try {
                await AttributeStore.loadAttributes();
            } catch (e) {
                console.log(e);
            }
        }

        filterData(params) {
            try {
                clearInterval(this.timeoutObject);
                this.setTimer();
                this.timeoutObject = setTimeout(async () => {
                     await ProductsListStore.addFilters({
                         id: params.attribute_id,
                         type: params.type,
                         values: params.value ?? null,
                         start: params.value.start ?? null,
                         end: params.value.end ?? null
                     });
                }, this.findData);
            } catch (e) {
                console.log(e);
            }
        }

        async getFilterData() {
            try {
                await ProductsListStore.loadProducts(1);
            } catch (e) {
                console.log(e);
            }
        }

        async clearFilter() {
            this.defaultCheckboxValue = [];
            this.defaultPriceValue = {start: 0, end: 10000};
            this.defaultSliderValue = {start: 0, end: 200};
            await ProductsListStore.removeFilters();
        }
    }
</script>
<style lang="less">
    .submit-filters {
        padding: 6px;
    }
</style>
