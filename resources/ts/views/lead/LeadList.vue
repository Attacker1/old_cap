<template>
  <MainFrame :title="$route.meta.title">
    <template v-slot:headerLeft>

        <template v-if="leadsSelected.length">

          <Button size="s">
            <Select :datas="leadStates" keyName="id" titleName="name" placeholder="Статус"
                    :null-option="false" :no-border="true" :equal-width="false" class="select-group"></Select>
          </Button>

          <Button icon="h-icon-inbox">Логсис</Button>

          <Button icon="h-icon-inbox">Возвраты Логсис</Button>
        </template>

    </template>


    <transition v-if="access(1, leads.data)" name="fade">

    <TableList :tableData="leads" :manage="manage(1)" @table-list-changed="fetchLeads">
<!--
      <template v-slot:thead-title-before>
        <th class="text-center checkbox">
          <div content="Дата"><span class="h-tag h-icon-check"></span></div>
        </th>
      </template>
      <template v-slot:thead-title-after></template>
      <template v-slot:thead-filter-before>
        <th class="text-center checkbox">
          <Checkbox v-model="selectAll" @change="selectLeads()"/>
        </th>
      </template>
      <template v-slot:thead-filter-after></template>
      <template v-slot:tbody-before>
        <td class="text-center checkbox">
          <Checkbox />
&lt;!&ndash;          :checked="leadsSelected.indexOf(lead.uuid) !== -1" @change="selectLead(lead.uuid)"&ndash;&gt;
        </td>
      </template>
      <template v-slot:tbody-after></template>-->
<!--
        <tr>
          <th class="text-center checkbox">
            <div content="Дата"><span class="h-tag h-icon-check"></span></div>
          </th>
          <th class="text-center">
            <div content="Дата"><span>Дата</span></div>
          </th>
          <th class="text-center">
            <div content="Клиент"><span>Телефон</span></div>
          </th>
          <th class="text-center">
            <div content="Клиент"><span>Email</span></div>
          </th>
          <th class="text-center">
            <div content="AMO ID"><span>AMO ID</span></div>
          </th>
          <th class="text-center">
            <div content="Клиент"><span>Ф.И.О.</span></div>
          </th>
          <th class="text-center">
            <div content="Дедлайн"><span>Дедлайн</span></div>
          </th>
          <th class="text-center">
            <div content="Статус"><span>Статус</span></div>
          </th>
          <th class="text-center">
            <div content="Теги"><span>Теги</span></div>
          </th>
          <th class="text-center manager">
            <div content="Теги"><span>Управление</span></div>
          </th>
        </tr>

        <tr class="table-filters">
          <th class="text-center checkbox">&lt;!&ndash;Дата&ndash;&gt;
            <Checkbox v-model="selectAll" @change="selectLeads()"/>
          </th>
          <th class="text-center">&lt;!&ndash;Дата&ndash;&gt;
            <DatePicker class="date" v-model="filters.byDate"/>
          </th>
          <th class="text-center">&lt;!&ndash;Телефон&ndash;&gt;
            <input style="width: 110px; text-indent: 10px;" type="text"
                   v-mask="'79999999999'" v-model.number="filters.byPhone">
          </th>
          <th class="text-center">&lt;!&ndash;Email&ndash;&gt;
            <input style="width: 110px; text-indent: 10px;" type="text"
                   v-mask="'79999999999'" v-model.number="filters.byPhone">
          </th>
          <th class="text-center">&lt;!&ndash;AMO ID&ndash;&gt;
            <input style="width: 110px; text-indent: 10px;" type="text"
                   v-mask="'9999999999'" v-model.number="filters.amoLike">
          </th>

          <th class="text-center">&lt;!&ndash;Ф.И.О.&ndash;&gt;
            <input type="text" v-model="filters.clientLike">
          </th>

          <th class="text-center">&lt;!&ndash;Дедлайн&ndash;&gt;
            <DatePicker class="datetime" type="datetime" v-model="filters.deadline"/>
          </th>
          <th class="text-center">&lt;!&ndash;Статус&ndash;&gt;
            <Select class="select-sm" v-model="filters.states" :datas="leadStates" keyName="id"
                    titleName="name"></Select>
          </th>
          <th class="text-center">&lt;!&ndash;Теги&ndash;&gt;
            <Select class="select-sm" v-model="filters.tags" :datas="leadTags" keyName="name" titleName="name"></Select>
          </th>
          <th class="text-center manager">
            <div content="Теги">
              <Button size="s" icon="h-icon-search" @click="clearFilter()">Очистить</Button>
            </div>
          </th>
        </tr>
        -->


