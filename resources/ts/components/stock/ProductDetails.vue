<template>
    <div class="product-details">
        <Row class="product-details-wrapper">
            <Cell class="product-img-wrapper">
                <Row type="flex" class="product-img-wrapper-dop">
                    <ProductGallery :imagesData="images" />
                </Row>

            </Cell>
            <Cell class="product-text-wrapper">
              <span>Бренд: {{ productAttributeData(1).value }}</span>
                <span v-if="itemProduct.size">({{ itemProduct.size }})</span>
<!--                <span>₽{{-->
<!--                        parseInt(itemProduct.price).toLocaleString('ru-RU') + itemProduct.price[itemProduct.price.length - 3] + itemProduct.price[itemProduct.price.length - 2] + itemProduct.price[itemProduct.price.length - 1]-->
<!--                    }}-->
<!--                </span>-->
              <div class="product-details-select-container" >
                <Select v-model="selectColor" :datas="itemProductColors" placeholder="цвета" class="product-details-select-box"></Select>
                <Select v-model="selectSize" :datas="itemProductSizes" placeholder="размеры" class="product-details-select-box"></Select>
              </div>
                <div class="product-samples" v-if="samples.length > 0">
                    <span class="product-samples__title">Замеры:</span>
                    <Table :datas="samples" stripe>
                        <TableItem prop="name" width="85%"></TableItem>
                        <TableItem prop="value"></TableItem>
                    </Table>
                </div>

                <div class="product-guide" v-if="productAttributeData(17).name && productAttributeData(18).name">
                    <span class="product-samples__title">Как подбирать:</span>
                    <Table :datas="[productAttributeData(18), productAttributeData(17)]" stripe>
                        <TableItem prop="name"></TableItem>
                        <TableItem>
                            <template slot-scope="{data}">
                                <a :href="data.value.slice(1,-1)" target="_blank">Ссылка на гайд</a>
                            </template>
<!--                            <a href="{{ data.value }}" target="_blank">Ссылка на гайд</a>-->
                        </TableItem>
                    </Table>
                </div>

              <div v-if="detailsCheckbox.length > 0" class="product-details-checkboxes">
                <!--                    <Checkbox class="product-details__other-checkbox" v-model="details" :datas="detailsData" disabled></Checkbox>-->
                <Checkbox
                    v-for="(item, index) in detailsCheckbox"
                    :key='index'
                    class="product-details__other-checkbox" v-model="item.value" disabled>{{ item.title }}
                </Checkbox>
              </div>
              <div class="product-details__other-compound">
                <span v-if="productAttributeData(19).name"><span class="product-details__other-strong">{{ productAttributeData(19).name }}:</span>{{ productAttributeData(19).value }}<br></span>
                <span v-if="productAttributeData(22).name"><span class="product-details__other-strong">Модель:</span>{{ productAttributeData(22).name + ' ' + productAttributeData(22).value }}<br></span>
                <span v-if="productAttributeData(20).name"><span class="product-details__other-strong">{{ productAttributeData(20).name }}:</span>{{ productAttributeData(20).value }}<br></span>
              </div>

              <span class="product-details__quantity">{{ itemProduct.quantity + ' в наличии' }} </span>
              <div class="product-details-cart-buttons">
                <button class="h-btn h-btn-s h-btn-primary stock-to-favorites">
                  {{ parseInt(itemProduct.price / 100).toLocaleString('ru-RU') }}₽
                </button>
                <Button class="in-capsula-btn" color="blue" @click="addToCart">{{ isInTheCart ? 'Удалить из капсулы' : 'Добавить в капсулу' }}</Button>
                <button class="h-btn h-btn-s h-btn-blue stock-to-favorites"
                        @click="toggleFavorites">
                  <i class="fas fa-heart stock-to-favorites-icon"
                     v-bind:class="{ active: itemProduct.isLiked }"
                  ></i>
                </button>

              </div>
            </Cell>
        </Row>
    </div>

</template>

<script lang="ts">

import {Component, Vue, Prop} from 'vue-property-decorator'
import ProductGallery from "./product/ProductGallery.vue";
import {ICart, IProducts} from "../../types/stock";
import {ProductsListStore} from "../../store/modules/stock/productsList";
import {CartStore} from "../../store/modules/stock/cart";
import {LikeStore} from "../../store/modules/stock/like";
import {eventBus} from "../../bus";

@Component({
    components: {
        ProductGallery
    }
})
export default class ProductDetails extends Vue {
    @Prop() itemProduct!: IProducts;

    $Notice: any

    data() {
        return {
            selectColor: null,
            selectSize: null,
            itemProductSizes: ["S", "M", "XL"],
            itemProductColors: ["черный", "красный"],
            guide: [
                {name: 'Как подбирать:', value: ''},
                {name: 'Рост:', value: 86}
            ],
        }
    }

