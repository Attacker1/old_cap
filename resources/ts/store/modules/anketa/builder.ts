import {Module, Mutation, VuexModule, Action, MutationAction} from "vuex-module-decorators";
import Axios from "axios";
import {IAnketaBuilder, IQuestion, IUpdateLists} from "../../../types/anketa";

const axios = Axios.create({
    baseURL: '/admin/vuex/anketa'
});

@Module({namespaced: true})


export default class StoreAnketaBuilder extends VuexModule {

    public grid: IAnketaBuilder[] = []
    @Mutation setGrid(payload: IAnketaBuilder[]) {this.grid = payload}

    public tft : string = 'Y'
    @Mutation setTft(payload: string) {
        this.tft = payload
    }


/*C anketaBuilder*/
    @Action({commit: 'setGrid'}) async createAndFetch(payload: IAnketaBuilder) {
        const response = await axios.post('/builder', {func:'createAndFetch', item: payload})
        return await response.data;
    }

/*R anketaBuilder*/
    @Action({commit: 'setGrid'}) async fetchGrid() {
        const response = await axios.post('/builder', {func:'grid'})
        return await response.data;
    }

/*U anketaBuilder*/
    @Action({commit: 'setGrid'}) async updateAndFetch(payload: IAnketaBuilder) {
        const response = await axios.post('/builder', {
            func:'updateAndFetch',
            id: payload.id,
            item: payload
        })
        return await response.data;
    }

/*D anketaBuilder*/
    @Action({commit: 'setGrid'}) async deleteAndFetch(payload: number) {
        const response = await axios.post('/builder', {func:'deleteAndFetch', id: payload})
        return await response.data;
    }

/*MIXED*/
    @Action({commit: 'setGrid'}) async makeDefault(payload: number) {
        const response = await axios.post('/builder', {func:'makeDefault', id: payload})
        return await response.data;
    }


    public anketaList: IQuestion[] = []
    @Mutation setAnketaList(payload: IQuestion[]) {this.anketaList = payload}

    public questionsList: IQuestion[] = []
    @Mutation setQuestionsList(payload: IQuestion[]) {this.questionsList = payload}

    @MutationAction({mutate: ['anketaList', 'questionsList']})  async initBuilderData(payload: string) {
        const responce = await axios.post('questions', {
            func: 'getBuilderData',
            anketaSlug: payload
        })
        return responce.data
    }


    @MutationAction({mutate: ['anketaList', 'questionsList']}) async updateBuilderData(payload: IUpdateLists) {
        const response = await axios.post('questions', {
            func: 'updateBuilderData',
            anketaSlug: payload.curentVariantSlug,
            anketaList: payload.anketaList
        })
        return response.data
    }


    @Mutation moveToEnd(index: number) {
        let current = this.questionsList.splice(index,1)
        if(current){
            this.anketaList.push(current[0])
        }
    }
}

