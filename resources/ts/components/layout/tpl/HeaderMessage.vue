<template>
  <DropdownCustom class="app-header-message-vue" placement="bottom-end" className="app-message-dropdown" :toggleIcon="false" ref="messageDropdown">
    <div class="app-header-icon-item">
      <Badge :count="2">
        <i class="h-icon-bell"></i>
      </Badge>
    </div>
    <div slot="content">
      <div class="h-panel">
        <div class="h-panel-bar h-panel-bar-s">
          <span class="h-panel-title">Последние сообщения</span>
        </div>
        <div class="message-list-container common-list-container">
          <div class="common-list-item" v-for="m of messageList" :key="m.id" @click="goMessageDetail(m)" :class="{readed: m.isReaded, unReaded: !m.isReaded}">
            <div class="common-list-meta">
              <p class="title">{{m.title}}</p>
              <p class="description">{{m.description}}</p>
            </div>
          </div>
        </div>
        <div v-if="messageList.length>0" class="text-center h-panel-bar"><span class="link">Больше сообщений</span></div>
      </div>
    </div>
  </DropdownCustom>
</template>
<script lang="ts">
import {Component, Vue} from "vue-property-decorator";
import {namespace} from "vuex-class";
@Component({components: {}})
export default class HeaderMessage extends Vue{

  public messageList = [{
    'id': 1,
    'isReaded': false,
    'title': 'Макс',
    'description': 'Сколько у вас сегодня рук?'
  }, {
    'id': 2,
    'isReaded': false,
    'title': 'Sergey Loginov',
    'description': 'пока вдвоём, но должен ещё один появиться'
  }, {
    'id': 3,
    'isReaded': true,
    'title': 'Макс',
    'description': 'Ты и Наташа?'
  }, {
    'id': 4,
    'isReaded': true,
    'title': 'Макс',
    'description': 'Антону пока вливаться'
  }, {
    'id': 5,
    'isReaded': true,
    'title': 'Sergey Loginov',
    'description': 'да'
  }]
};
</script>
