<template>
    <MainFrame :title="$route.meta.title">
        <div class="stock-frame">
            <!-- content -->
            <Head></Head>

            <Row :space-x="10" :space-y="1" type="flex" class="stock-products">
                <Cell  v-padding="5"  v-for='(item, index) in favouriteProducts' :key='index'>
                    <StockProductsItem :product="item"/>
                </Cell>
            </Row>
        </div>
    </MainFrame>
</template>
<script lang="ts">
import {Component, Vue} from "vue-property-decorator";
import {IPageProducts, IProducts} from "../../types/stock";
import Head from "../../components/stock/Head.vue";
import MainFrame from "../../components/layout/MainFrame.vue";
import StockProductsItem from "../../components/stock/main/StockProductsItem.vue";
import {ProductsListStore} from "../../store/modules/stock/productsList";
import {FavouriteProductsListStore} from "../../store/modules/stock/favouriteProductsList";

@Component({
    components: {
        Head,
        MainFrame,
        StockProductsItem
    }
})

export default class Favorite extends Vue {

    data() {
        return {
            value1: 'Стилист тест пользователь',
            amo_id: '123123123',
            subtotal: 19.367,
            total: 19.367
        };
    }

    get favouriteProducts(): IProducts[] {
        return FavouriteProductsListStore.favouriteProducts;
    }

    async beforeCreate() {
        try {
            await FavouriteProductsListStore.loadFavouriteProducts();
        } catch (e) {
            console.log(e);
        }
    }
}
</script>

<style lang="less">

</style>