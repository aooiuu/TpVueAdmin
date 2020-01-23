<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script>
export default {
  name: 'App',
  mounted() {
    console.log('mouted ')
    window.router = this.$router
    window.route = this.$route
  }
}
</script>

<style>
html, body, p {
  margin: 0;
  padding: 0;
}
.space-pre {
white-space: pre;
}
.el-table--small th, .el-table--small td{
padding:  5px 0;
}
</style>
