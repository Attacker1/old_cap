import {Module, Mutation, VuexModule, Action, MutationAction} from "vuex-module-decorators";
import Axios from "axios";
import {IQuestion, IQuestionType} from "../../../types/anketa";
import {eventBus} from "../../../bus";

const axios = Axios.create({
    baseURL: '/admin/vuex/anketa'
});

function emitSuccess(res){
    if(res.status >= 200 && res.status <= 299  ) {
        eventBus.$emit('actionSuccess','Сохранено 😋')
        return res.data
    }
}
function emitError(error) {
    console.log('error', error);
    eventBus.$emit('actionError', error.message)
}

@Module({namespaced: true})


export default class StoreAnketaQuestion extends VuexModule {


    public grid: IQuestion[] = []
    @Mutation setGrid(payload: IQuestion[]){
        this.grid = payload
    }

    public questionTypes: IQuestionType[] = []
    @Mutation setQuestionTypes(payload: IQuestionType[]){
        this.questionTypes = payload
    }

    @MutationAction({mutate: ['grid','questionTypes']}) async fetchQuestionsList() {
       const response = await axios.post('question-manager',{
            func: 'fetchQuestionsList'
        })
        return response.data
    }


    @MutationAction({mutate: ['grid']}) async fetchUpdateQuestion(payload: IQuestion) {
      return  await axios.post('question-manager',{
            func: 'fetchUpdateQuestion',
            id: payload.id,
            question: payload
        }).then(response => emitSuccess(response)).catch(error => emitError(error))
    }


    @MutationAction({mutate: ['grid']}) async fetchCreateQuestion(payload) {
        const response = await axios.post('question-manager',{
            func: 'fetchCreateQuestion',
            question: payload
        })
        return response.data
    }
// options
    @MutationAction({mutate: ['grid']}) async fetchCreateQuestionOption(payload) {
        const response = await axios.post('question-manager',{
            func: 'fetchCreateQuestionOption',
            questionOption: payload
        })
        return response.data
    }


}

