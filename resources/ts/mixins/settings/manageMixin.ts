import {Component, Prop, Vue} from 'vue-property-decorator'
import {namespace} from 'vuex-class'

const StoreSettings = namespace('StoreSettings')
import {eventBus} from "../../bus";
import {IFormItem, IManageBlock, IManageBlockItem, IUser, IUsersRoles} from "../../types/settings";

@Component
export default class manageMixin extends Vue {

    @StoreSettings.State('user') user!: IUser
    @StoreSettings.State('roles') roles!: IUsersRoles[]
    @StoreSettings.Mutation('setFakeRole') setFakeRole!: (payload: number) => void
    @StoreSettings.State('manageBlocks') manageBlocks!: IManageBlock[]
    @StoreSettings.Action('updateOrCreateManageBlock') updateOrCreateManageBlock!: (payload) => void
    @StoreSettings.Action('updateOrCreateManageBlockItems') updateOrCreateManageBlockItems!: (payload) => void

    @StoreSettings.Mutation('setObList') setObList!: (payload) => void

    get fakeRole() {
        return this.$store.state.StoreSettings.fakeRole
    }

    set fakeRole(payload) {
        this.setFakeRole(payload)
    }

    get namespace() {
        return this.$store.state.StoreSettings.namespace
    }

    get obList() {
        return this.$store.state.StoreSettings.obList
    }

    set obList(payload) {
        this.setObList(payload)
    }


    public open: boolean = false
    public editItem = 0

    // Управляет доступом к полям
    accessItem(item: IManageBlockItem) {
        let currentRole = this.fakeRole > 0 ? this.fakeRole : this.user.role_id;
        return item.roles.indexOf(currentRole) !== -1
    }

    access(id, arrObj: object[] = []) {

        let newKey: string = 'ob' + id.toString()
        if (arrObj.length && !this.obList[newKey]) {
            this.obList[newKey] = arrObj[0]
        }

        let currentRole = this.fakeRole > 0 ? this.fakeRole : this.user.role_id;
        let section = this.manageBlocks.find(i => i.id === id)
        return section && section.roles.indexOf(currentRole) !== -1
    }

    async submit(payload) {
        if (payload.valid.result) {
            await this.updateOrCreateManageBlock(payload.data)
        }
        this.open = false
    }

    async submitItem(payload) {
        console.log(payload.data.sortable);
        if (payload.valid.result) {
            await this.updateOrCreateManageBlockItems(payload.data)
        }
        this.open = false
    }

    putInputValue(payload: {
        obj: IFormItem[]
        key: string
        value: any
    }) {
        let elem = payload.obj.find(i => i.key === payload.key)
        if (elem !== undefined) {
            elem.input_value = payload.value
        }
    }

    putInputList(payload: {
        obj: IFormItem[]
        key: string
        value: any
    }) {
        let elem = payload.obj.find(i => i.key === payload.key)
        if (elem !== undefined) {
            elem.input_list = payload.value
        }
    }

    async changeBlockRoles(item: IManageBlock) {
        let roleIndex = item.roles.indexOf(this.fakeRole)

        roleIndex !== -1
            ? item.roles.splice(roleIndex, 1)
            : item.roles.push(this.fakeRole)

        await this.updateOrCreateManageBlock(item)
    }

    async changeBlockItemRoles(item: IManageBlockItem) {
        let roleIndex = item.roles.indexOf(this.fakeRole)

        roleIndex !== -1
            ? item.roles.splice(roleIndex, 1)
            : item.roles.push(this.fakeRole)

        await this.updateOrCreateManageBlockItems(item)
    }


}
