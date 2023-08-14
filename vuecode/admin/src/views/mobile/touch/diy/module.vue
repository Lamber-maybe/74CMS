<template>
  <div class="app-container mobile-module">
    <el-row :gutter="20">
      <el-col :span="5">
        <div class="preview_block">
          <el-card class="box-card">
            <div slot="header" class="clearfix">
              <span>效果预览</span>
            </div>
            <img
              v-if="header.is_display == 1"
              class="preview_img"
              :src="'static/index_module/header/' + header.plan_id + '.jpg'"
            >
            <img
              v-if="menu.is_display == 1"
              class="preview_img"
              :src="'static/index_module/menu/' + menu.plan_id + '.jpg'"
            >
            <div v-if="section.is_display == 1" class="item-section">
              <img
                v-for="(item,index) in section.plan_id"
                :key="index"
                class="preview_img_section"
                :src="'static/index_module/section/' + item + '.jpg'"
              >
            </div>

            <div v-if="notice.is_display == 1" class="divider-line" />
            <img
              v-if="notice.is_display == 1"
              class="preview_img"
              :src="'static/index_module/notice/' + notice.plan_id + '.jpg'"
            >
            <div v-if="famous.is_display == 1" class="divider-line" />
            <img
              v-if="famous.is_display == 1"
              class="preview_img"
              :src="'static/index_module/famous/' + famous.plan_id + '.jpg'"
            >
            <div v-if="hotword.is_display == 1" class="divider-line" />
            <img
              v-if="hotword.is_display == 1"
              class="preview_img"
              :src="'static/index_module/hotword/' + hotword.plan_id + '.jpg'"
            >
            <div v-if="joblist.is_display == 1" class="divider-line" />
            <img
              v-if="joblist.is_display == 1"
              class="preview_img"
              :src="'static/index_module/joblist/' + joblist.plan_id + '.jpg'"
            >
            <div v-if="article.is_display == 1" class="divider-line" />
            <img
              v-if="article.is_display == 1"
              class="preview_img"
              :src="'static/index_module/article/1.jpg'"
            >
          </el-card>
        </div>
      </el-col>
      <el-col :span="19">
        <el-form ref="form" label-width="80px">
          <el-card shadow="never" style="max-height:650px;overflow-y:auto">
            <div style="padding:20px;">
              <div class="module_tit">头部</div>
              <el-form-item label="显示状态">
                <el-radio-group v-model="header.is_display">
                  <el-radio label="1">
                    显示
                  </el-radio>
                  <el-radio label="0">
                    隐藏
                  </el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item label="选择布局">
                <el-radio-group v-model="header.plan_id">
                  <el-radio label="1">
                    普通头部
                  </el-radio>
                  <el-radio label="2">
                    带logo头部
                  </el-radio>
                  <el-radio label="3">
                    纯色头部
                  </el-radio>
                  <el-radio label="4">
                    搜索边压
                  </el-radio>
                </el-radio-group>
              </el-form-item>
              <el-divider />
              <div class="module_tit">菜单栏</div>
              <el-form-item label="显示状态">
                <el-radio-group v-model="menu.is_display">
                  <el-radio label="1">
                    显示
                  </el-radio>
                  <el-radio label="0">
                    隐藏
                  </el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item label="选择布局">
                <el-radio-group v-model="menu.plan_id">
                  <el-radio label="1">
                    两行10图标1
                  </el-radio>
                  <el-radio label="4">
                    两行10图标2
                  </el-radio>
                  <el-radio label="2">
                    单行5图标
                  </el-radio>
                  <el-radio label="3">
                    单行4图标
                  </el-radio>
                </el-radio-group>
              </el-form-item>
              <el-divider />
              <div class="module_tit">版块</div>
              <el-form-item label="显示状态">
                <el-radio-group v-model="section.is_display">
                  <el-radio label="1">
                    显示
                  </el-radio>
                  <el-radio label="0">
                    隐藏
                  </el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item label="选择版块">
                <el-checkbox-group
                  v-model="section_list"
                  :max="2"
                  @change="fun_change_section_selected"
                >
                  <el-checkbox label="company">知名企业</el-checkbox>
                  <el-checkbox label="nearby">附近职位</el-checkbox>
                  <el-checkbox label="high_wage">高薪职位</el-checkbox>
                </el-checkbox-group>
              </el-form-item>
              <el-divider />
              <div class="module_tit">公告栏</div>
              <el-form-item label="显示状态">
                <el-radio-group v-model="notice.is_display">
                  <el-radio label="1">
                    显示
                  </el-radio>
                  <el-radio label="0">
                    隐藏
                  </el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item label="选择布局">
                <el-radio-group v-model="notice.plan_id">
                  <el-radio label="1">
                    单行滚动式
                  </el-radio>
                  <el-radio label="2">
                    双行滚动式
                  </el-radio>
                </el-radio-group>
              </el-form-item>
              <el-divider />
              <div class="module_tit">名企栏</div>
              <el-form-item label="显示状态">
                <el-radio-group v-model="famous.is_display">
                  <el-radio label="1">
                    显示
                  </el-radio>
                  <el-radio label="0">
                    隐藏
                  </el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item label="选择布局">
                <el-radio-group v-model="famous.plan_id">
                  <el-radio label="1">
                    方块滚动式
                  </el-radio>
                  <el-radio label="2">
                    横块滚动式
                  </el-radio>
                  <el-radio label="3">
                    两行6个线式
                  </el-radio>
                  <el-radio label="4">
                    三行9个块式
                  </el-radio>
                </el-radio-group>
              </el-form-item>
              <el-divider />
              <div class="module_tit">热门职位</div>
              <el-form-item label="显示状态">
                <el-radio-group v-model="hotword.is_display">
                  <el-radio label="1">
                    显示
                  </el-radio>
                  <el-radio label="0">
                    隐藏
                  </el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item label="选择布局">
                <el-radio-group v-model="hotword.plan_id">
                  <el-radio label="1">
                    边框式
                  </el-radio>
                  <el-radio label="2">
                    背景块式
                  </el-radio>
                  <el-radio label="3">
                    纯字式
                  </el-radio>
                </el-radio-group>
              </el-form-item>
              <el-divider />
              <div class="module_tit">信息列表</div>
              <el-form-item label="显示状态">
                <el-radio-group v-model="joblist.is_display">
                  <el-radio label="1">
                    显示
                  </el-radio>
                  <el-radio label="0">
                    隐藏
                  </el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item label="选择布局">
                <el-radio-group v-model="joblist.plan_id">
                  <el-radio label="1">
                    精准推荐1
                  </el-radio>
                  <el-radio label="2">
                    精准推荐2
                  </el-radio>
                  <el-radio label="3">
                    职位块列表1
                  </el-radio>
                  <el-radio label="4">
                    综合列表
                  </el-radio>
                  <el-radio label="5">
                    职位块列表2
                  </el-radio>
                  <el-radio label="6">
                    职位分类
                  </el-radio>
                </el-radio-group>
              </el-form-item>
              <el-divider />
              <div class="module_tit">新闻资讯</div>
              <el-form-item label="显示状态">
                <el-radio-group v-model="article.is_display">
                  <el-radio label="1">
                    显示
                  </el-radio>
                  <el-radio label="0">
                    隐藏
                  </el-radio>
                </el-radio-group>
              </el-form-item>
            </div>
          </el-card>
          <el-form-item style="margin-top:20px">
            <el-button type="primary" @click="onSubmit">保存</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import { setMobileIndexModule } from '@/api/configuration'
