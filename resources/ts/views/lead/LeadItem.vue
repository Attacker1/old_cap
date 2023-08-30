<template>
  <MainFrame :title="$route.meta.title + leadAmoID" namespace="">

<!--    <template v-slot:headerLeft>

        <Button icon="h-icon-left" @click="$router.push({name: 'admin.lead.list'})">Список</Button>

    </template>



    <div class="scrolling-frame">
      <div class="h-panel" >
        <div class="h-panel-bar">
          Для формирования ссылки на оплату нет след. данных: <span class="h-tag h-tag-red">Cуммы</span>

          <div class="h-panel-right">
            <Button color="primary" v-tooltip content="Perform asynchronous verification but do not wait for results">
              Сохранить
            </Button>
          </div>
        </div>

        <div class="h-panel-body">
          <Form v-if="lead" :label-width="150" mode="threecolumn" :model="lead" :rules="formData.validation" ref="form"
                :top="0.2" :showErrorTip="true">
            <FormItem label="Стилист:" prop="stylist_id">
              <Select v-if="usersByRoles.stylist" v-model="lead.stylist_id" :datas="usersByRoles.stylist" keyName="id"
                      titleName="name" :single="true"/>
            </FormItem>
            <FormItem label="Статус:" prop="state">
              <Select v-if="leadStates" v-model="lead.state_id" :datas="leadStates" keyName="id" titleName="name"
                      :single="true"/>
            </FormItem>
            <FormItem label="Amo ID:" prop="amo_lead_id">
              <input type="text" v-model.number="lead.amo_lead_id">
            </FormItem>
            <FormItem label="Дедлайн:" prop="deadline_at">
              <DatePicker class="datetime" type="datetime" v-model="lead.deadline_at"/>
            </FormItem>
            <FormItem label="Сумма:" prop="summ">
              <NumberInput v-model.number="lead.summ" :useOperate="true"/>
            </FormItem>
            <FormItem label="total:" prop="total">
              <NumberInput v-model.number="lead.total" :useOperate="true"/>
            </FormItem>
            <FormItem label="discount:" prop="discount">
              <NumberInput v-model.number="lead.discount" :useOperate="true"/>
            </FormItem>
            <FormItem label="Купон:" prop="data.coupon">
              <input type="text" v-model="lead.data.coupon">
            </FormItem>
            <FormItem label="Город:" prop="data.city_delivery">
              <input type="text" v-model="lead.data.city_delivery">
            </FormItem>
            <FormItem label="Адрес:" prop="has_delivery.delivery_address">
              <input type="text" v-model="lead.has_delivery.delivery_address">
            </FormItem>
            <FormItem label="Адрес возврата:" prop="has_delivery.delivery_address">
              <input type="text" v-model="lead.has_delivery.delivery_address">
            </FormItem>
            <FormItem label="Дата доставки:" prop="delivery_at">
              <DatePicker class="datetime" type="datetime" v-model="lead.delivery_at"/>
            </FormItem>
            <FormItem label="Интервал доставки:" prop="">
              <Select v-if="deliveryIntervals" v-model="lead.questionnaire['deliveryTime']" :datas="deliveryIntervals"
                      keyName="id" titleName="text" :single="true"/>
            </FormItem>
            <FormItem label="Дата возврата:" prop=""></FormItem>
            <FormItem label="Интервал возврата:" prop=""></FormItem>
            <FormItem label="Доп. заметки" :single="true" prop="textareaData">
              <textarea rows="3" v-autosize  placeholder="Доп инфо"></textarea>
            </FormItem>
          </Form>
        </div>
        <div class="h-panel-bar">
          История изменений
        </div>
        <div class="h-panel-body">
          <div class="system-log">
            <div class="system-log-content" :class="{opened : syslog}">
              <span class="h-tag h-tag-gray h-icon-bell"> 08.02.2022 16:54</span> <span class="h-tag h-tag-green">Система: исправил "created_at" с  на 2022-02-08 16:54:23</span> <br/>
              <span class="h-tag h-tag-gray h-icon-bell"> 08.02.2022 16:54</span> <span class="h-tag h-tag-yellow">Система: исправил "AMO ID" с <strong></strong> на <strong>30854027</strong></span> <br/>
              <span class="h-tag h-tag-gray h-icon-bell"> 08.02.2022 16:54</span> <span class="h-tag h-tag-yellow">Система: исправил "ID статуса" с Заполненная анкета на Анкета оплачена</span> <br/>
              <span class="h-tag h-tag-gray h-icon-bell"> 08.02.2022 16:55</span> <span class="h-tag">Наталья Потевская: исправил "ID стилиста" с  на 5</span> <br/>
              <span class="h-tag h-tag-gray h-icon-bell"> 08.02.2022 16:55</span> <span class="h-tag">Наталья Потевская: исправил "ID статуса" с Анкета оплачена на Анкета у стилиста</span> <br/>
              <span class="h-tag h-tag-gray h-icon-bell"> 08.02.2022 16:59</span> <span class="h-tag">Стилист Тест Пользователь: исправил "ID статуса" с Анкета у стилиста на Подборка составлена</span> <br/>
              <span class="h-tag h-tag-gray h-icon-bell"> 08.02.2022 17:00</span> <span class="h-tag">Наталья Потевская: исправил "ID статуса" с Подборка составлена на Одежда у клиента</span> <br/>
              <span class="h-tag h-icon-bell"> 08.02.2022 21:44</span> <span class="h-tag">Dmitry Zyuzin: исправил "ID статуса" с ОС отправлена на Сделка закрыта</span> <br/>
            </div>

            <Button size="xs" color="gray system-log-toggle" :icon="syslog ? 'h-icon-top': 'h-icon-down'"  @click="syslog = !syslog"/>
          </div>


          &lt;!&ndash;        <textarea rows="3" v-autosize  placeholder="Доп инфо"></textarea>&ndash;&gt;
        </div>
        <br v-for="i in 30"/>
      </div>
    </div>

    -->
  </MainFrame>

