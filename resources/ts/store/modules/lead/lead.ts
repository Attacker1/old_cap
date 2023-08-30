import {Module, Mutation, VuexModule, Action, MutationAction} from "vuex-module-decorators";
import {ILeadPaginate, ILeadTag, ILedState} from "../../../types/lead";
import {IQuestionOption} from "../../../types/anketa";

const baseUrl = '/admin/vuex/lead'

import {emitResponseLoad} from "../../../mixins/vuex/vuex.mixin";

@Module({
    namespaced: true
})

export default class StoreLead extends VuexModule {

    public server: string = process.env.NODE_ENV === 'production' ? '' : 'http://stage-0.thecapsula.loc'

    public lead: string = ''
    public leadStates: ILedState[] = []
    public leadTags: ILeadTag[] = []
    public leads: ILeadPaginate = {}
    public deliveryIntervals: IQuestionOption[] = []
    public deliveryBackIntervals: IQuestionOption[] = []
    public leadFilters = {}


    @MutationAction({mutate: ['leads','leadStates','leadTags']}) async initList(payload) {
        return await emitResponseLoad({
            url: baseUrl,
            payload: {
                func: 'initList',
                ...payload
            }
        });
    }

    @Mutation setLeads(payload) {this.leads = payload}
    @Action({commit: 'setLeads'}) async fetchLeads(payload) {
        return await emitResponseLoad({
            url: baseUrl,
            payload: {
                func: 'fetchLeads',
                ...payload
            }
        })
    }

    @MutationAction({mutate: ['lead','leadStates','leadTags','deliveryIntervals','deliveryBackIntervals']}) async initLead(payload) {
        return  await emitResponseLoad({
            url: baseUrl,
            payload: {
                func: 'initLead',
                uuid: payload,
            }
        })
    }

}