export default {
  data() {
    return {
      section_list: [],
      header: {},
      menu: {},
      section: {},
      notice: {},
      famous: {},
      hotword: {},
      joblist: {},
      article: {}
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fun_change_section_selected() {
      this.section.plan_id = this.section_list
    },
    fetchInfo() {
      setMobileIndexModule({}, 'get')
        .then(response => {
          const {
            header,
            menu,
            section,
            notice,
            famous,
            hotword,
            joblist,
            article
          } = {
            ...response.data
          }
          this.header = header
          this.header.is_display = this.header.is_display + ''
          this.menu = menu
          this.menu.is_display = this.menu.is_display + ''
          this.section = section
          this.section.is_display = this.section.is_display + ''
          this.section.plan_id = this.section.plan_id.split(',')
          this.section_list = this.section.plan_id
          this.notice = notice
          this.notice.is_display = this.notice.is_display + ''
          this.famous = famous
          this.famous.is_display = this.famous.is_display + ''
          this.hotword = hotword
          this.hotword.is_display = this.hotword.is_display + ''
          this.joblist = joblist
          this.joblist.is_display = this.joblist.is_display + ''
          this.article = article
          this.article.is_display = this.article.is_display + ''
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      const insertData = {
        header: that.header,
        menu: that.menu,
        section: that.section,
        notice: that.notice,
        famous: that.famous,
        hotword: that.hotword,
        joblist: that.joblist,
        article: that.article
      }
      setMobileIndexModule(insertData)
        .then(response => {
          this.$message.success(response.message)
          return true
        })
        .catch(() => {})
    }
  }
}
</script>
<style>
.mobile-module .el-divider--horizontal {
  margin: 20px 0;
}
.mobile-module .el-form-item {
  margin-bottom: 0;
}
.mobile-module .el-card__body {
  padding: 0;
}
.mobile-module .divider-line {
  width: 100%;
  height: 4px;
  background-color: #f3f3f3;
}
.mobile-module .preview_block {
  max-width: 300px;
}
.mobile-module .preview_img {
  width: 100%;
  border: 0;
  margin: 0;
  display: block;
}
.mobile-module .item-section {
  width: 94%;
  margin: 0 auto;
}
.mobile-module .preview_img_section {
  width: 50%;
  border: 0;
  padding: 2px;
}
.mobile-module .module_tit {
  margin-bottom: 20px;
  border-left: 6px solid #409eff;
  padding-left: 10px;
}
</style>
