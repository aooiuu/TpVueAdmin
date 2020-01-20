<template>
  <el-dialog :visible.sync="showEx" :title="title" :close-on-click-modal="false">
    <el-form :model="form.data" label-position="left" label-width="70px" size="small">
      <el-form-item label="父级">
        <el-cascader
          v-model="form.pid.value"
          :options="form.pid.options"
          size="small"
          style="width: 100%;"
          @change="pidOnChange"
        />
      </el-form-item>
      <el-form-item label="分组名">
        <el-input v-model="form.data.name" />
      </el-form-item>
      <el-form-item label="权限">
        <el-tree
          ref="treeX"
          :data="tree.data"
          show-checkbox
          node-key="id"
          default-expand-all
          style="max-height: 300px; overflow: auto; margin-top: 5px;"
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
export { default } from './add.js'
</script>

