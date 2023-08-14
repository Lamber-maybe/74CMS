<template>
  <div>

  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'Meta',
  props: ['pagealias', 'custom_data', 'query_data'],
  metaInfo () {
    return {
      title: this.title,
      meta: [
        {name: 'keywords', content: this.keywords},
        {name: 'description', content: this.description},
        {property: 'og:type', content: this.og_type},
        {property: 'og:title', content: this.og_title},
        {property: 'og:url', content: this.og_url},
        {property: 'og:site_name', content: this.og_site_name},
        {property: 'og:description', content: this.og_description}
      ]
    }
  },
  data () {
    return {
      title: '',
      keywords: '',
      description: '',
      og_type: '',
      og_title: '',
      og_url: '',
      og_site_name: '',
      og_description: ''
    }
  },
  created () {
    this.fetchData()
  },
  watch: {
    $route: {
      handler: function (val, oldVal) {
        if (val.name === oldVal.name) {
          this.fetchData()
        }
      },
      // 深度观察监听
      deep: true
    },
    pagealias: function () {
      this.fetchData()
    }
  },
  methods: {
    fetchData () {
      this.og_url = location.href
      http
        .get(api.pageinfo, {alias: this.pagealias, data: this.query_data === undefined ? '{}' : this.query_data, custom_data: this.custom_data === undefined ? '{}' : this.custom_data})
        .then(res => {
          if (res.data.seo_title !== undefined) {
            this.title = res.data.seo_title
            this.keywords = res.data.seo_keywords
            this.description = res.data.seo_description
            this.og_title = res.data.og_title
            this.og_type = res.data.og_type
            this.og_site_name = res.data.og_site_name
            this.og_description = res.data.og_description
          }
        }).catch(() => {})
    }
  }
}
</script>
