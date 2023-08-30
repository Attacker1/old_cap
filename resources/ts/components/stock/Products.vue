<template>

    <Row :space-x="10" :space-y="1" type="flex" class="stock-products">
        <Cell  v-padding="5"  v-for='(item, index) in products' :key='index'>
            <StockProductsItem :product="item" v-on:openProductModal="openModal"/>
        </Cell>
        <Modal v-model="openedModal" v-if="selectedProduct"
               hasCloseIcon
               :params="{itemProduct: this.selectedProduct}"
        >
          <div slot="header" v-if="selectedProduct.name"><b>{{ selectedProduct.name}}</b></div>
            <ProductDetails
                :itemProduct="selectedProduct"
            />
            <div slot="footer">
                <button class="h-btn h-btn-red" @click="openedModal=false" :params="{item: selectedProduct}">закрыть</button></div>
        </Modal>
    </Row>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator'
import ProductDetails from './ProductDetails.vue'
import StockProductsItem from "./main/StockProductsItem.vue";
import {IProducts} from "../../types/stock";

@Component({
    components: {
        ProductDetails,
        StockProductsItem
    }
})

export default class Products extends Vue {
    @Prop() readonly products!: IProducts[];

    public openedModal: boolean = false
    public selectedProduct: IProducts | null = null

    openModal(index) {
        this.openedModal = true
        this.selectedProduct = this.products.find(e => e.id == index) ?? null;
    }
}
</script>
