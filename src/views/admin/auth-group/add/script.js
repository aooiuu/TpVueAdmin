import { getRules, buildRulesTree, buildGroupPidTree } from '@/views/admin/utils'

export default {
  props: {
    show: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: '编辑'
    },
    list: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      treeIsexpand: false,
      treeLoading: false,
      form: {
        data: {
          pid: null,
          name: '',
          rules: []
        },
        pid: {
          options: []
        }
      },
      tree: {
        data: [],
        origData: [],
        checkAll: false
      }
    }
  },
  computed: {
    showEx: {
      get() { return this.show },
      set(value) {
        this.$emit('hide')
      }
    }
  },
  watch: {
    show(value) {
      if (!value) {
        return
      }
      this.form.pid.options = buildGroupPidTree(this.list).map(e => ({
        label: e.text + ' ' + e.name,
        value: e.id
      }))
      this.getRulesTree()
    }
  },
  methods: {
    async getRulesTree(id) {
      this.treeLoading = true
      try {
        const { code, msg, data } = await getRules(id)
        if (code === 0) {
          this.tree.origData = data.filter(item => item.children ? true : item.state.selected)
          this.tree.data = buildRulesTree(this.tree.origData)
          const checkedKeys = this.tree.origData
            .filter(item => item.state.selected)
            .map(item => item.id)
          this.$refs.treeX.setCheckedKeys(checkedKeys)
        } else {
          this.$message({
            type: 'error',
            message: msg
          })
        }
      } catch (error) {
        console.warn(error)
      }
      this.treeLoading = false
    },
    pidOnChange(value) {
      this.getRulesTree(value === 0 ? undefined : value)
    },
    async save() {
      this.form.data.rules = this.$refs.treeX.getCheckedKeys()
      console.log(this.form.origData)
      try {
        const { code, msg } = await this.$request({
          url: 'admin/auth_group/add',
          method: 'POST',
          data: this.form.data
        })
        this.$message({
          type: code !== 0 ? 'error' : 'success',
          message: msg
        })
      } catch (error) {
        console.warn(error)
      }
    }
  }
}
