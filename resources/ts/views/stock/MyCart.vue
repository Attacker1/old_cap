<template>
    <MainFrame :title="$route.meta.title">
        <form class="stock-frame">
            <!-- content -->
            <Head></Head>

            <Row type="flex" class="mycart">
                <Cell class="mycart-details">
                    <h3>Детали заказа</h3>
                    <p>Стилист</p>
                    <div>
                        <input type="text" v-model="value1" readonly/>
                    </div>
                    <p>AMO_ID</p>
                    <div>
                        <input type="text" v-model="amo_id" readonly/>
                    </div>
                </Cell>
                <Cell class="mycart__capsula">
                    <h3>Капсула клиента</h3>
                    <div class="mycart__products">
                        <ProductCardMini
                            v-if="cart.products.length > 0"
                            v-for="(product, index) of cart.products"
                            :key="index"
                            :product="product"
                        />
                    </div>
                    <table class="mycart__total">
                        <tr>
                            <td>Подытог</td>
                            <td>{{ '₽ ' + subtotal }}</td>
                        </tr>
                        <tr>
                            <td>Итого</td>
                            <td>{{ '₽ ' + total }}</td>
                        </tr>
                    </table>
                    <button class="mycart__submit">Подтвердить заказ</button>
                </Cell>
            </Row>
        </form>
    </MainFrame>
</template>
<script lang="ts">
import {Component, Vue} from "vue-property-decorator";
import Head from "../../components/stock/Head.vue";
import MainFrame from "../../components/layout/MainFrame.vue";
import ProductCardMini from "../../components/stock/myCart/ProductCardMini.vue";
import {ICart, IProducts} from "../../types/stock";
import {CartStore} from "../../store/modules/stock/cart";

@Component({
    components: {
        ProductCardMini,
        MainFrame,
        Head
    }
})


export default class MyCart extends Vue {

    data() {
        return {
            value1: 'Стилист тест пользователь',
            amo_id: '123123123',
            subtotal: 19.367,
            total: 19.367
        };
    }

    get cart(): ICart {
        return CartStore.cart;
    }

    async beforeCreate() {
        try {
            await CartStore.loadCartProducts();
        } catch (e) {
            console.log(e);
        }
    }
}
</script>

<style lang="less">
.mycart {
    width: 75%;
    margin: 0 auto;

    &__products {
        display: flex;
        flex-direction: column;
        width: 300px;
    }

    &__total {
        width: 100%;
        margin-top: 20px !important;
        background-color: #fafafa;
        border: 1px solid #eaeaea;
        border-collapse: collapse;

        td {
            border-bottom: 1px solid #eaeaea !important;
            padding: 10px !important;

            &:last-child {
                text-align: end;
            }
        }
    }

    &__submit {
        width: 100%;
        background-color: #232323;
        color: #ffffff;
        text-align: center;
        border: none;
        cursor: pointer;
        outline: none !important;
        padding: 10px !important;
        margin-top: 30px !important;
    }
}
</style>