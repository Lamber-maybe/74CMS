<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>编辑角色</span>
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
        v-loading="infoLoading"
        class="common-form"
        :model="form"
        label-width="120px"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item label="角色名称" prop="name">
          <el-input v-model="form.name" class="middle" />
        </el-form-item>
        <el-form-item label="pc后台权限">
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
        <el-form-item label="移动端后台权限">
          <el-tree
            ref="treeMobile"
            node-key="alias"
            :data="accessDataMobile"
            :props="propsMobile"
            show-checkbox
            :default-checked-keys="checkKeysMobile"
            @check-change="handleCheckChangeMobile"
          />
        </el-form-item>
        <el-form-item label="操作权限">
          <el-checkbox v-model="form.access_export">导出数据</el-checkbox>
          <el-checkbox v-model="form.access_delete">删除数据</el-checkbox>
<!--          <el-checkbox v-model="form.access_set_service">分配客服</el-checkbox>-->
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
import { apiEdit } from '@/api/admin_role'
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
      infoLoading: true,
      checkKeys: [],
      checkKeysMobile: [],
      form: {
        name: '',
        access: [],
        access_mobile: [],
        access_export: false,
        access_delete: false,
        access_set_service: false
      },
      accessData: [],
      accessDataMobile: [
        { label: '会员管理', alias: 'member', children: [
          { label: '企业会员', alias: 'member_company' },
          { label: '个人会员', alias: 'member_personal' },
          { label: '无效会员', alias: 'member_invalid' },
          { label: '简历管理', alias: 'resume_manage' },
          { label: '照片/作品', alias: 'resume_img' },
          { label: '企业管理', alias: 'company_manage' },
          { label: '职位管理', alias: 'job_manage' },
          { label: '企业风采', alias: 'company_img' },
          { label: '账号注销申请', alias: 'cancel_apply' }
        ] },
        { label: '业务管理', alias: 'business', children: [
          { label: '(企业)套餐管理', alias: 'setmeal' },
          { label: '(企业)订单管理', alias: 'order_company' },
          { label: '(企业)开通套餐', alias: 'setmeal_add' },
          { label: '(企业)新增推广', alias: 'promotion_job_add' },
          { label: '(企业)积分增减', alias: 'points_company' },
          { label: '(个人)订单管理', alias: 'order_personal' },
          { label: '(个人)新增推广', alias: 'promotion_resume_add' }
        ] },
        { label: '系统工具', alias: 'system', children: [
          { label: '更新缓存', alias: 'clearcache' },
          { label: '网站启停', alias: 'site_status' },
          { label: '暂停注册', alias: 'reg_status' }
        ] }
      ],
      props: {
        label: function(data, node) {
          return data.meta.title
        },
        children: 'children'
      },
      propsMobile: {
        label: 'label',
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
    this.fetchInfo()
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
    fetchInfo() {
      this.infoLoading = true
      const param = { id: this.$route.query.id }
      apiEdit(param, 'get')
        .then(response => {
          this.form = { ...response.data }
          this.checkKeys = this.form.access == '' ? [] : [...this.form.access.checkedKeys]
          this.checkKeysMobile = this.form.access_mobile == '' ? [] : [...this.form.access_mobile.checkedKeys]
          this.infoLoading = false
        })
        .catch(() => {})
    },
    handleCheckChange(data, checked, indeterminate) {
      // console.log(data, checked, indeterminate);
      const harfCheckedKeys = [...this.$refs.tree.getHalfCheckedKeys()]
      const checkedKeys = [...this.$refs.tree.getCheckedKeys()]
      this.form.access = {
        harfCheckedKeys: harfCheckedKeys,
        checkedKeys: checkedKeys
      }
      // console.log(this.form.access)
    },
    handleCheckChangeMobile(data, checked, indeterminate) {
      const harfCheckedKeys = [...this.$refs.treeMobile.getHalfCheckedKeys()]
      const checkedKeys = [...this.$refs.treeMobile.getCheckedKeys()]
      this.form.access_mobile = {
        harfCheckedKeys: harfCheckedKeys,
        checkedKeys: checkedKeys
      }
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...this.form }
      insertData.access_export = insertData.access_export === true ? 1 : 0
      insertData.access_delete = insertData.access_delete === true ? 1 : 0
      insertData.access_set_service = insertData.access_set_service === true ? 1 : 0
      this.$refs[formName].validate(valid => {
        if (valid) {
          apiEdit(insertData)
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