<!--

      <template v-slot:tbody>
        <tr class="" v-for="lead in leads.data">
          <td class="text-center checkbox">
            <Checkbox :checked="leadsSelected.indexOf(lead.uuid) !== -1" @change="selectLead(lead.uuid)"/>
          </td>
          <td class="text-center">
            <DatePicker class="date" v-model="lead.created_at" :readonly="true" :disabled="true"/>
          </td>
          <td class="text-center">
            <span class="h-tag h-tag-green"
                  v-if="lead.lead_client && lead.lead_client.phone">{{ lead.lead_client.phone }}</span>
          </td>
          <td class="text-center">
            <span class="h-tag"
                  v-if="lead.lead_client && lead.lead_client.email">{{ lead.lead_client.email }}</span>
          </td>
          <td class="text-center"><span class="h-tag h-tag-yellow" v-if="lead['amo_lead_id']">{{ lead['amo_lead_id'] }}</span>
          </td>

          <td class="text-center">
                  <span class="h-tag" v-if="lead.lead_client">
                    <template v-if="lead.lead_client.name">{{ lead.lead_client.name }}</template>
                    <template v-if="lead.lead_client.patronymic">{{ lead.lead_client.patronymic }}</template>
                    <template v-if="lead.lead_client.second_name">{{ lead.lead_client.second_name }}</template>
                  </span>
          </td>

          <td class="text-center">
            <DatePicker v-if="lead.deadline_at" v-model="lead.deadline_at" type="datetime" :readonly="true"
                        :disabled="true"/>
          </td>
          <td class="text-center">
            <span class="h-tag h-tag-blue"
                  v-if="lead.lead_state && lead.lead_state.name">{{ lead.lead_state.name }}</span>
          </td>
          <td class="text-center">
                  <span class="h-tag" v-if="lead.lead_tag && lead.lead_tag.name"
                        :style="'background-color:' + lead.lead_tag.color +'; color: white' ">
                    {{ lead.lead_tag.name }}
                  </span>
          </td>

          <td class="text-center manager">
            <Button icon="h-icon-edit" :text="true" size="xs"
                    @click="$router.push({name: 'admin.lead.item', params: {uuid: lead.uuid} })"></Button>

            &lt;!&ndash;
            <Poptip content="Вы уверены? ;)" @confirm="deleteAndFetch(data.id)">
              <Button icon="h-icon-trash" :text="true" size="xs"></Button>
            </Poptip>
            &ndash;&gt;

          </td>

        </tr>
      </template>
-->

    </TableList>
    </transition>
  </MainFrame>
</template>

<script lang="ts">
import {Component, Mixins, Vue, Watch} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import MainFrame from "../../components/layout/MainFrame.vue";
import TableList from "../../components/layout/tpl/TableList.vue";
import {ILeadPaginate, ILeadTag, ILedState} from "../../types/lead";


import FieldsManagement from "../../components/settings/FieldsManagement.vue";
import rootMixin from "../../mixins/settings/rootMixin";
import serviceMixin from "../../mixins/settings/serviceMixin";
import manageMixin from "../../mixins/settings/manageMixin";

const StoreLead = namespace('StoreLead')
@Component({
  components: {FieldsManagement, MainFrame, TableList}
})
export default class LeadList extends Mixins(rootMixin, manageMixin, serviceMixin) {
  @StoreLead.State('leads') leads!: ILeadPaginate
  @StoreLead.State('leadStates') leadStates!: ILedState[]
  @StoreLead.State('leadTags') leadTags!: ILeadTag[]

  @StoreLead.Action('initList') initList!: (payload) => void
  @StoreLead.Action('fetchLeads') fetchLeads!: (payload) => void

  @Watch('leadsSelected') onLeadsSelectedChange(n, o) {
    this.selectAll = !!this.leadsSelected.length
  }


  public table = {
    created_at: {title: 'Дата'},
    client: {title: 'Клиент'},
    amo_lead_id: {title: 'AMO ID'},
    deadline_at: {title: 'Дедлайн'},
    states: {title: 'Статус'},
    tag: {title: 'Теги'},
  }

  public selectAll = false;
  public leadsSelected: string[] = []

  public filters = {
    byDate: '',
    byPhone: '',
    amoLike: '',
    deadline: '',
    clientLike: '',
    states: '',
    tags: '',
  }


  clearFilter() {
    Object.keys(this.filters).map((key, index) => {
      this.filters[key] = ''
    })
    this.leadsSelected = []
  }

  selectLead(uuid: string) {
    let index = this.leadsSelected.findIndex(i => i === uuid)
    console.log(index);
    index === -1
        ? this.leadsSelected.push(uuid)
        : this.leadsSelected.splice(index, 1)
  }

  selectLeads() {
    if (this.leadsSelected.length) {
      this.leadsSelected = []
    } else {
      this.leads.data?.map((i) => {
        this.leadsSelected.push(i.uuid)
      })
    }

  }


  async created() {
    await this.initList({per_page: 50})
    // this.pagination = {
    //   page: this.leads.current_page ?? this.pagination.page,
    //   size: this.leads.per_page ?? this.pagination.size,
    //   total: this.leads.total ?? this.pagination.total,
    // }

    this.$on('table-list-changed', (payload) => {

    })

    // @ts-ignore
    console.log('sdsdsd', this.$root.gUser, );

  }

}
</script>

<style>
.h-layout-footer {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  box-sizing: border-box;
  padding: 0px 20px 0px 0px;
  margin-top: 6px;
}

.h-page-total, .h-page-pager {
  color: #fff;
}

.h-page-jumper-input.h-input {
  text-align: center;
}

.h-table-header-table {
  color: #000;
}

.h-datetime {
  width: 160px;
  padding-left: 12px;
}

.h-input.h-datetime-show input {
  text-indent: 8px;
}

.date.h-datetime {
  width: 130px;
}

.table-filters {
  background-color: #999;
}

.table-filters th {
  opacity: 1;
}

.select-sm {
  margin: 0 auto;
  width: 180px
}

.manager {
  width: 120px;
  /*border-bottom: 1px solid red;*/

}

td.manager {

}

.checkbox {
  width: 50px;
}

.checkbox .h-tag {
  margin: 0;
}

.select-group {
  height: 10px;
}

.select-group .h-select-show {
  margin-top: -7px;
}
</style>