</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import MainFrame from "../../components/layout/MainFrame.vue";
import {ILead, ILeadPaginate, ILeadTag, ILedState} from "../../types/lead";
import {IUsersByRoles} from "../../types/settings";
import {IQuestionOption} from "../../types/anketa";
import {routeRootName} from "../../lead";

const StoreLead = namespace('StoreLead')
const StoreSettings = namespace('StoreSettings')
@Component({
  components: {MainFrame}
})
export default class LeadItem extends Vue {
  click() {
    console.log(this.$route)
  }

  @StoreLead.State('lead') lead!: ILead
  @StoreLead.State('leadStates') leadStates!: ILedState[]
  @StoreLead.State('leadTags') leadTags!: ILeadTag[]
  @StoreLead.State('deliveryIntervals') deliveryIntervals!: IQuestionOption[]
  @StoreLead.State('deliveryBackIntervals') deliveryBackIntervals!: IQuestionOption[]
  @StoreLead.Action('initLead') initLead!: (payload: string) => void

  @StoreSettings.State('usersByRoles') usersByRoles!: IUsersByRoles
  @StoreSettings.Action('fetchUsersByRoles') fetchUsersByRoles!: () => void


  public leadAmoID: number = 0

  public formData = {
    mode: 'threecolumn',
    validation: {
      rules: {},
      required: []
    }
  }


  public deliveryTime: string = ''
  public deliveryBackTime: string = ''
  public syslog = false

  /*{{lead.questionnaire.anketa['deliveryTime']}} 001645e1-a9db-4e9f-8e51-8176f3be5f79*/

  async initVars() {
    if (this.lead.questionnaire.anketa) {
      let anketa = this.lead.questionnaire.anketa
      Object.keys(anketa).map((i) => {
        this.deliveryTime = i == 'deliveryTime' && anketa[i].length ? anketa[i] : null
        this.deliveryTime = i == 'deliveryBackTime' && anketa[i].length ? anketa[i] : null

        console.log(anketa[i], i);

      })
    }

  }

  async created() {
    // await this.initLead(this.$route.params.uuid)
    // this.fetchUsersByRoles()
    // this.leadAmoID = this.lead?.amo_lead_id ?? ''
    // await this.initVars()
  }
}
</script>

<style>
.h-form-item-label {
  padding: 8.5px 15px 8.5px 0 !important;
  /*background: #c1c1c1;*/
  /*color: #3b91ff !important;*/
}
.scrolling-frame {
  height: calc(100vh - 50px);
  padding: 0;
  margin: 0;
  overflow-y: auto;
}
.system-log {
  position: relative;
}
.system-log-content {
  font-size: 12px;
  max-height: 40px;
  overflow-y: auto;
  padding: 10px 0;
  border: 1px solid #eee;
  opacity: .5;


}
.system-log-content.opened {
  max-height: unset;
  opacity: unset;
}
.system-log-content span {
  margin-bottom: 2px;
}
.system-log-toggle {
  position: absolute;
  bottom: -8px;
  left: calc(50% - 14px);
}
</style>
