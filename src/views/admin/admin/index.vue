<template>
  <div class="app-container">
    <!-- 工具栏 -->
    <div class="filter-container">
      <el-button class="filter-item" size="small" @click="refresh">刷新</el-button>
      <el-input placeholder="标题" style="width: 200px;" class="filter-item" size="small" />
      <el-button class="filter-item" type="primary" size="small">
        <svg-icon icon-class="search-solid" />
        搜索
      </el-button>
      <el-button class="filter-item" type="primary" size="small">
        <svg-icon icon-class="plus-solid" />
        添加
      </el-button>
      <el-button class="filter-item" type="primary" size="small">
        <svg-icon icon-class="file-download-solid" />
        导出
      </el-button>
    </div>
    <!-- 表格 -->
    <el-table v-loading="table.loding" stripe :data="table.data" style="width: 100%" border fit highlight-current-row size="small">
      <el-table-column prop="id" label="ID" align="center" width="60" />
      <el-table-column prop="username" label="username" align="center" />
      <el-table-column prop="nickname" label="nickname" align="center" />
      <el-table-column prop="nickname" label="所属组别" align="center" />
      <!-- 操作区域 -->
      <el-table-column label="操作" align="center" show-overflow-tooltip fixed="right" width="100">
        <template>
          <el-button title="编辑" class="btn-icon" type="primary" size="small">
            <svg-icon icon-class="pencil-alt-solid" />
          </el-button>
          <el-button title="删除" class="btn-icon" type="danger" size="small">
            <svg-icon icon-class="trash-alt-solid" />
          </el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      table: {
        loding: false,
        data: [],
        total: 0,
        search: '',
        sort: 'id',
        order: 'desc',
        offset: 0,
        limit: 10,
        filter: {},
        op: {}
      }
    }
  },
  methods: {
    async refresh() {
      this.table.loding = true
      try {
        const { code, msg, data } = await this.$request({
          url: 'admin/admin/index',
          method: 'GET',
          params: {
            search: this.table.search,
            sort: this.table.sort,
            order: this.table.order,
            offset: this.table.offset,
            limit: this.table.limit,
            filter: this.table.filter,
            op: this.table.op
          }})
        if (code !== 0) {
          this.$message({
            type: 'info',
            message: msg
          })
        } else {
          const { total, rows } = data
          this.table.total = total
          this.table.data = rows
        }
      } catch (error) {
        this.$message({
          type: 'error',
          message: '接口访问失败'
        })
      }
      this.table.loding = false
    }
  }
}
</script>
