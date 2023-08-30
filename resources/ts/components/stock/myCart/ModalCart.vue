<template>
    <div class="modal-cart">
        <Row type="flex" justify="space-between" direction="column" :space="20" class="modal-cart__wrapper">
            <Cell>
                <Row type="flex" direction="column" class="modal-cart__products">
                    <ProductCardMini
                        v-if="products"
                        v-for="(product, index) of products"
                        :key="index"
                        :product="product"
                    />
                </Row>
            </Cell>
            <Cell>
                <table class="modal-cart__total">
                    <tr>
                        <td>Подытог</td>
                        <td>{{ '₽ ' + '123123' }}</td>
                    </tr>
                </table>
                <Button color="primary" :block="true" @click="proceed">Оформить заказ</Button>
            </Cell>
        </Row>
    </div>
</template>
<script lang="ts">
import {Component, Vue, Prop} from 'vue-property-decorator'
import {IProducts} from "../../../types/stock";
import ProductCardMini from "./ProductCardMini.vue";

@Component({
    components: {
        ProductCardMini
    }
})
export default class ModalCart extends Vue {
    @Prop() readonly products!: IProducts[];

    proceed() {
        this.$router.push({
                name: 'admin.stock.my.cart'
            }
        )
    }
}
</script>
<style lang="less">
    .modal-cart {
        padding-top: 30px;
        height: 100%;

        &__wrapper {
            height: 100%;
        }

        &__products {
            width: 300px;
        }

        &__total {
            width: 100%;
            margin: 20px 0 !important;
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
    }
</style>