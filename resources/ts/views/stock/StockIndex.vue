<template>
  <MainFrame :title="$route.meta.title">
      <div id="main-frame" class="stock-frame">
        <!-- content -->
        <Head></Head>

        <Row :space-x="10" :space-y="20" type="flex" class="stock-index-content">
          <!--  Меню-->
          <Cell class="stock-left-menu">
            <LMenu></LMenu>
          </Cell>
          <!--   Товары -->
          <Cell class="stock-products">
              <div class="stock-reservation-info" v-if="minutesBeforeExpire">
                  Пожалуйста, оформите заказ в течение {{ minutesBeforeExpire + ' минут' }},
                  чтобы гарантировать, что срок действия вашего товара не истекает.
              </div>
            <Products
                :products="pageProducts.data"
            />
<!--            <p><Pagination :cur="pageProducts.current_page" :total="pageProducts.total" :size="pageProducts.per_page" layout="pager" align="center" @change="changePage"></Pagination></p>-->
          </Cell>
        </Row>
      </div>
  </MainFrame>
</template>
<script lang="ts">
import {Component, Mixins, Vue} from "vue-property-decorator";
import MainFrame from "../../components/layout/MainFrame.vue";
import Head from "../../components/stock/Head.vue";
import LMenu from "../../components/stock/LMenu.vue";
import Products from "../../components/stock/Products.vue";
import {ICart, IPageProducts, IProducts} from "../../types/stock";
import {ProductsListStore} from "../../store/modules/stock/productsList";
import {CartStore} from "../../store/modules/stock/cart";
import rootMixin from "../../mixins/settings/rootMixin";
import manageMixin from "../../mixins/settings/manageMixin";

@Component({
  components: {
    MainFrame,
    Head,
    LMenu,
    Products
  }
})

export default class StockIndex extends Mixins(rootMixin,manageMixin) {

    page = 1;

    async changePage(value) {
        try {
            await ProductsListStore.loadProducts(value.cur);
        } catch (e) {
            console.log(e);
        }
    }

    get pageProducts(): IPageProducts {
        return ProductsListStore.products;
    }

    async beforeCreate() {
        try {
            await ProductsListStore.loadProducts(1);
        } catch (e) {
            console.log(e);
        }
    }

    getNextProducts() {
        window.onscroll = () => {
            let bottomOfWindow = -document.documentElement.getBoundingClientRect().top + window.innerHeight === document.documentElement.offsetHeight;
            if (bottomOfWindow) {
                try {
                    ProductsListStore.loadProducts(this.page + 1);
                } catch (e) {
                    console.log(e);
                }
                this.page++;
            }
        }
    }

    get cart(): ICart {
        return CartStore.cart;
    }

    get minutesBeforeExpire(): number | null {
        if (this.cart.expired_time) {
            return Math.floor((Date.parse(this.cart.expired_time) - Date.now()) / (1000 * 60));
        }
        return null;
    }

    async mounted() {
        this.getNextProducts();
        console.log(this.user);
    }

    beforeDestroy() {
        ProductsListStore.resetProducts();
    }

}
</script>
<style lang="less">
    .h-layout-content {
        height: auto !important;
    }

    .slider-component__inputs {
        margin: 10px 0 !important;
    }

    .stock-reservation-info {
        text-align: center;
        padding: 20px 10px;
        color: #fff;
        background-color: #1e85be;
    }
</style>
