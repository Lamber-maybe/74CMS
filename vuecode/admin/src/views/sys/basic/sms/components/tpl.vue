<template>
  <div class="app-container">
    <el-radio-group v-model="alias" @change="aliasChange">
      <el-radio-button label="qscms">骑士官方</el-radio-button>
      <el-radio-button label="alisms">阿里云通信</el-radio-button>
    </el-radio-group>
    <div class="spaceline" />
    <el-table
      v-loading="listLoading"
      size="mini"
      :data="list"
      element-loading-text="Loading"
      fit
      highlight-current-row
    >
      <el-table-column
        label="模板名称"
        prop="title"
        width="130"
      />
      <el-table-column label="模板内容" prop="content" />
      <el-table-column label="参数" prop="params" width="290" />
      <el-table-column v-if="alias == 'alisms'" label="阿里模板ID" width="210">
        <template slot-scope="scope">
          <el-input
            v-model="scope.row.alisms_tplcode"
            class="small"
            size="mini"
          />
        </template>
      </el-table-column>
    </el-table>
    <div class="spaceline" />
    <el-button size="small" type="primary" @click="onSubmit()">保存</el-button>
  </div>
</template>

<script>
import { smsTplList } from '@/api/configuration'

export default {
  data() {
    return {
      alias: 'qscms',
      list: null,
      listLoading: true
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      const param = {}
      smsTplList(param, 'get')
        .then(response => {
          this.list = response.data.items
          this.listLoading = false
        })
        .catch(() => {})
    },
    aliasChange(e) {
      const tmp = []
      this.list.forEach(obj => {
        if (e == 'alisms') {
          obj.content = obj.content.replace(/{/g, '${')
        } else {
          obj.content = obj.content.replace(/\${/g, '{')
        }
        tmp.push(obj)
      })
      this.list = [...tmp]
    },
    onSubmit() {
      var that = this
      const insertData = []
      that.list.forEach(res => {
        insertData.push({
          id: res.id,
          alisms_tplcode: res.alisms_tplcode
        })
      })
      smsTplList(insertData)
        .then(response => {
          that.$message.success(response.message)
          return true
        })
        .catch(() => {})
    }
  }
}
</script>
