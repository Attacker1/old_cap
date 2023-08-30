<template>
    <MainFrame :title="$route.meta.title">
        <Head/>
        <OrderHead class="order__head" />

        <Row :space-x="10" :space-y="20" type="flex" class="order__content">
            <!--   Товары -->
            <Cell class="order__products">
                <OrderProductCard
                    v-if="products"
                    v-for="(product, index) of products"
                    :key="index"
                    :product="product"
                />
            </Cell>
        </Row>
    </MainFrame>
</template>
<script lang="ts">
import {Component, Vue} from "vue-property-decorator";
import Head from "../../components/stock/Head.vue";
import MainFrame from "../../components/layout/MainFrame.vue";
import ProductCardMini from "../../components/stock/myCart/ProductCardMini.vue";
import {IProducts} from "../../types/stock";
import OrderHead from "../../components/stock/order/OrderHead.vue";
import OrderProductCard from "../../components/stock/order/OrderProductCard.vue";

@Component({
    components: {
        ProductCardMini,
        MainFrame,
        Head,
        OrderHead,
        OrderProductCard
    }
})

export default class Order extends Vue {
    products: IProducts[] = [
        {
            id: 0,
            name: "0-5234123 Платье",
            brand_id: {
                id: 5,
                name: 'Puma'
            },
            price: 4990,
            size: '42'
        },

        {
            id: 1,
            name: "0-41234123 Блузка Женская",
            brand_id: {
                id: 1,
                name: 'Prada'
            },
            price: 3990,
            size: '42'
        },

        {
            id: 2,
            name: "00 25234123 Брюки",
            brand_id: {
                id: 2,
                name: 'Jacquemus'
            },
            price: 3299,
            size: '42'
        },

        {
            id: 3,
            name: "0-5234123 Ремень",
            brand_id: {
                id: 3,
                name: 'Gucci'
            },
            price: 1290,
            size: '42'
        },

        {
            id: 4,
            name: "0-5234123 Джемпер жен",
            brand_id: {
                id: 4,
                name: 'Furla'
            },
            price: 1190,
            size: '42'
        },
    ];

    data() {
        return {
            value1: 'Стилист тест пользователь',
            amo_id: '123123123',
            subtotal: 19.367,
            total: 19.367
        };
    }

    beforeCreate() {
        console.log(this.$route.params.order_id);
    }
}
</script>

<style lang="less">
    .order {
        &__products {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            column-gap: 15px;
            border: 1px solid #f4f5f8;
            border-radius: 5px;
            padding: 0 20px;
        }
    }
</style>