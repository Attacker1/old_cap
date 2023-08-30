<template>
    <MainFrame :title="$route.meta.title">
        <div class="product">
            <Head/>

            <Row :space-x="-10" :space-y="-10" type="flex" class="product__wrapper">
                <ProductDetails
                    :itemProduct="product"
                />
            </Row>
        </div>
    </MainFrame>
</template>
<script lang="ts">
import {Component, Vue} from "vue-property-decorator";
import Head from "../../components/stock/Head.vue";
import ProductDetails from "../../components/stock/ProductDetails.vue";
import MainFrame from "../../components/layout/MainFrame.vue";
import {IProducts} from "../../types/stock";
import {ProductsListStore} from "../../store/modules/stock/productsList";
import {SingleProductStore} from "../../store/modules/stock/singleProduct";

@Component({
    components: {
        MainFrame,
        Head,
        ProductDetails
    }
})

export default class Product extends Vue {

    get product(): IProducts {
        return SingleProductStore.product;
    }

    async beforeCreate() {
        try {
            await SingleProductStore.loadProduct(Number(this.$route.params.product_id));
        } catch (e) {
            console.log(e);
        }
    }

    beforeDestroy() {
        SingleProductStore.resetProduct();
    }
}
</script>

<style lang="less">
    .product {
        overflow-y: auto;
        height: 100%;
    }
</style>