<template>

  <ul class="h-menu h-menu-dark main-left-menu "
      :class="siderCollapsed ? 'h-menu-mode-vertical h-menu-size-collapse' : 'h-menu-mode-normal'">
    <li class="h-menu-li cap" :class="{'h-menu-li-opened' : itemOpened.key === item.key && !siderCollapsed}" v-for="item in items">
      <a class="h-menu-show" :class="{'h-menu-li-selected cap': $route.name.includes(item.key)}" @click="showItem(item)"
         :href="item.path">
        <span class="h-menu-show-icon"><i :class="item.icon"></i></span>
        <span class="h-menu-show-desc">{{ item.title }}</span>
        <span v-if="item.hasOwnProperty('children') && item.children.length && !siderCollapsed" class="h-menu-show-expand"><i class="h-icon-angle-down"></i></span>
      </a>

      <div  class="h-tooltip cap-tooltip" v-if="!item.hasOwnProperty('children') || !item.children.length">
        <div class="h-tooltip-arrow placement-right"></div>
        <div class="h-tooltip-inner cap">
          <div class="h-tooltip-inner-content cap">{{ item.title }}</div>
        </div>
      </div>

      <ul class="h-menu-ul" v-if="item.hasOwnProperty('children') && item.children.length">


        <li class="h-menu-li cap h-menu-li-opened" v-for="child in item.children" @click="showItem(item)">
          <a class="h-menu-show last" @click.stop="retFalse()"
             :href="child.hasOwnProperty('children') && child.children.length ? 'javascript:' : child.path"
             :class="{'h-menu-li-selected cap': $route.name.includes(child.key)}">
            <span class="h-menu-show-icon"><i :class="child.icon"></i></span>
            <span class="h-menu-show-dcesc">{{ child.title }}</span>
            <span v-if="child.hasOwnProperty('children') && child.children.length && !siderCollapsed" class="h-menu-show-expand subchild"><i class="h-icon-angle-down"></i></span>
          </a>
          <ul class="h-menu-ul" v-if="child.hasOwnProperty('children') && child.children.length">


            <li class="h-menu-li cap" v-for="subChild in child.children" @click="showItem(child)">
              <a class="h-menu-show last" @click.stop="$router.push({name:subChild.key})"
                 :class="{'h-menu-li-selected cap': $route.name.includes(subChild.key)}">
                <span class="h-menu-show-icon"><i :class="subChild.icon"></i></span>
                <span class="h-menu-show-dcesc">{{ subChild.title }}</span>
<!--                :href="subChild.hasOwnProperty('children') && subChild.children.length ? 'javascript:' : subChild.path"-->
              </a>
            </li>

          </ul>
        </li>

      </ul>
    </li>

  </ul>

</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator'
import {namespace} from 'vuex-class'
import {ISideMenu} from "../../../types/settings";

const ex = namespace('Example')

@Component
export default class AsideMenu extends Vue {

  @Prop() items !: ISideMenu[]
  @Prop() siderCollapsed !: boolean

  public itemOpened = {
    key: '',
    state: false
  }

  showItem(item: ISideMenu) {
    if (this.siderCollapsed || !item.hasOwnProperty('children') || !item.children.length ) {return false}
    console.log(item);
    this.itemOpened = this.itemOpened.key == item.key
        ? {key: '', state: false}
        : {key: item.key, state: true}
  }
  retFalse() {
    return false
  }

}
</script>

<style>
.cap-tooltip {
  position: absolute;
  top: 14px;
  left: 49px;
  display: none;
  opacity: 0;
}

.h-tooltip .h-tooltip-arrow.placement-right {
  top: 50%;
  margin-top: -5px;
  left: 3px;
  border-width: 5px 5px 5px 0;
  border-right-color: #333333;
}

.h-tooltip-inner.cap {
  margin-left: 8px;
  border: 1px solid #333333;

}

.h-menu-size-collapse li:hover .cap-tooltip {
  display: block;
  opacity: 1;
}

.h-tooltip-inner-content.cap {
  padding: 8px 14px;
}
.h-menu-li.cap {
  position: relative
}
.h-menu-li.cap .h-menu-show-desc {
  font-size: 13px!important;
}
.h-menu-li-selected.cap .h-menu-show-icon, .h-menu-li-selected.cap .h-menu-show-desc{
  color: #333;
}
.h-menu-mode-normal .h-menu-ul a.h-menu-li-selected.cap {
  background: unset;
  color:#45b984;
}
.h-menu-mode-normal .h-menu-ul a.h-menu-li-selected.cap .icon-shuffle {
  color:#45b984;
}
.h-menu-li-selected.cap .h-icon-angle-down {
  color: #333;
}
.h-menu-show.last.h-menu-li-selected.cap {
  color: #333!important;
  font-size: 13px!important;
}
.h-menu-mode-normal .h-menu-show.last.h-menu-li-selected.cap {
  color:#45b984!important;
  font-size: 13px!important;
}
.h-menu-show.last {
  font-size: 13px!important;
  color: #b3b3b3!important;
}
.h-menu-show-expand.subchild .h-icon-angle-down{
  color:#45b984!important;
}
.h-menu-mode-normal .h-menu-li .h-menu-li .h-menu-show {
  padding-left: 35px;
}
.h-menu-mode-normal .h-menu-li .h-menu-li .h-menu-li .h-menu-show {
  padding-left: 50px;
}
.h-menu-li-opened:before {
  height: 44px;
}
</style>
