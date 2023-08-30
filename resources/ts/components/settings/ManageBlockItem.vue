<template>
  <Modal v-model="open" type="drawer-left" >
    <div class="h-panel manage-form" v-if="open" style="width: 1000px;">
      <div class="h-panel-bar">

        <span v-if="editItem" class="h-panel-title">Редактировать поле</span>
        <span v-else class="h-panel-title">Добавить новое поле</span>
        <span v-if="editItem" class="h-panel-right"><i class="h-icon-edit primary-color"></i></span>
        <span v-else class="h-panel-right"><i class="h-icon-plus primary-color"></i></span>
      </div>
      <div class="h-panel-body">

        <QuickForm :items="formFields" :labelWidth="160" :exclude="editItem ? [] : ['id']"
                   @submit="submitItem" @cancel="open = false" mode="twocolumn"/>

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
export default class ManageBlockItem extends Mixins(manageMixin) {

  /*
  Пример кастомного правила валидации
   */
  input_rules_sample = {
    custom: {
      validAsync(value, next, parent, data) {
        setTimeout(() => {
          if (/[a-z._]/i.test(value)) {
            next();
          } else {
            next('Только буквы латинского алфавита и точки');
          }
        }, 250);
      }
    }
  }


  public formFields: IFormItem[] = [
      /*Основные*/
    {
      key: 'id',
      title: 'id',
      input_type: 'text',
      input_value: null,
      input_disabled: true,
      legend: 'Значения'
    },
    {
      key: 'block_id',
      title: 'Родитель (id)',
      input_type: 'text',
      input_value: null,
      input_disabled:true,
      input_rules: {
        required: true
      },
    },
    {
      key: 'key',
      title: 'Ключ поля',
      // tooltip: `Только буквы латинского алфавита, подчерк и точки <span class="h-tag-bg-red">parent.child.child_of_child</span>`,
      input_type: 'SlugPicker',
      input_value: '',
      input_rules: {
        required: true,

      },
    },
    {
      key: 'input_type',
      title: 'Тип поля',
      input_type: 'select',
      tooltip: `Для таблицы служит полем фильтра`,
      input_list: [
        {id: 'text', name: 'text'},
        {id: 'select', name: 'select'},
        {id: 'number', name: 'number'},
        {id: 'textarea', name: 'textarea'},
        {id: 'checkboxGroup', name: 'checkboxGroup'},
        {id: 'checkbox', name: 'checkbox'},
        {id: 'DatePicker', name: 'DatePicker'},
      ],
      input_value: 'text',
      input_rules: {
        required: true
      },
    },
    {
      key: 'title',
      title: 'Заголовок',
      input_type: 'text',
      input_value: '',
      input_rules: {
        required: true
      },
    },
    {
      key: 'sort',
      title: 'Порядок',
      tooltip: `Сортировка блока в списке`,
      input_type: 'number',
      input_value: 99,
      input_rules: {
        required: true
      },
    },
    {
      key: 'roles',
      title: 'Роли',
      input_type: 'checkboxGroup',
      input_value: [2],
      input_list: this.roles,
      input_disabled: true,
      input_rules: {
        required: true
      },
    },
    {
      key: 'input_value',
      title: 'По умолчанию',
      tooltip: `Значение по умолчанию`,
      input_type: 'text',
      input_value: '',
    },
      /*Действия*/
    {
      key: 'input_rules',
      title: 'Правила валидации',
      input_type: 'textarea',
      input_value: '',
      legend: 'Действия'
    },
    {
      key: 'filter',
      sort: 40,
      title: 'Название фильтра',
      tooltip: `Название метода фильтра`,
      input_type: 'text',
      input_value: '',
      input_rules: {},
    },
    {
      key: 'sortable',
      sort: 40,
      title: 'Сортировка',
      tooltip: ``,
      input_type: 'checkbox',
      input_value: 0,
      input_rules: {},
    },
    {
      key: 'input_mask',
      sort: 40,
      title: 'Маска ввода',
      tooltip: `например маска ввода телефона v-mask="'79999999999'" где 9 символ подстановки  https://github.com/probil/v-mask` ,
      input_type: 'text',
      input_value: '',
      input_rules: {},
    },
      /*оформление*/


    {
      key: 'position',
      title: 'Позиция заголовка',
      input_type: 'select',
      input_value: 'text-center',
      input_rules: {
        required: true
      },
      input_list: [{
        id: 'text-left',
        name: 'Слева'
      }, {
        id: 'text-center',
        name: 'По центру'
      }, {
        id: 'text-right',
        name: 'Справа'
      }],
      legend: 'Оформление'
    },
    {
      key: 'tooltip',
      title: 'Подсказка',
      tooltip: `Подсказка на случай если блок или список имеет свои особенности`,
      input_type: 'textarea',
      input_value: '',
    },

    {
      key: 'disabled',
      sort: 40,
      title: 'Деактивировать',
      tooltip: ``,
      input_type: 'checkbox',
      input_value: 0,
      input_rules: {},
    },
    {
      key: 'view_class',
      sort: 40,
      title: 'Добавить class',
      tooltip: ``,
      input_type: 'text',
      input_value: '',
      input_rules: {},
    },
    {
      key: 'view_type',
      sort: 40,
      title: 'Тип отображения',
      tooltip: ``,
      input_type: 'select',
      input_value: '',
      input_rules: {},
      input_list: [{
        id: 'ViewTag',
        name: 'ViewTag'
      },{
        id: 'DatePicker',
        name: 'DatePicker'
      },]
    },


/*
    {
      key: '',
      sort: 40,
      title: '',
      tooltip: ``,
      input_type: 'textarea',
      input_value: '',
      input_rules: {},
    }*/
  ]

  created() {
    this.open = false

    this.editItem = 0

    console.log(this.obList);

    this.putInputValue({
      obj:this.formFields,
      key: 'namespace',
      value: this.roles
    })



    eventBus.$on('manage-block-item-edit', (item: IManageBlock) => {
      this.putInputList({
        obj:this.formFields,
        key: 'roles',
        value: this.roles
      })
      this.putInputList({
        obj:this.formFields,
        key: 'key',
        value: this.obList['ob1']
      })
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

    eventBus.$on('manage-block-item-open', (item: IManageBlock) => {
      this.putInputValue({
        obj:this.formFields,
        key: 'block_id',
        value: item.id
      })
      this.putInputList({
        obj:this.formFields,
        key: 'roles',
        value: this.roles
      })



      this.open = !this.open
    })
  }

}
</script>
