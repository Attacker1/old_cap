<template>
  <Modal v-model="open" type="drawer-left">
    <div class="h-panel manage-form" v-if="open">
      <div class="h-panel-bar">
        <span v-if="editItem" class="h-panel-title">Редактировать блок</span>
        <span v-else class="h-panel-title">Добавить новый блок</span>
        <span v-if="editItem" class="h-panel-right"><i class="h-icon-edit primary-color"></i></span>
        <span v-else class="h-panel-right"><i class="h-icon-plus primary-color"></i></span>
      </div>
      <div class="h-panel-body" >

        <QuickForm :items="formFields" :labelWidth="150" @submit="submit" :exclude="editItem ? [] : ['id']" @cancel="open = false"/>

      </div>
    </div>
  </Modal>
</template>

<script lang="ts">
import {Component, Mixins, Prop, Vue} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import {IFormItem, IManageBlock, IUsersRoles} from "../../types/settings";
import QuickForm from "../layout/form/QuickForm.vue";
import manageMixin from "../../mixins/settings/manageMixin";
import {eventBus} from "../../bus";

@Component({
  components: {QuickForm}
})
export default class ManageBlock extends Mixins(manageMixin) {


  public formFields: IFormItem[] = [
    {
      key: 'id',
      sort: 10,
      title: 'id',
      tooltip: ``,
      input_type: 'text',
      input_value: null,
      input_disabled: true,
      input_rules: {},
    },
    {
      key: 'namespace',
      sort: 10,
      title: 'namespace',
      tooltip: ``,
      input_type: 'text',
      input_value: this.namespace,
      input_disabled: true,
      input_rules: {
        required: true
      },
    }, {
      key: 'slug',
      sort: 20,
      title: 'Слаг блока',
      tooltip: `Только буквы латинского алфавита, уникальный в рамках данного namespace`,
      input_type: 'text',
      input_value: '',
      input_rules: {
        required: true,
        custom: {
          validAsync(value, next, parent, data) {
            setTimeout(() => {
              console.log(/[а-я]/i.test(value));
              if (/[a-z]/i.test(value)) {
                next();
              } else {
                next('Только буквы латинского алфавита');
              }
            }, 250);
          }
        }
      },
    }, {
      key: 'title',
      sort: 30,
      title: 'Название',
      tooltip: ``,
      input_type: 'text',
      input_value: '',
      input_rules: {
        required: true
      },
    }, {
      key: 'type',
      sort: 30,
      title: 'Тип',
      tooltip: ``,
      input_type: 'select',
      input_list: [{
        id: 'block',
        name: 'Блок'
      }, {
        id: 'list',
        name: 'Список'
      }],
      input_value: 'block',
      input_rules: {
        required: true
      },
    }, {
      key: 'roles',
      sort: 40,
      title: 'Роли',
      tooltip: ``,
      input_type: 'checkboxGroup',
      input_value: [2],
      input_list: this.roles,
      input_disabled: true,
      input_rules: {
        required: true
      },
    },
    {
      key: 'sort',
      sort: 40,
      title: 'Сортировка',
      tooltip: `Сортировка блока в списке`,
      input_type: 'number',
      input_value: 500,
      input_rules: {
        required: true
      },
    },
    {
      key: 'tooltip',
      sort: 40,
      title: 'Подсказка',
      tooltip: `Подсказка на случай если блок или список имеет свои особенности`,
      input_type: 'textarea',
      input_value: '',
      input_rules: {},
    }

  ]

  mounted() {
    this.editItem = 0




    eventBus.$on('manage-block-edit', (item: IManageBlock) => {
      this.putInputList({obj:this.formFields, key: 'roles', value: this.roles})

      if (item) {
        this.editItem = item.id
        this.formFields.map(i => {
          if (item.hasOwnProperty(i.key)) {
            i.input_value = item[i.key]
          }
        })
      }

      this.open = !this.open

    })

    eventBus.$on('manage-block-open', () => {
      this.putInputValue({obj:this.formFields, key: 'roles', value: this.roles})
      this.putInputValue({obj:this.formFields, key: 'namespace', value: this.namespace})
      console.log('nnnn', this.namespace);
      this.open = !this.open
    })
  }

}
</script>
