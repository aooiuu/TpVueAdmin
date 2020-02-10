<template>
  <div class="app-container">
    <!-- 弹窗 -->
    <!-- 工具栏 -->
    <div class="filter-container">
      <el-button class="filter-item" size="mini" @click="refresh">刷新</el-button>
      <el-input v-model="table.search" placeholder="标题" style="width: 200px;" class="filter-item" size="mini" />
      <el-button class="filter-item" type="primary" size="mini">
        <svg-icon icon-class="search-solid" />
        搜索
      </el-button>
      <el-button class="filter-item" type="primary" size="mini" @click="Add.show = true">
        <svg-icon icon-class="plus-solid" />
        添加
      </el-button>
      <el-button class="filter-item" type="primary" size="mini">
        <svg-icon icon-class="file-download-solid" />
        导出
      </el-button>
    </div>
    <!-- 表格 -->
    <el-table v-loading="table.loding" stripe :data="table.data" style="width: 100%" border fit highlight-current-row size="mini">
      <el-table-column prop="id" label="ID" align="center" width="100" />
      <el-table-column prop="url_num" label="URLID" align="center" width="100" />
      <el-table-column prop="url_str" label="URL" align="center">
        <template v-slot="{row}">
          <a :href="row.url_str">{{ row.url_str }}</a>
        </template>
      </el-table-column>
      <el-table-column prop="title" label="title" align="center" />
      <el-table-column prop="status" label="status" align="center" />
      <!-- 操作区域 -->
    </el-table>
    <!-- 分页 -->
    <pagination v-show="table.total>0" :total="table.total" :page.sync="page" :limit.sync="table.limit" @pagination="refresh" />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'
export default {
  name: 'UrlData',
  components: { Pagination },
  data() {
    return {
      item: {},
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
  computed: {
    page: {
      get() {
        return this.table.offset / this.table.limit + 1
      },
      set(val) {
        this.table.offset = this.table.limit * (val - 1)
      }
    }
  },
  mounted() {
    this.refresh()
  },
  methods: {
    async refresh() {
      this.table.loding = true
      try {
        const { code, msg, data } = await this.$request({
          url: 'admin/url_info/index',
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
