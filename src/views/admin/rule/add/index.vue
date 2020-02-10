<template>
  <el-dialog :visible.sync="showEx" :title="title" :close-on-click-modal="false">
    <el-form :model="form.data" label-position="left" label-width="70px" size="mini">
      <el-form-item label="是否菜单">
        <el-switch v-model="form.data.ismenu" :active-value="1" :inactive-value="0" />
      </el-form-item>
      <el-form-item label="父级">
        <el-select v-model="form.data.pid" placeholder="请选择" style="width:100%;">
          <el-option
            v-for="options in form.pid.options"
            :key="options.value"
            :value="options.value"
            :label="options.label"
          >
            <span style="float: left" v-html="options.label.replace(/ /g,'&nbsp;')" />
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="标题">
        <el-input v-model="form.data.title" />
      </el-form-item>
      <el-form-item label="规则">
        <el-input v-model="form.data.name" placeholder="目录名/控制器名/方法名" />
      </el-form-item>
      <el-form-item label="图标">
        <el-button size="mini" @click="iconsShow = !iconsShow">
          <svg-icon v-if="form.data.icon" :icon-class="form.data.icon" />
          <span v-else>无</span>
        </el-button>
        <icons
          :class="{icons: true, show: iconsShow}"
          @handleClipboard="handleClipboard"
        />
      </el-form-item>
      <el-form-item label="权重">
        <el-input v-model="form.data.weigh" />
      </el-form-item>
      <el-form-item label="备注">
        <el-input v-model="form.data.remark" />
      </el-form-item>
      <el-form-item label="状态">
        <el-switch v-model="form.data.status" active-value="normal" inactive-value="hidden" />
      </el-form-item>
    </el-form>
    <!-- footer -->
    <div slot="footer" class="dialog-footer">
      <el-button size="mini" @click="showEx = false">
        取消
      </el-button>
      <el-button type="primary" size="mini" @click="save">
        保存
      </el-button>
    </div>
  </el-dialog>
</template>

<script>
import { buildRulePidTree } from '@/views/admin/utils'

export default {
  components: {
    icons: () => import('@/components/icons')
  },
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
      iconsShow: false,
      form: {
        data: {
          pid: null,
          name: '',
          title: '',
          weigh: 0,
          icon: '',
          status: 'normal',
          remark: '',
          type: 'menu',
          ismenu: 1
        },
        pid: {
          options: []
        }
      }
    }
  },
  computed: {
    showEx: {
      get() { return this.show },
      set(value) {
        this.iconsShow = false
        this.$emit('hide')
      }
    }
  },
  watch: {
    show(value) {
      if (!value) {
        return
      }
      console.log('this.list:', this.list)
      this.form.pid.options = buildRulePidTree(this.list.concat([{
        id: 0,
        pid: -1,
        title: '无'
      }])).map(e => {
        e.label = e.text + e.label
        return e
      })
    }
  },
  mounted() {
  },
  methods: {
    handleClipboard(item) {
      this.form.data.icon = item
    },
    pidOnChange(value) {
      this.form.pid.value = value
      this.form.data.pid = this.form.pid.value[this.form.pid.value.length - 1]
      console.log('this.form:', this.form)
      console.log('this.form.pid.value:', this.form.pid.value)
    },
    async save() {
      console.table(JSON.parse(JSON.stringify(this.form.data)))
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
