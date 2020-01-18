<template>
  <el-dialog :visible.sync="showEx" :title="title" :close-on-click-modal="false">
    <el-form :model="form.data" label-position="left" label-width="70px" size="small">
      <el-form-item label="名称">
        <el-input v-model="form.data.name" />
      </el-form-item>
      <el-form-item label="权限" style="max-height: 300px; overflow: auto;">
        <el-tree
          ref="treeX"
          :data="tree.data"
          show-checkbox
          node-key="id"
          :default-expanded-keys="[2, 3]"
          :default-checked-keys="[5]"
        />
      </el-form-item>
    </el-form>
    <!-- footer -->
    <div slot="footer" class="dialog-footer">
      <el-button size="small" @click="$emit('hide')">
        取消
      </el-button>
      <el-button type="primary" size="small" @click="save">
        保存
      </el-button>
    </div>
  </el-dialog>
</template>

<script>
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
          pid: 0,
          name: 'a',
          title: 'v',
          weigh: 0,
          status: true,
          remark: '',
          type: 'menu'
        }
      },
      tree: {
        data: []
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
      value && this.getRoletree()
    }
  },
  methods: {
    async getRoletree() {
      console.log('getRoletree', this.item)
      try {
        const { code, msg, data } = await this.$request({
          url: 'admin/auth_group/roletree',
          method: 'GET',
          params: {
            id: this.item.id
          }
        })
        this.$message({
          type: code !== 0 ? 'error' : 'success',
          message: msg
        })
        if (code === 0) {
          this.buildTree(data)
        }
      } catch (error) {
        console.warn(error)
      }
    },
    buildTree(data) {
      this.tree.data = data.map(item => ({
        label: item.title
      }))
    },
    async save() {
      console.log('getRoletree', this.item)
      // try {
      //   const { code, msg } = await this.$request({
      //     url: 'admin/rule/add',
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
</script>

<style>
</style>
