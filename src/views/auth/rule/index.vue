<template>
  <div class="app-container">
    <!-- 弹窗 -->
    <Add :show="Add.show" title="添加" @hide="Add.show = false" />
    <!-- 工具栏 -->
    <div class="filter-container">
      <el-button class="filter-item" size="small" @click="refresh">刷新</el-button>
      <el-input placeholder="标题" style="width: 200px;" class="filter-item" size="small" />
      <el-button class="filter-item" type="primary" size="small">
        <svg-icon icon-class="search-solid" />
        搜索
      </el-button>
      <el-button class="filter-item" type="primary" size="small" @click="Add.show = true">
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
      <el-table-column prop="title" label="标题" align="center" />
      <el-table-column prop="name" label="规则" align="center" />
      <el-table-column prop="weigh" label="权重" align="center" />
      <!-- 操作区域 -->
      <el-table-column label="操作" align="center" show-overflow-tooltip fixed="right" width="80">
        <template slot-scope="{row}">
          <el-button title="编辑" class="btn-icon" type="primary" size="small">
            <svg-icon icon-class="pencil-alt-solid" />
          </el-button>
          <el-button title="删除" class="btn-icon" type="danger" size="small" @click="del(row)">
            <svg-icon icon-class="trash-alt-solid" />
          </el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import Add from './add'
import { confirm } from '@/utils/messageBox'
export default {
  components: {
    Add
  },
  data() {
    return {
      Add: {
        show: false
      },
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
  mounted() {
    this.refresh()
  },
  methods: {
    async del(item) {
      if (await confirm('此操作将删除此信息, 是否继续?')) {
        try {
          const { code, msg } = await this.$request({
            url: 'admin/rule/del',
            method: 'POST',
            data: {
              id: item.id
            }})
          this.$message({
            type: code !== 0 ? 'error' : 'success',
            message: msg
          })
          this.refresh()
        } catch (error) {
          this.$message({
            type: 'error',
            message: '接口访问失败'
          })
        }
      }
    },
    async refresh() {
      this.table.loding = true
      try {
        const { code, msg, data } = await this.$request({
          url: 'admin/rule/index',
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
