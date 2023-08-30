import {Action, getModule, Module, Mutation, VuexModule} from 'vuex-module-decorators';
import store from "../../index";
import Axios from "axios";
import {IPageProducts, IProducts} from "../../../types/stock";


const axios = Axios.create({
    baseURL: '/admin/vuex/stock'
});


@Module({
    namespaced: true,
    name: 'favouriteProductsList',
    store,
    dynamic: true,
})
class FavouriteProductsListModule extends VuexModule {
    favouriteProducts: IProducts[] = [];

    @Mutation
    private setProducts(products: IProducts[]) {
        this.favouriteProducts = products;
    }

    @Mutation
    public removeProduct(product_id: number) {
        const index = this.favouriteProducts.findIndex(n => n.id === product_id);
        if (index !== -1) {
            this.favouriteProducts.splice(index, 1);
        }
    }

    @Action
    public async resetProducts() {
        this.setProducts([]);
    }


    @Action({rawError: true})
    public async loadFavouriteProducts(): Promise<any> {
        const response = await axios.post('like', {
            func: 'fetchFavourites',
        });
        const data = response.data as IProducts[];
        this.setProducts(data);
    }

}

export const FavouriteProductsListStore = getModule(FavouriteProductsListModule);
