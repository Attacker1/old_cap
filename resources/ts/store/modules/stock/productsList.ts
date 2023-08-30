import {Action, getModule, Module, Mutation, VuexModule} from 'vuex-module-decorators';
import store from "../../index";
import Axios from "axios";
import {IPageProducts, IProducts} from "../../../types/stock";


const axios = Axios.create({
    baseURL: '/admin/vuex/stock'
});


@Module({
    namespaced: true,
    name: 'productsList',
    store,
    dynamic: true,
})
class ProductsListModule extends VuexModule {
    products: IPageProducts = {};
    filters: any = [];

    @Mutation
    private setProducts(products: IPageProducts) {
        this.products = products;
    }

    @Mutation
    private resetFilters() {
        this.filters = [];
    }

    @Mutation
    private setFilters(filters: any) {
        const index = this.filters.findIndex(n => n.id === filters.id);
        if (index == -1) {
            this.filters.push(filters);
        } else {
            this.filters.map(function(item) {
                if (item.id == filters.id) {
                    switch(filters.type) {
                        case 'double':
                            item.start = filters.start;
                            item.end = filters.end;
                            break;
                        case 'choose':
                            item.values = filters.values;
                            break;
                    }
                    return item;
                }
            })
        }
    }

    @Mutation
    private updateProducts(products: IPageProducts) {
        if (products.data!.length > 0) {
            this.products.current_page = products.current_page;
            for (let i = 0; i < products.data!.length; i++) {
                this.products.data!.push(products.data![i]);
            }
        }
    }

    @Mutation
    public setLike(params: {product_id: number, liked: boolean}) {
        let product = this.products.data?.find( function($q) {
            return $q.id == params.product_id;
        });

        if (product) {
            product.isLiked = params.liked;
        }
    }

    @Action
    public async resetProducts() {
        this.setProducts({});
        this.resetFilters();
    }


    @Action
    public async removeFilters() {
        this.resetFilters();
    }


    @Action({rawError: true})
    public async loadProducts(page: number): Promise<any> {
        try {
            if (!this.products.last_page || page <= this.products.last_page!) {
                const response = await axios.post('product', {
                    func: 'grid',
                    per_page: 24,
                    current_page: page,
                    filters: this.filters.length > 0 ? this.filters: null
                });
                const data = response.data as IPageProducts;
                if (page == 1) {
                    this.setProducts(data);
                } else {
                    this.updateProducts(data)
                }
            }
        } catch (e) {

        }
    }


    @Action({rawError: true})
    public async addFilters(filters: {}): Promise<any> {
        this.setFilters(filters);
    }

}

export const ProductsListStore = getModule(ProductsListModule);
