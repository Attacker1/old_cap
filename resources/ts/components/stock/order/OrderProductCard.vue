<template>
    <div class="order-product-card">
        <div class="order-product-card__wrapper">
            <div class="order-product-card__left">
                <img :src="`/assets-vuex/img/test-product.jpg`"
                     alt="product"
                     class="order-product-card__image"
                     @click="proceed()"
                >
                <span class="order-product-card__price">{{ product.price + ' р.' }}</span>
            </div>
            <div class="order-product-card__information">
                <h3 class="order-product-card__name">{{ product.name }}</h3>
                <span class="order-product-card__vendor">Артикул:</span>
                <span class="order-product-card__brand">Брэнд: {{ product.brand_id.name }}</span>
                <span class="order-product-card__size">Размер: {{ product.size }}</span>
            </div>
        </div>
        <button class="order-product-card__remove" @click="removeProduct()">
            <i class="fa far fa-trash-alt order-product-card__remove-icon"></i>
        </button>
    </div>

</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator'
import {IProducts} from "../../../types/stock";

@Component
export default class OrderProductCard extends Vue {
    @Prop() readonly product!: IProducts;

    removeProduct() {
        console.log('removing');
    }

    proceed() {
        let route = this.$router.resolve({
            name: 'admin.stock.product',
            params: {
                product_id: this.product.id!.toString(),
            }
        });
        window.open(route.href, '_blank');
    }
}
</script>
<style lang="less">
.order-product-card {
    display: flex;
    max-width: 400px;
    width: 100%;
    justify-content: space-between;
    padding: 20px;
    border-bottom: 1px solid #eeeeee;

    &__left {
        display: flex;
        flex-direction: column;
    }

    &__wrapper {
        display: flex;
    }

    &__information {
        display: flex;
        flex-direction: column;
        margin-left: 20px;
    }

    &__price {
        width: 100%;
        text-align: center;
        background-color: #14c6be;
        padding: 2px 3px;
        border-radius: 5px;
        margin-top: 5px;
        color: #ffffff;
    }

    &__image {
        width: 100px;
        object-fit: contain;
        cursor: pointer;
    }

    &__name {
        margin-bottom: 10px;
    }

    &__vendor {
        color: #f74f61;
        font-size: 15px;
        font-weight: 600;
    }

    &__brand {
        color: #519fff;
        font-size: 15px;
        font-weight: 600;
    }

    &__size {
        color: #8a4ffc;
        font-size: 15px;
        font-weight: 600;
    }

    &__remove {
        background-color: transparent;
        margin-left: 20px;
        border: none;
        cursor: pointer;
        outline: none;

        &-icon {
            color: #F64E60 !important;
            font-size: 20px;
        }
    }
}
</style>