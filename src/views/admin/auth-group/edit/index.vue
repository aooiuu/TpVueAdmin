<template>
  <el-dialog :visible.sync="showEx" :title="title" :close-on-click-modal="false">
    <el-form :model="form.data" label-position="left" label-width="70px" size="mini">
      <el-form-item label="名称">
        <el-input v-model="form.data.name" />
      </el-form-item>
      <el-form-item label="权限">
        <span>选中全部</span>
        <el-checkbox v-model="tree.checkAll" @change="treeOnChange" />
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
      <el-button size="mini" @click="$emit('hide')">
        取消
      </el-button>
      <el-button type="primary" size="mini" @click="save">
        保存
      </el-button>
    </div>
  </el-dialog>
</template>

<script>
export { default } from './script'
</script>
