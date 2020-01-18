<template>
  <el-dialog :visible.sync="showEx" :title="title" :close-on-click-modal="false">
    <el-form :model="form.data" label-position="left" label-width="70px" size="small">
      <el-form-item label="规则">
        <el-input v-model="form.data.name" placeholder="目录名/控制器名/方法名" />
      </el-form-item>
      <el-form-item label="标题">
        <el-input v-model="form.data.title" />
      </el-form-item>
      <el-form-item label="权重">
        <el-input v-model="form.data.weigh" />
      </el-form-item>
      <el-form-item label="备注">
        <el-input v-model="form.data.remark" />
      </el-form-item>
      <el-form-item label="状态">
        <el-switch v-model="form.data.status" />
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
      treeData: []
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
  methods: {
    async save() {
      try {
        const { code, msg } = await this.$request({
          url: 'admin/rule/add',
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
</style>
