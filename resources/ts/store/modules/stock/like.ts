import {Action, getModule, Module, Mutation, VuexModule} from 'vuex-module-decorators';
import store from "../../index";
import Axios from "axios";
import {IProducts} from "../../../types/stock";
import HeyUI from "heyui";
import {ProductsListStore} from "./productsList";
import {FavouriteProductsListStore} from "./favouriteProductsList";
import {SingleProductStore} from "./singleProduct";


const axios = Axios.create({
    baseURL: '/admin/vuex/stock'
});


@Module({
    namespaced: true,
    name: 'like',
    store,
    dynamic: true,
})
class LikeModule extends VuexModule {

    @Action({rawError: true})
    public async addLike(product_id: number): Promise<any> {
        try {
            const response = await axios.post('like', {
                func: 'addLike',
                product_id: product_id
            });
            if (response) {
                ProductsListStore.setLike({product_id: product_id, liked: true});
                if (SingleProductStore.product) {
                    SingleProductStore.setLike(true);
                }
            }
        } catch (e: any) {
            HeyUI.$Notice['success'](e.response.data.error.message);
        }
    }

    @Action({rawError: true})
    public async removeLike(product_id: number): Promise<any> {
        try {
            const response = await axios.post('like', {
                func: 'removeLike',
                product_id: product_id
            });
            if (response) {
                ProductsListStore.setLike({product_id: product_id, liked: false});
                FavouriteProductsListStore.removeProduct(product_id);
                if (SingleProductStore.product) {
                    SingleProductStore.setLike(false);
                }
            }
        } catch (e: any) {
            HeyUI.$Notice['success'](e.response.data.error.message);
        }
    }
}

export const LikeStore = getModule(LikeModule);
