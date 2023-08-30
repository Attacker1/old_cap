<template>
    <div class="product-card__mini">
        <div class="product-card__left">
            <img :src="product.img ? product.img : '/assets-vuex/img/test-product.jpg'"
                 alt="product"
                 class="product-card__image"
                 @click="proceed()"
            >
        </div>
        <div class="product-card__right">
            <div class="product-card__wrapper">
                <p class="product-card__item">{{ product.name + ' * 1' }}</p>
                <p class="product-card__item product-card__price">{{ product.price.toFixed(2) / 100 }}₽</p>
            </div>
            <p class="product-card__item">брэнд</p>
            <p class="product-card__item">{{ product.size }}</p>
            <button class="product-card__remove" @click.prevent="removeProduct()">
                <i class="fa fa-times product-card__remove-icon" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</template>
<script lang="ts">
import {Component, Vue, Prop} from 'vue-property-decorator'
import {IProducts} from "../../../types/stock";
import {CartStore} from "../../../store/modules/stock/cart";

@Component
export default class ProductCardMini extends Vue {
    @Prop() readonly product!: IProducts;

    proceed() {
        this.$router.push(
            {
                name: 'admin.stock.product',
                params: {
                    product_id: this.product.id!.toString(),
                }
            }).catch(err => {});
    }

    async removeProduct() {
        await CartStore.removeCartProduct(this.product);
    }
}
</script>
<style lang="less">
.product-card {
    &__mini {
        display: flex;
        width: 100%;
        padding: 10px 30px 10px 0;
        border-bottom: 1px solid #f2f2f2;
        color: #666666;
        font-size: 12px;
        position: relative;

        &:last-child {
            border-bottom: none;
        }
    }

    &__left {
        display: flex;
    }

    &__right {
        display: flex;
        flex-direction: column;
    }

    &__wrapper {
        display: flex;
    }

    &__image {
        width: 50px;
        object-fit: contain;
        cursor: pointer;
    }

    &__item {
        margin: 0 0 0 10px;
    }

    &__price {
        margin: 0 0 0 10px;
        white-space: nowrap;
    }

    &__remove {
        position: absolute;
        top: 10px;
        right: 0;
        background-color: transparent;
        margin-left: 20px;
        border: none;
        cursor: pointer;
        outline: none;

        &-icon {
            color: #F64E60 !important;
            font-size: 14px;
        }
    }
}
</style>