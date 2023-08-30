<template>
    <MainFrame :title="$route.meta.title">
        <div class="stock-frame">
            <!-- content -->
            <Head></Head>

            <Row :space-x="10" :space-y="10" type="flex" class="stock-index-content orderlist__table">
                <Table :datas="orders" stripe>
                    <TableItem title="ID заказа" prop="id"></TableItem>
                    <TableItem title="Amo ID" prop="lead_uuid"></TableItem>
                    <TableItem title="Дата формирования" prop="confirmed_at"></TableItem>
                    <TableItem title="Статус заказа" prop="state_id"></TableItem>
                    <TableItem title="Просмотреть заказ" prop="dictData" dict="simple">
                        <template slot-scope="{data}">
                            <button class="h-btn h-btn-s h-btn-blue" @click="proceed(data)"><i class="fa far fa-edit"></i>
                            </button>
                        </template>
                    </TableItem>
                </Table>
            </Row>
        </div>
    </MainFrame>
</template>
<script lang="ts">
import {Component, Vue} from "vue-property-decorator";
import Head from "../../components/stock/Head.vue";
import MainFrame from "../../components/layout/MainFrame.vue";
import {ICart} from "../../types/stock";
import {CartStore} from "../../store/modules/stock/cart";

@Component({
    components: {
        MainFrame,
        Head
    }
})

export default class MyOrderList extends Vue {
    data() {
        return {
            datas: [
                {orderId: 1234, amoId: '23456789', date: '11.08.2021', status: 'Новый'},
                {orderId: 1233, amoId: '23456788', date: '11.08.2021', status: 'Подтвержденный'},
                {orderId: 1232, amoId: '23456786', date: '11.08.2021', status: 'Закрытый'},
                {orderId: 1231, amoId: '23456787', date: '11.08.2021', status: 'Подтвержденный'},
                {orderId: 1230, amoId: '23456785', date: '11.08.2021', status: 'Новый'},
                {orderId: 1229, amoId: '23456784', date: '11.08.2021', status: 'Подтвержденный'}
            ]
        };
    }

    proceed(order) {
        this.$router.push(
            {
                name: 'admin.stock.order',
                params: {
                    order_id: order.orderId.toString(),
                }
            }).catch(err => {});
    }

    get orders(): ICart[] {
        return CartStore.orders;
    }

    async beforeCreate() {
        try {
            await CartStore.fetchOrders();
        } catch (e) {
            console.log(e);
        }
    }
}
</script>
<style lang="less">
.orderlist__table {
    width: 75%;
}
</style>