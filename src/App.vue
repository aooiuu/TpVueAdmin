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
