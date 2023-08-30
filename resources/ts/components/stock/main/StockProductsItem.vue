<template>
    <div class="stock-products-item">
        <div class="stock-products-img-wrapper">
          <div>
              <img :src="productMainImageUrl ? productMainImageUrl : `/assets-vuex/img/main/placeholder.jpg`"
                   alt="product"
                   @click="$emit('openProductModal', product.id)"
              >
              <a @click="toggleFavorites">
                  <i class="stock-to-favorites fas fa-heart"
                     v-bind:class="{ active: product.isLiked }"
                  ></i>
              </a>
            <div class="stock-product-price">{{ product.price.toFixed(2) / 100 }}₽</div>
          </div>
        <!--<img :src="require(`../../assets/img/main/${item.img}`)" alt="product">-->
        <!--<span class="h-panel-title">Товар #{{key}}</span>-->

        <div class="stock-products-wrapper">
          <div class="stock-product-title">
            <span @click="proceed" class="stock-product-link">{{ product.name }}</span>
            <span>({{ product.size }})</span>
          </div>
          <div class="stock-product-divider"></div>
        </div>
        </div>
    </div>

</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator'
import {IProducts} from "../../../types/stock";
import {CartStore} from "../../../store/modules/stock/cart";
import {LikeStore} from "../../../store/modules/stock/like";

@Component
export default class StockProductsItem extends Vue {
    @Prop() readonly product!: IProducts;

    async toggleFavorites() {
        if (!this.product.isLiked) {
            await LikeStore.addLike(this.product.id!);
        } else {
            await LikeStore.removeLike(this.product.id!);
        }
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

    get productMainImageUrl(): string | null {
        return this.product.images!.length > 0 ? this.product.images!.find(e => e.main == 1).img_preview : null;
    }
}
</script>
<style lang="less">

.stock {
  &-title {
    background-color: #0bb2aa !important;
  }

  &-link {
      font-size: 16px;
      font-weight: 600;
  }

  &-product-price {
    width: auto;
    position: absolute;
    font-size: 12px;
    bottom: 42px;
    left: 0;
    color: white;
    padding: 0 5px;
    opacity: 0.7;
    background-color: #45b984 !important;
  }
  &-product-title {
    font-size: 10px;
    line-height: 1.2;
    text-transform: uppercase;
    margin-bottom: 10px;
    font-weight: bold;
    min-height: 25px;
  }
  &-product-divider {
    border-bottom: 1px dotted #dcdcdc;
  }

    &-product-link {
        cursor: pointer;
    }

    &-to-favorites {
        &.active {
            color: #e11617 !important;
            text-shadow: none !important;
        }
    }
}
</style>