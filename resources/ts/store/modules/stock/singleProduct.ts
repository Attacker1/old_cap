import {Action, getModule, Module, Mutation, VuexModule} from 'vuex-module-decorators';
import store from "../../index";
import Axios from "axios";
import {IProducts} from "../../../types/stock";


const axios = Axios.create({
    baseURL: '/admin/vuex/stock'
});


@Module({
    namespaced: true,
    name: 'singleProduct',
    store,
    dynamic: true,
})
class SingleProductModule extends VuexModule {
    product: IProducts = {};

    @Mutation
    private setProduct(product: IProducts) {
        this.product = product;
    }

    @Mutation
    public setLike(value: boolean) {
        this.product['isLiked'] = value;
    }

    @Action
    public async resetProduct() {
        this.setProduct({});
    }


    @Action({rawError: true})
    public async loadProduct(product_id: number): Promise<any> {
        const response = await axios.post('product', {
            func: 'find',
            id: product_id
        });
        const data = response.data as IProducts;
        this.setProduct(data);
    }
}

export const SingleProductStore = getModule(SingleProductModule);
