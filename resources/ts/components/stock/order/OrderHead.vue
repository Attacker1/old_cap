<template>
    <div class="h-panel-bar order-head">
        <Row type="flex" justify="start" :space="20" class="dark2-color stock-nav-head">
            <Cell>
                <Row type="flex" direction="row" :space="4">
                    <Cell class="order-head__icon order-head__avatar">
                        <IconAvatar />
                    </Cell>
                    <Row type="flex" direction="column" :space-x="-15">
                        <span>Клиент</span>
                        <router-link class="order-head__link" :to="{ name: 'admin.stock.my.orderlist'}">Наталия</router-link>
                    </Row>
                </Row>
            </Cell>
            <Cell>
                <Row type="flex" direction="row" :space="4">
                    <Cell class="order-head__icon order-head__lead">
                        <IconLibrary />
                    </Cell>
                    <Row type="flex" direction="column" :space-x="-15">
                        <span>Сделка</span>
                        <router-link class="order-head__link" :to="{ name: 'admin.stock.my.orderlist'}">29756453</router-link>
                    </Row>
                </Row>
            </Cell>
            <Cell>
                <Row type="flex" direction="row" :space="4">
                    <Cell class="order-head__icon order-head__status">
                        <IconGroupChat />
                    </Cell>
                    <Row type="flex" direction="column" :space-x="-15">
                        <span>Статус</span>
                        <span>Подборка составлена</span>
                    </Row>
                </Row>
            </Cell>
        </Row>
        <Row>
            <Cell>
                <Button class="in-capsula-btn" color="blue">Сохранить</Button>
            </Cell>
        </Row>
    </div>

</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator'
import IconAvatar from "../../icons/IconAvatar.vue";
import IconLibrary from "../../icons/IconLibrary.vue";
import IconGroupChat from "../../icons/IconGroupChat.vue";

@Component({
    components: {
        IconAvatar,
        IconLibrary,
        IconGroupChat
    }
})

export default class OrderHead extends Vue {
}
</script>
<style lang="less">
    .order-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 857px;
        width: 100%;

        &__icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
        }

        &__avatar {
            background-color: #E1F0FF;
        }

        &__lead {
            background-color: #FFF4DE;
        }

        &__status {
            background-color: #C9F7F5;
        }
    }
</style>