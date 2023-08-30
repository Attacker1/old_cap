<template>

   <!--<div class="h-panel-bar">
       <span class="h-panel-title">{{ $route.meta.title }}</span>
       <div class="h-panel-right">
           <Search @search="search"
                   v-model="searchText3"
                   v-width="300"
                   showSearchButton
                   placeholder="#артикул"
           ><i class="h-icon-search"></i>
           </Search>

       </div>
   </div>-->
   <div class="h-panel-bar">
       <Row type="flex" justify="end" :space="20" class="dark2-color stock-nav-head">
           <Cell>
               <router-link class="stock-link" :to="{ name: 'admin.stock.index'}">Главная</router-link>
           </Cell>
           <Cell>
               <!--<DropdownMenu :disabled="!selectAll" dict="" class-name="h-text-dropdown"><span :disabled="!selectAll">Информация</span></DropdownMenu>-->
           </Cell>
           <Cell>
             <router-link class="stock-link" :to="{ name: 'admin.stock.my.orderlist'}">Мои заказы</router-link>
           </Cell>
           <Cell>
               <Row type="flex" direction="row" :space="4">
                   <Cell><a href="" class="stock-link"><i class="far fa-heart"></i></a></Cell>
                   <Cell>
                       <router-link class="stock-link" :to="{ name: 'admin.stock.my.favourite'}">Избранное</router-link>
                   </Cell>
               </Row>
           </Cell>
           <Cell>
               <Row type="flex" direction="row" :space="4">
                   <Cell><a class="stock-link"><i class="fas fa-shopping-basket"></i></a></Cell>
                   <Cell>
<!--                     <router-link class="stock-link" :to="{ name: 'admin.stock.my.cart'}">Товаров {{ cartProducts.length }}</router-link>-->
                     <div class="stock-link" @click="openModal()">Товаров {{ cart.products ? cart.products.length : 0 }}</div>
                   </Cell>
               </Row>
           </Cell>
           <Modal v-model="openedModal"
                  hasCloseIcon
                  type="drawer-right"
                  :params="{products: this.cart.products}"
           >
               <ModalCart
                   :products="cart.products"
               />
           </Modal>
       </Row>
   </div>

</template>

<script lang="ts">
    import {Component, Vue} from 'vue-property-decorator'
    import {CartStore} from "../../store/modules/stock/cart";
    import {ICart, IProducts} from "../../types/stock";
    import ModalCart from "./myCart/ModalCart.vue";
    @Component({
        components: {ModalCart}
    })
    export default class Head extends Vue {
        public openedModal: boolean = false

        search(data) {
            //this.$data.info(`Поиск "${data}"`);
            return 'hallo';
        }

        selectAll() {}

        get cart(): ICart {
            return CartStore.cart;
        }

        openModal() {
            this.openedModal = true
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
    .stock {
        &-link {
            font-size: 16px;
            font-weight: 600;
        }
    }
</style>