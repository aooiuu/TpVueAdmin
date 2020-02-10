<template>
  <div class="app-container">
    <!-- 弹窗 -->
    <Add :show="Add.show" title="添加" @hide="Add.show = false; refresh()" />
    <Edit :show="Edit.show" title="编辑" :item="item" @hide="Edit.show = false; refresh()" />
    <!-- 工具栏 -->
    <div class="filter-container">
      <el-button class="filter-item" size="mini" @click="refresh">刷新</el-button>
      <el-input placeholder="标题" style="width: 200px;" class="filter-item" size="mini" />
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
      <el-table-column prop="id" label="ID" align="center" width="60" />
      <el-table-column prop="username" label="用户名" align="center" />
      <el-table-column prop="nickname" label="昵称" align="center" />
      <el-table-column prop="nickname" label="所属组别" align="center">
        <template slot-scope="{row}">
          <el-tag
            v-for="authGroupAccess in row.auth_group_access"
            :key="authGroupAccess.group_id + '_' + authGroupAccess.uid"
            size="mini"
            class="auth-group-tag"
          >
            {{ authGroupAccess.auth_group.name }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="status" label="状态" align="center">
        <el-tag slot-scope="{row}" :type="row.status === 'normal' ? 'success' : 'danger'">
          {{ row.status === 'normal' ? '正常' : '隐藏' }}
        </el-tag>
      </el-table-column>
      <!-- 操作区域 -->
      <el-table-column label="操作" align="center" show-overflow-tooltip fixed="right" width="100">
        <template slot-scope="{row}">
          <el-button title="编辑" class="btn-mini" type="primary" size="mini" @click="edit(row)">
            <svg-icon icon-class="pencil-alt-solid" />
          </el-button>
          <el-button title="删除" class="btn-mini" type="danger" size="mini" @click="del(row)">
            <svg-icon icon-class="trash-alt-solid" />
          </el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import { confirm } from '@/utils/messageBox'
export default {
  name: 'AdminAdmin',
  components: {
    Add: () => import('./add'),
    Edit: () => import('./edit')
  },
  data() {
    return {
      item: {},
      Add: {
        show: false
      },
      Edit: {
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
    },
    edit(item) {
      this.item = item
      this.Edit.show = true
      console.log('edit:', this.item)
    },
    async del(item) {
      if (await confirm('此操作将删除此信息, 是否继续?')) {
        try {
          const { code, msg } = await this.$request({
            url: 'admin/admin/del',
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
    }
  }
}
</script>

<style lang="css">
.auth-group-tag {
  margin: 2px 5px;
}
</style>
