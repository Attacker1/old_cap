import {Action, getModule, Module, Mutation, VuexModule} from 'vuex-module-decorators';
import store from "../../index";
import Axios from "axios";
import {IAttributes, IProducts} from "../../../types/stock";


const axios = Axios.create({
    baseURL: '/admin/vuex/stock'
});


@Module({
    namespaced: true,
    name: 'attributes',
    store,
    dynamic: true,
})
class AttributeModule extends VuexModule {
    attributes: IAttributes[] = [];

    @Mutation
    private setAttributes(attributes: IAttributes[]) {
        this.attributes = attributes;
    }

    @Action
    public async resetAttributes() {
        this.setAttributes([]);
    }


    @Action({rawError: true})
    public async loadAttributes(): Promise<any> {
        const response = await axios.post('attribute', {
            func: 'fetch'
        });
        const data = response.data as IAttributes[];
        this.setAttributes(data);
    }
}

export const AttributeStore = getModule(AttributeModule);
