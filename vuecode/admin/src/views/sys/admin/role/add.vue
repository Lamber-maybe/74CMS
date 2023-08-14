<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>添加角色</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/sys/admin/role')"
        >
          返回
        </el-button>
        <el-button
          style="float: right; padding: 0;"
          type="text"
          @click="onSubmit('form')"
        >
          保存
        </el-button>
      </div>
      <el-form
        ref="form"
        class="common-form"
        :model="form"
        label-width="100px"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item label="角色名称" prop="name">
          <el-input v-model="form.name" class="middle" />
        </el-form-item>
        <el-form-item label="权限">
          <el-tree
            ref="tree"
            node-key="name"
            :data="accessData"
            :props="props"
            show-checkbox
            :default-checked-keys="checkKeys"
            @check-change="handleCheckChange"
          />
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button @click="goto('/sys/admin/role')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { apiAdd } from '@/api/admin_role'
import userRouter from '@/router/modules/user'
import businessRouter from '@/router/modules/business'
import contentRouter from '@/router/modules/content'
import mobileRouter from '@/router/modules/mobile'
import statRouter from '@/router/modules/stat'
import applicationRouter from '@/router/modules/application'
import toolRouter from '@/router/modules/tool'
import systemRouter from '@/router/modules/system'

export default {
  data() {
    return {
      checkKeys: [],
      form: {
        name: '',
        access: []
      },
      accessData: [],
      props: {
        label: function(data, node) {
          return data.meta.title
        },
        children: 'children'
      },
      count: 1,
      rules: {
        name: [
          { required: true, message: '请输入角色名', trigger: 'blur' },
          { max: 15, message: '长度在 1 到 15 个字符', trigger: 'blur' }
        ]
      }
    }
  },
  created() {
    let routeList = [
      { ...userRouter },
      { ...businessRouter },
      { ...contentRouter },
      { ...mobileRouter },
      { ...statRouter },
      { ...applicationRouter },
      { ...toolRouter },
      { ...systemRouter }
    ]
    routeList = this.routeTree(routeList)
    this.accessData = [...routeList]
  },
  methods: {
    routeTree(list) {
      const newlist = [...list]
      const that = this
      for (let i = 0; i < newlist.length; i++) {
        // let res = newlist[i];
        const res = { ...newlist[i] }
        if (res.hidden !== undefined && res.hidden === true) {
          newlist.splice(i, 1)
          i--
        } else {
          if (res.children && res.children.length > 0) {
            res.children = that.routeTree(res.children)
            newlist[i] = res
          }
        }
      }
      return newlist
    },
    handleCheckChange(data, checked, indeterminate) {
      // console.log(data, checked, indeterminate);
      const harfCheckedKeys = [...this.$refs.tree.getHalfCheckedKeys()]
      const checkedKeys = [...this.$refs.tree.getCheckedKeys()]
      this.form.access = {
        harfCheckedKeys: harfCheckedKeys,
        checkedKeys: checkedKeys
      }
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          apiAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/sys/admin/role')
              }, 1500)
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    goto(target) {
      this.$router.push(target)
    }
  }
}
</script>