    get samples() {
        let samples : any = [];
        for(let i = 0; i < this.itemProduct.attributes!.length; i++) {
            if ([5, 6, 8, 9].includes( this.itemProduct.attributes![i].id!)) {
                samples.push(
                        {
                            name: this.itemProduct.attributes![i].name ?? '',
                            value: this.itemProduct.attributes![i].pivot!.value ?? ''
                        }
                    )
            }
        }

        return samples;

        // return this.itemProduct.attributes?.filter(item => [6, 8, 9].includes(item.id!))
    }

    get detailsCheckbox() {
        let items : any = [];

        for(let i = 0; i < this.itemProduct.attributes!.length; i++) {
            if ([11, 12, 13, 14, 16].includes( this.itemProduct.attributes![i].id!)) {
                items.push({
                    title: this.itemProduct.attributes![i].name ?? '',
                    value: this.itemProduct.attributes![i].pivot!.value === 'true'
                });
            }
        }

        return items;

        // return this.itemProduct.attributes?.filter(item => [6, 8, 9].includes(item.id!))
    }

    async toggleFavorites() {
        if (!this.itemProduct.isLiked) {
            await LikeStore.addLike(this.itemProduct.id!);
        } else {
            await LikeStore.removeLike(this.itemProduct.id!);
        }
    }

    productAttributeData(id): any {
        return {
            name: this.itemProduct.attributes?.find(e => e.id == id)?.name ?? null,
            value: this.itemProduct.attributes?.find(e => e.id == id)?.pivot?.value ?? null
        }
    }

    get isInTheCart(): boolean {
        if (this.cart.products && this.cart.products.length > 0) {
            for (let i = 0; i < this.cart.products.length; i++) {
                if (this.cart.products![i].id == this.itemProduct.id) {
                    return true;
                }
            }
        }
        return false;
    }

    get cart(): ICart {
        return CartStore.cart;
    }

    async addToCart() {
        if (this.isInTheCart) {
            await CartStore.removeCartProduct(this.itemProduct!);
            this.$Notice['success'](`Успешно удалено из капсулы`);
        } else {
            await CartStore.addCartProduct(this.itemProduct!);
            this.$Notice['success'](`Успешно добавлено в капсулу`);
        }
    }

    get images(): string[] | null {
        if (this.itemProduct.images && this.itemProduct.images.length > 0) {
            return this.itemProduct.images.map(e => e.img_original);
        }
        return null;
    }

}

</script>

<style lang="less">
    .h-table-header {
      height: 10px !important;
    }
    .h-table td {
      height: 10px;
      font-size: 11px;
    }
    .h-checkbox .h-checkbox-native {
      width: 13px;
      height: 11px;
    }
    .h-checkbox .h-checkbox-native:after {
      top: -3px;
      left: 3px;
      box-sizing: content-box;
      content: "";
      border: 2px solid #ffffff;
      border-left: 0;
      border-top: 0;
      height: -14px;
      position: absolute;
      width: 4px;
      transition: all 0.2s;
      transform: rotate(45deg) scale(0);
    }

    .product {
        &-title {
            font-size: 20px;
            font-weight: 600;
        }

        &-samples {
            margin-bottom: 20px;

            &__title {
                margin-bottom: 5px;
                font-weight: 600;
                display: block;
            }
        }

        &-text-wrapper {
            margin-left: 50px;
            min-width: 300px;
            max-width: 400px;
        }

        &-guide {
            margin-bottom: 20px;
        }

        &-details {
            &-wrapper {
                display: flex;
            }

          &-select-container {
            background-color: #e2f8f5;
              display: flex;
              height: 50px;
              min-width: 350px;
            }
          &-select-box {
            min-width: 150px;
            margin: 5px;
            padding: 5px;
            flex: 0 1 auto;
          }
          &-checkboxes {
            font-size: 12px;
          }
          &-cart-buttons {
          }

            &__other {
                margin-top: 30px;
                border: 2px solid #e7e7e7;
                padding: 20px 10px;

                &-title {
                    font-weight: 600;
                    font-size: 20px;
                }

                &-checkbox {
                    margin-right: 15px;
                }

                &-strong {
                    font-weight: 600;
                    margin-right: 5px;
                }
            }

            &__quantity {
                background: #eca905;
                display: inline-block;
                line-height: 1;
                color: #fff;
                padding: 5px 10px;
                margin: 10px 0 20px 0;
            }
            &__price {
                background: #eca905;
                display: inline-block;
                line-height: 1;
                color: #fff;
                padding: 5px 10px;
                border-radius: 50px;
                margin: 0 0 20px 0;
            }
          &__other-compound {
            margin-top: 5px;
            font-size: 12px;
            }
        }
    }

    .stock-to-favorites {
        padding: 8px 10px !important;

        &-icon {
            &.active {
                color: #e11617 !important;
                text-shadow: none !important;
            }
        }
    }
</style>