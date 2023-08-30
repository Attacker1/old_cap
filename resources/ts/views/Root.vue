<template>
  <router-view></router-view>
</template>

<script lang="ts">
    import { Component, Vue } from 'vue-property-decorator'
    import {namespace} from "vuex-class";
    import {IUser, IUsersRoles} from "../types/settings";
    const StoreSettings = namespace('StoreSettings')
    @Component
    export default class Root extends Vue {
      @StoreSettings.Mutation('setUser') setUser!: (payload: IUser) => void
      @StoreSettings.Mutation('setRoles') setRoles!: (payload: IUsersRoles) => void
      async created() {

        if (this.$attrs.hasOwnProperty('settings') && this.$attrs.settings.hasOwnProperty('user')) {
          await this.setUser(this.$attrs.settings['user'])
        }
        if (this.$attrs.hasOwnProperty('settings') && this.$attrs.settings.hasOwnProperty('roles')) {
          await this.setRoles(this.$attrs.settings['roles'])
        }

      }
    }
</script>
