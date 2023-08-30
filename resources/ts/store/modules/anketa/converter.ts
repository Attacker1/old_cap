import {VuexModule, Module, Mutation, Action, MutationAction} from 'vuex-module-decorators'
import Axios from "axios";

const axios = Axios.create({
    baseURL: '/admin/vuex/anketa/questions-converter'
});
@Module({
    namespaced: true
})
export default class StoreAnketaConverter extends VuexModule {
// initials
    public skipQuest: number = 0
    public takeQuest: number = 100
    public questData = []
    public count: number = 0

    @Mutation setQuestData(payload: []) {
        this.questData = payload
    }

    @Mutation setSkipQuest(payload: number) {
        this.skipQuest = payload
    }

    @Mutation setTakeQuest(payload: number) {
        this.takeQuest = payload
    }

    @Mutation setCount(payload: number) {
        this.count = payload
    }

    public convertedAnswers: {} = {}

    @Mutation setConvertedAnswers(payload: any) {
        if(this.convertedAnswers.hasOwnProperty(payload.uuid)){
            this.convertedAnswers[payload.uuid][payload.slug] = payload.value
        } else {
            this.convertedAnswers[payload.uuid] = {}
            this.convertedAnswers[payload.uuid][payload.slug] = payload.value
        }

        // console.log('ff',payload);
        // this.convertedAnswers[payload.uuid] = '44'
        // this.convertedAnswers[payload.uuid][payload.slug] = payload.value
        // if (this.convertedAnswers[payload.uuid]) {
        //     // @ts-ignore
        //     this.convertedAnswers[payload.uuid][payload.slug] = payload.value
        // } else {
        //     // @ts-ignore
        //     this.convertedAnswers[payload.uuid] = []
        //     // @ts-ignore
        //     this.convertedAnswers[payload.uuid].push(payload.anketa)
        // }

    }

    @MutationAction({mutate: ['questData', 'skipQuest', 'takeQuest', 'count', 'convertedAnswers']})
    async getQuestData(payload) {
        const response = await axios.post('', {
            func: 'getQuestData',
            skip: payload.skip,
            take: payload.take,
            convertedAnswers: payload.convertedAnswers,
        })
        return await response.data;
    }

    @MutationAction({mutate: ['skipQuest', 'takeQuest', 'count']})
    async getParse(payload) {
        const response = await axios.post('', {
            func: 'getParse',
            skip: payload.skip,
            take: payload.take,
            convertedAnswers: payload.convertedAnswers,
        })
        return await response.data;
    }

    //
    public questDataVariants = []

    @Mutation setInitQuestDataVariants(payload) {
        this.questDataVariants = payload
    }

    @Mutation setQuestDataVariants(payload) {
        // console.log('jjjjjjjjjjjj', payload);
        if (this.questDataVariants[payload.key]) {
            // @ts-ignore
            this.questDataVariants[payload.key].push(payload.data)
        } else {
            // @ts-ignore
            this.questDataVariants[payload.key] = []
            // @ts-ignore
            this.questDataVariants[payload.key].push(payload.data)
        }


    }



    @Mutation newConvertedAnswers() {
        this.convertedAnswers = []
    }



// @Action


}
