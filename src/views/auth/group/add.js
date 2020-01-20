import { getRules, buildRulesTree, buildGroupPidTree } from '@/views/auth/utils'

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
      form: {
        data: {
          pid: 0,
          name: '',
          rules: ''
        },
        pid: {
          options: [],
          value: [0]
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
      this.form.pid.options = buildGroupPidTree(this.list)
      this.form.pid.value = [0]
      this.getRulesTree()
    }
  },
  methods: {
    async getRulesTree(id) {
      try {
        const { code, msg, data } = await getRules(id)
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
    pidOnChange(value) {
      this.form.pid.value = value
      this.form.data.pid = value[value.length - 1]
      console.log('this.form:', this.form)
      console.log('this.form.pid.value:', this.form.pid.value)
    },
    async save() {
      return console.log(this.form.data)
      // try {
      //   const { code, msg } = await this.$request({
      //     url: 'admin/auth_group/add',
      //     method: 'POST',
      //     data: this.form.data
      //   })
      //   this.$message({
      //     type: code !== 0 ? 'error' : 'success',
      //     message: msg
      //   })
      // } catch (error) {
      //   console.warn(error)
      // }
    }
  }
}
