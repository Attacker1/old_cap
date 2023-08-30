<template>
  <div>
    <div class="h-table h-table-stripe">
      <div class="h-table-header" style="padding-right: 15px;">
        <table class="h-table-header-table">
          <tr v-if="titleRow">
            <slot name="thead-title-before"></slot>
            <template v-if="manage.items" v-for="(manageItem,key) in manage.items"> <!--:key="key"-->
              <th :class="manageItem.position" v-if="accessItem(manageItem)">
                <div>
                  <span>{{ manageItem.title }}</span>

                  <span v-if="manageItem.sortable">
                  &nbsp;
                  <a href="javascript:" class="h-icon-top"
                     :class="callbackObject.orderBy === manageItem.key && callbackObject.orderDirection === 'asc' ? 'red-color' : 'gray-color'"
                     @click="sortAsc(manageItem)"/>
                  <a href="javascript:" class="h-icon-down"
                     :class="callbackObject.orderBy === manageItem.key &&  callbackObject.orderDirection === 'desc' ? 'red-color' : 'gray-color'"
                     @click="sortDesc(manageItem)"/>
                </span>

                </div>
              </th>
            </template>
            <slot name="thead-title-after"></slot>
          </tr>

          <tr v-if="filterRow" class="table-filters">
            <slot name="thead-filter-before"></slot>
            <template v-if="manage.items" v-for="(manageItem,key) in manage.items">
              <th :class="manageItem.position" v-if="accessItem(manageItem)">
                <DatePicker class="date" v-if="manageItem.input_type === 'DatePicker'"/>
                <input v-else type="text">
              </th>
            </template>
            <slot name="thead-filter-after"></slot>
          </tr>

        </table>
        <div class="h-table-fixed-cover" style="width: 15px;"></div>
      </div>

      <div class="h-table-container">
        <div class="relative">
          <div class="h-table-body" style="height: calc(100vh - 210px)">
            <table class="h-table-body-table" style="">
              <tbody class="h-table-tbody">


              <tr v-for="item in items">
                <slot name="tbody-before"></slot>
                <td v-for="(manageItem,key) in manage.items" v-if="manage.items && accessItem(manageItem)"
                    :class="manageItem.position">

                  <ViewTag v-if="manageItem.view_type === 'ViewTag'" :title="showValues(item,manageItem)"
                           :class-name="manageItem.view_class"/>

                  <DatePicker v-else-if="manageItem.view_type === 'DatePicker'" class="date"
                              :value="showValues(item,manageItem)" :readonly="true" :disabled="true"/>


                  <template v-else>{{ showValues(item, manageItem) }}</template>

                </td>
                <slot name="tbody-after"></slot>
              </tr>


              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="h-table-items">
        <div></div>
      </div>
    </div>
    <HFooter class="bg-gray-color" v-if="pagination">
      <Pagination v-model="pagination" align="right" @change="paginate"></Pagination>
    </HFooter>
  </div>

</template>

<script lang="ts">
import {Component, Emit, Mixins, Prop, Vue} from 'vue-property-decorator'
import {IFormItem, IManageBlock, IPagination} from "../../../types/settings";
import {ILeadPaginate} from "../../../types/lead";
import ViewTag from "./elements/ViewTag.vue";
import manageMixin from "../../../mixins/settings/manageMixin";

@Component({
  components: {ViewTag}
})
export default class TableList extends Mixins(manageMixin) {

  @Prop({default: true}) titleRow
  @Prop({default: true}) filterRow
  @Prop() tableData!: ILeadPaginate
  @Prop() manage!: IManageBlock

  emitCallbackObject() {
    this.$emit('table-list-changed', this.callbackObject)
  }

  get items() {
    if (this.tableData.hasOwnProperty('current_page') && this.tableData.hasOwnProperty('data')) {
      this.pagination = {
        page: this.tableData.current_page,
        size: this.tableData.per_page,
        total: this.tableData.total,
      }
      this.callbackObject['page'] = this.pagination.page
      this.callbackObject['per_page'] = this.pagination.size
      return this.tableData.data
    } else {
      return this.tableData
    }
  }

  public callbackObject = {
    orderBy: 'created_at',
    orderDirection: 'desc',
  }

  public pagination: IPagination = {}

  paginate() {
    this.callbackObject['page'] = this.pagination.page
    this.callbackObject['per_page'] = this.pagination.size
    this.emitCallbackObject()
  }


  sortAsc(manageItem) {
    this.callbackObject['orderBy'] = manageItem.key
    this.callbackObject['orderDirection'] = 'asc'
    this.emitCallbackObject()
  }

  sortDesc(manageItem) {
    this.callbackObject['orderBy'] = manageItem.key
    this.callbackObject['orderDirection'] = 'desc'
    this.emitCallbackObject()
  }

  showValues(item, manage): string {
    let slugs = manage.key.split('.'),
        lastKey = slugs.pop(),
        obj = {}, val

    slugs.forEach((i) => {
      obj = Object.keys(obj).length === 0 ? item[i] : obj[i]
    })

    val = slugs.length ? obj[lastKey]: item[lastKey]
    return val ? val.toString() : ''
  }

}
</script>
