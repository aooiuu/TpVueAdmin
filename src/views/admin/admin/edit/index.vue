<template class="admin_add">
  <el-dialog :visible.sync="showEx" :title="title" :close-on-click-modal="false">
    <el-form :model="form.data" label-position="left" label-width="70px" size="small">
      <el-form-item label="用户组">
        <el-select v-model="form.data.group" multiple placeholder="请选择" style="width:100%;">
          <el-option
            v-for="options in form.group.options"
            :key="options.value"
            :label="options.label"
            :value="options.value"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="用户名">
        <el-input v-model="form.data.username" />
      </el-form-item>
      <el-form-item label="密码">
        <el-input v-model="form.data.password" type="password" />
      </el-form-item>
      <el-form-item label="昵称">
        <el-input v-model="form.data.nickname" />
      </el-form-item>
      <el-form-item label="状态">
        <el-switch v-model="form.data.status" active-value="normal" inactive-value="hidden" />
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
import { toTree, toTreeArr } from '@/utils/tree'
import { filterObj } from '@/utils/object'
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
          username: '',
          nickname: '',
          password: '',
          status: 'normal',
          // 多个分组
          group: []
        },
        group: {
          options: []
        }
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
      this.initAuthGroupTree()
    }
  },
  methods: {
    async initAuthGroupTree() {
      console.log('item:')

      const { code, msg, data } = await this.$request({
        url: 'admin/auth_group/index',
        method: 'POST',
        data: this.form.data
      })
      if (code !== 0) {
          this.$message({
          type: 'error',
          message: msg
        })
        return
      }
      console.log({ code, msg, data })
      this.form.group.options = toTreeArr(toTree(data.rows)).map(e => ({
        label: e.text + ' ' + e.name,
        value: e.id
      }))
      this.form.data = Object.assign(this.form.data, filterObj(this.item, Object.keys(this.form.data)))
      this.form.data.group = this.item.auth_group_access.map(e => e.group_id)
      console.table(JSON.parse(JSON.stringify(this.form.group.options)), ['label', 'value'])
    },
    async save() {
      console.table({ ...this.form.data })
      try {
        const { code, msg } = await this.$request({
          url: 'admin/admin/edit',
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
</script>

<style>
.el-select-dropdown__item{
  white-space: pre;
}
</style>
