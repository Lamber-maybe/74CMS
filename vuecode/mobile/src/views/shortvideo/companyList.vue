<template>
    <div id="app">
        <Head>视频招聘</Head>
        <List :videotype="'1'" :apitype="'0'"></List>
    </div>
</template>
<script>
import List from './components/VideoList.vue'

export default {
  components: {
    List
  }
}
</script>
