<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style lang="css">
.el-dialog__body {
  max-height: 60vh;
  overflow-y: auto;
}
.el-button+.el-button{
  margin-left: 0;
}
.tag-group .el-tag+.el-tag {
  margin-left: 5px;
}
p {
  margin: 0;
  padding: 0;
}
</style>
