import {Action, getModule, Module, Mutation, VuexModule} from 'vuex-module-decorators';
import store from "../../index";
import Axios from "axios";
import HeyUI from "heyui";
import {ICart, IProducts} from "../../../types/stock";
import {eventBus} from "../../../bus";


const axios = Axios.create({
    baseURL: '/admin/vuex/stock'
});


@Module({
    namespaced: true,
    name: 'cart',
    store,
    dynamic: true,
})
class CartModule extends VuexModule {
    cart: ICart = {};
    orders: ICart[] = [];

    @Mutation
    private setCartProduct(cartProduct: IProducts) {
        if (this.cart.products) {
            this.cart.products?.push(cartProduct);
        } else {
            this.cart = {
                'expired_time': new Date(Date.now() + 7200000).toISOString(),
                'products': [cartProduct]
            }
        }
    }

    @Mutation
    private setOrders(orders: ICart[]) {
        this.orders = orders;
    }

    @Mutation
    private deleteCartProduct(product: IProducts) {
        if (this.cart.products && this.cart.products.length > 0) {
            const index = this.cart.products.findIndex(n => n.id === product.id);
            if (index !== -1) {
                this.cart.products.splice(index, 1);
            }
        }
    }

    @Mutation
    private setCart(cart: ICart) {
        this.cart = cart;
    }


    @Action({rawError: true})
    public async loadCartProducts(): Promise<any> {
        const response = await axios.post('cart', {
            func: 'fetch'
        });
        const data = response.data as ICart;
        this.setCart(data);
    }

    @Action({rawError: true})
    public async addCartProduct(product: IProducts): Promise<any> {
        try {
            const response = await axios.post('cart', {
                func: 'addProduct',
                product_id: product.id
            });
            if (response) {
                this.setCartProduct(product);
            }
        } catch (e: any) {
            // console.log(e.response.data.error.message);
            HeyUI.$Notice['success'](e.response.data.error.message);
        }
    }

    @Action({rawError: true})
    public async removeCartProduct(product: IProducts): Promise<any> {
        try {
            const response = await axios.post('cart', {
                func: 'deleteProduct',
                product_id: product.id
            });
            if (response) {
                this.deleteCartProduct(product);
            }
        } catch (e: any) {
            HeyUI.$Notice['success'](e.response.data.error.message);
        }
    }

    @Action({rawError: true})
    public async fetchOrders(): Promise<any> {
        try {
            const response = await axios.post('cart', {
                func: 'fetchAll'
            });
            if (response) {
                this.setOrders(response.data as ICart[]);
            }
        } catch (e: any) {
            HeyUI.$Notice['success'](e.response.data.error.message);
        }
    }
}

export const CartStore = getModule(CartModule);
