<template>
  <div class="icons-container">
    <el-input v-model="search" placeholder="搜索过滤" size="small" />
    <div class="grid">
      <div @click="handleClipboard('',$event)">
        <span class="icon-item">无</span>
      </div>
      <div
        v-for="item of allIcons"
        :key="item"
        @click="handleClipboard(item,$event)"
      >
        <div class="icon-item">
          <svg-icon :icon-class="item" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import svgIcons from './svg-icons'
export default {
  data() {
    return {
      svgIcons,
      search: ''
    }
  },
  computed: {
    allIcons() {
     return svgIcons.filter(e => (!this.search || e.includes(this.search)))
    }
  },
  methods: {
    handleClipboard(item, event) {
      this.$emit('handleClipboard', item)
    }
  }
}
</script>

<style lang="scss" scoped>
.icons-container {
  overflow: hidden;
  display: flex;
  flex-direction: column;
  .grid {
    flex: 1;
    overflow: auto;
    position: relative;
    display: grid;
    border-top: solid 1px rgba(0,0,0,0.1);
    margin-top: 5px;
    grid-template-columns: repeat(auto-fill, minmax(30px, 1fr));
    >div{
      height: 30px;
      display: flex;
      justify-content: center;
      align-content: center;
    }
  }

  .icon-item {
    font-size: 18px;
    display: block;
    text-align: center;
    float: left;
    color: #24292e;
    cursor: pointer;
    svg:hover{
      color: #1890ff;
    }
  }
}
</style>
