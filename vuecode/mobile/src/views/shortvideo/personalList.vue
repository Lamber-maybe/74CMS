<template>
    <div id="app">
        <Head>视频求职</Head>
        <List :videotype="'2'" :apitype="'1'"></List>
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
