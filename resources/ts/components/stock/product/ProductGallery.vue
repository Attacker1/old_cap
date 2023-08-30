<template>
    <div class="product-gallery">
        <div class="product-gallery__images">
            <img class="product-gallery__image"
                 v-for="(item, index) of imagesData"
                 :key="index"
                 :class="imagesData[index] === mainImg ? 'active' : ''"
                 @click="setMainImage(index)"
                 :src="item">
        </div>
        <img class="product-gallery__main-image" :src=mainImg>
    </div>
</template>

<script lang="ts">

import {Component, Vue, Prop} from 'vue-property-decorator'

@Component
export default class ProductGallery extends Vue {
    @Prop() imagesData!: any;

    mainImg = this.imagesData[0];

    showMainImg(index) {
        this.mainImg = index
    }

    setMainImage(index) {
        this.mainImg = this.imagesData[index];
    }
}

</script>

<style lang="less">
.product-gallery {
    display: flex;

    &__main {
        width: 400px;
        height: 533px;

        &-image {
            width: 400px;
        }
    }

    &__images {
        display: flex;
        flex-direction: column;
        margin-right: 20px;
    }

    &__image {
        object-fit: cover;
        border: 1px solid #ccc;
        margin-bottom: 20px;
        width: 100px;
        height: 100px;
        cursor: pointer;

        &:hover {
            border-color: #ef0404;
        }

        &.active {
            border-color: #ef0404;
        }
    }
}
</style>