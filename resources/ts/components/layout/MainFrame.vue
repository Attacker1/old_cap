<template>

  <Layout>

    <Layout :siderFixed="siderFixed" :siderCollapsed="siderCollapsed">

      <Sider theme="dark">
        <div class="layout-logo">
          <a class="sider-logo" href="/" v-if="!siderCollapsed">
            <img :src="`/assets-vuex/img/logo-capsula.png`"><span>Capsula</span>
          </a>
          <a class="sider-collapse" @click="siderCollapsed = !siderCollapsed">
            <i :class="siderCollapsed ? 'h-icon-right primary-color' : 'h-icon-left white-color' "></i>
          </a>
        </div>
        <AsideMenu :items="tempMenu" :siderCollapsed="siderCollapsed"/>
      </Sider>

      <Layout :headerFixed="headerFixed">

        <HHeader class="bg-gray-color">

          <Row :space-x="19" :space-y="5">

            <Cell width="10">
              <div class="header-left">
                <ButtonGroup size="s">
                  <Button v-if="user.role_id === 2" icon="h-icon-setting"
                          @click="permissionsAside = !permissionsAside"></Button>
                  <slot name="headerLeft"></slot>
                </ButtonGroup>
              </div>
            </Cell>

            <Cell width="4">
              <div class="header-center">
                <slot name="headerCenter">
                  <h3 class="white-color">{{ title }}</h3>
                </slot>
              </div>
            </Cell>

            <Cell width="10">
              <div class="header-right">
                <slot name="headerRight"></slot>
                <Button size="s" icon="h-icon-left"><a href="/">old</a></Button>

                <!--              <h-circle :percent="60" :stroke-width="8" :size="30"><i class="icon-paper-clip"></i></h-circle>-->
                <!--              <h-circle :percent="20" :stroke-width="8" :size="30" color="red"><i class="icon-printer"></i></h-circle>-->

                <HeaderMessage/>

                <DropdownMenu className="app-header-dropdown" trigger="hover" offset="0,5" :width="150"
                              placement="bottom-end" :datas="infoMenu">
                  <Avatar :src="`/assets-vuex/img/ava.jpg`" :width="30"><span>{{user.name}}.</span></Avatar>
                </DropdownMenu>
              </div>
            </Cell>

          </Row>

        </HHeader>

        <Content>
          <!--Основной контент-->
          <slot></slot>

        </Content>

      </Layout>

    </Layout>

    <Sider :siderFixed="true" class="manage" :class="{show : permissionsAside}">

      <div v-if="user.role_id === 2" class="manage-block">
        <FieldsManagement :namespace="namespace"/>
        <slot name="manage"></slot>
      </div>

    </Sider>

  </Layout>

</template>
<script lang="ts">
import {Component, Mixins, Prop, Vue, Watch} from "vue-property-decorator";
import {namespace} from "vuex-class";

import HeaderMessage from "./tpl/HeaderMessage.vue";
import AsideMenu from "./tpl/AsideMenu.vue";


import {ISettingsInit, ISideMenu, IUser, IUsersRoles} from "../../types/settings";
import FieldsManagement from "../settings/FieldsManagement.vue";


const StoreSettings = namespace('StoreSettings')

@Component({components: {FieldsManagement, HeaderMessage, AsideMenu}})
export default class MainFrame extends Vue {

  $refs!: {
    sideMenu: HTMLFormElement
  }

  @Prop({default: ''}) title!: string

  public namespace: string = ''



  @StoreSettings.State('user') user!: IUser
  @StoreSettings.State('server') server!: string
  @StoreSettings.State('sideMenu') sideMenu!: ISideMenu[]
  @StoreSettings.Action('fetchSideMenu') fetchSideMenu!: () => void
  @StoreSettings.Action('init') init!: (payload: ISettingsInit) => void

  public permissionsAside = false;

  //  @TODO-uretral:   До тех пор пока существует metronix потом перебить в базу -> table menu (что бы не создавать лишних таблиц)
  public tempMenu = [
    {
      title: 'Главная',
      key: 'admin.index',
      icon: 'h-icon-home',
      path: '/admin/v/index',
      nativeLink: true,
      children: []
    }, {
      title: 'Сделки',
      key: 'admin.lead',
      icon: 'icon-tag',
      path: '/admin/v/lead',
      nativeLink: true,
      children: this.menuChildFinder('admin.lead')
    }, {
      title: 'Сток',
      key: 'admin.stock',
      icon: 'icon-stack',
      path: '/admin/v/stock',
      children: this.menuChildFinder('admin.stock')
    },
    {
      title: 'Настройки разделов',
      key: 'admin.settings',
      icon: 'icon-layout',
      // path: '/admin/v/settings',
      children: [
        {
          title: 'Анкеты',
          key: 'admin.settings.anketas',
          icon: 'icon-shuffle',
          path: '/admin/v/settings/anketas',
          children: this.menuChildFinder('admin.settings.anketas')
        },
        {
          title: 'Еще что-то',
          key: 'admin.settings.anketas2',
          icon: '',
          path: '/admin/v/settings/anketas2',
          // children: i.hasOwnProperty('children') ? menu(i.children) : []
        },
      ]
    }, {
      title: 'Настройки системы',
      key: 'admin.system',
      icon: 'icon-cog',
      children: [{
        title: 'Меню',
        key: 'admin.system.menu',
        icon: 'icon-menu',
        path: '/admin/v/system/menu',
        children: []
      },]
    }

  ]
  public infoMenu = [
    {key: 'info', title: 'Работать', icon: 'h-icon-user'},
    {key: 'logout', title: 'Гулять', icon: 'h-icon-outbox'}
  ]


  public siderFixed = false
  public siderCollapsed = true
  public headerFixed = false
  public leftMenu: any[] = []

  menuChildFinder(key) {
    let node = this.$router.options.routes?.find(i => this.$route.name?.includes(key))
    if (node && node.children) {
      return node.children.reduce((arr: ISideMenu[], child) => {
        if (!child.path.includes(':')) {
          arr.push({
            title: child.meta?.title,
            // @ts-ignore
            key: child.name,
            icon: child.meta?.icon,
            path: child.path,
            // children: i.hasOwnProperty('children') ? menu(i.children) : []
          })
        }
        return arr

      }, [])
    } else {
      return []
    }

  }


  public menu: ISideMenu[] = []



  async created() {
    this.namespace =  this.$parent.$vnode.tag?.split('-').pop() ?? ''
    await this.init({
      namespace: namespace,
      role: this.user.role_id
    })
  }

}
</script>
