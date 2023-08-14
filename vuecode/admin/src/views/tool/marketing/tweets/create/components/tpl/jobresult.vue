<template>
  <div>
    <el-input
      v-model="content"
      :value="content"
      type="textarea"
      :rows="20"
    />
  </div>
</template>
<script>
import { tweetsTemplateEdit } from '@/api/tweets_template'
export default {
  props: ['datalist', 'itemInfoId', 'paramsArr', 'contentParamsArr', 'titleFooter'],
  data() {
    return {
      content: '',
      itemInfo: []
    }
  },
  watch: {
    datalist(val, oldVal){
      this.replaceContent()
    },
    content(val, oldVal){
      this.$emit('copyhtml', val)
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData(){
      const that = this
      that.listLoading = true
      tweetsTemplateEdit({ id: that.itemInfoId }, 'get')
        .then(response => {
          that.itemInfo = response.data.info
          that.replaceContent()
          that.listLoading = false
        })
        .catch(() => {})
    },
    replaceContent(){
      const that = this
      let textContent = ''
      let title_item = JSON.parse(JSON.stringify(that.itemInfo.title))
      that.paramsArr.forEach(function(e) {
        title_item = title_item.replace('#' + e.value + '#', that.titleFooter[e.value])
      })
      textContent = textContent + title_item + '\n\r'
      that.datalist.forEach(function(e, k) {
        let item = JSON.parse(JSON.stringify(that.itemInfo.content))
        that.contentParamsArr.forEach(function(event) {
          if (event.value === 'id'){
            item = item.replace('#' + event.value + '#', k + 1)
          } else {
            item = item.replace('#' + event.value + '#', e[event.value])
          }
        })
        textContent += item + '\n\r'
      })
      let footer_item = JSON.parse(JSON.stringify(that.itemInfo.footer))
      that.paramsArr.forEach(function(e) {
        footer_item = footer_item.replace('#' + e.value + '#', that.titleFooter[e.value])
      })
      textContent += footer_item
      that.content = textContent
    }
  }
}
</script>
