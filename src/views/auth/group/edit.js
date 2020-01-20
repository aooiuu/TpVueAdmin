import { getRules, buildRulesTree } from '@/views/auth/utils'

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
    item: {
      type: Object,
      default: () => {}
    }
  },
  data() {
    return {
      treeIsexpand: false,
      form: {
        data: {
          id: 0,
          name: '',
          rules: []
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
      if (value) {
        this.form.data.name = this.item.name
        this.form.data.id = this.item.id
        this.getRulesTree()
      }
    }
  },
  methods: {
    treeOnChange(value) {
      console.log('treeOnChange: ', value)
      if (value) {
        const checkedKeys = this.tree.origData.map(item => item.id)
        console.log('checkedKeys:', checkedKeys)
        this.$refs.treeX.setCheckedKeys(checkedKeys)
      } else {
        this.$refs.treeX.setCheckedKeys([])
      }
    },
    async getRulesTree() {
      try {
        const { code, msg, data } = await getRules(this.item.id)
        if (code === 0) {
          this.tree.origData = data
          this.tree.data = buildRulesTree(data)
          const checkedKeys = this.tree.data
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
    },
    async save() {
      this.form.data.rules = this.$refs.treeX.getCheckedKeys()
      console.log('this.form.data:', this.form.data)
      try {
        const { code, msg } = await this.$request({
          url: 'admin/auth_group/edit',
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
