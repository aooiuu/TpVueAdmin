<template>
  <div class="app-container admin_rule">
    <!-- 弹窗 -->
    <Add :show="Add.show" title="添加" :list="table.data" @hide="Add.show = false; refresh()" />
    <Edit :show="Edit.show" title="编辑" :list="table.data" :item="item" @hide="Edit.show = false; refresh()" />
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
    <el-table
      v-loading="table.loding"
      stripe
      :data="table.data"
      style="width: 100%"
      border
      fit
      highlight-current-row
      size="small"
      :row-style="({row, rowIndex})=> ({color: row.ismenu ? '#1890ff' : '#606266'})"
    >
      <el-table-column prop="id" label="ID" align="center" width="60" />
      <el-table-column prop="pid" label="父级" align="center" width="60" />
      <el-table-column prop="title" label="标题" align="center">
        <p slot-scope="{row}" style="text-align: left;" v-html="((row.text + (row.text ? ' ' : '' ) + row.title)).replace(/ /g,'&nbsp;')" />
      </el-table-column>
      <el-table-column prop="name" label="规则" align="center" />
      <el-table-column prop="icon" label="图标" align="center" width="50">
        <template slot-scope="{row}">
          <svg-icon v-if="row.icon" :icon-class="row.icon" />
          <span v-else>无</span>
        </template>
      </el-table-column>
      <el-table-column prop="weigh" label="权重" align="center" width="50" />
      <el-table-column
        prop="ismenu"
        label="是否菜单"
        align="center"
        :filters="[{ text: '是', value: 1 }, { text: '不是', value: 0 }]"
        :filter-method="(value, row)=>row.ismenu === value"
        filter-placement="bottom-end"
        width="100"
      >
        <el-tag slot-scope="{row}" :type="row.ismenu === 1 ? 'success' : 'info'">
          {{ row.ismenu === 1 ? '是' : '否' }}
        </el-tag>
      </el-table-column>
      <!-- 操作区域 -->
      <el-table-column
        label="操作"
        align="center"
        show-overflow-tooltip
        fixed="right"
        width="120"
      >
        <template slot-scope="{row}">
          <el-button
            title="编辑"
            class="btn-icon"
            type="primary"
            size="small"
            @click="edit(row)"
          >
            <svg-icon icon-class="pencil-alt-solid" />
          </el-button>
          <el-button
            title="删除"
            class="btn-icon"
            type="danger"
            size="small"
            @click="del(row)"
          >
            <svg-icon icon-class="trash-alt-solid" />
          </el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import { confirm } from '@/utils/messageBox'
import { toTree, reverseTree, toTreeArr } from '@/utils/tree'
export default {
  name: 'AdminRule',
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
    filterMetho(value, row) {
      return row.ismenu === value
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
          this.table.data = reverseTree(toTreeArr(toTree(rows)))
          console.log('this.table.data', this.table.data)
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

<style>
.admin_rule .icons {
  height: 200px;
  position: absolute;
  bottom: 0;
  right: 0;
  left: 0;
  visibility: hidden;
  opacity: 0;
  transition: all ease .2s;
  z-index: 9;
  background-color: #fff;
  border: solid 1px rgba(0,0,0,0.2);
  box-shadow: 0 2px 12px 0 rgba(0,0,0,.3);
  padding: 5px;
}
.admin_rule .icons.show {
  bottom: 33px;
  opacity: 1;
  visibility: visible;
}
</style>
