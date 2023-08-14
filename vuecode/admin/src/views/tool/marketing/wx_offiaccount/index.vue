<template>
  <div class="app-container">
    <addTemplate
      v-if="templateFlag"
      :template-id="templateId"
      :template-type="templateType"
    />

    <el-tabs v-else v-model="tabActive" type="border-card">
      <el-tab-pane label="公众号营销" name="1">
        <div class="tip">
          <p>快速筛选出符合条件的职位进行复制到微信公众号中推送；</p>
          <p>职位默认按刷新时间排序；</p>
          <p>
            使用公众号二维码，用户需先关注公众号后才能查看，二维码有效期为30天；
          </p>
          <p>
            使用普通二维码，用户无需关注公众号直接识别即可打开触屏链接，二维码永久有效
          </p>
        </div>
        <!--<showform v-show="chooseDone===true" :datalist="datalist" :type="data.type" @goback="chooseDone=false" />-->
        <div v-if="tabActive == 1" class="el-row--flex">
          <el-form
            v-show="chooseDone === false"
            class="common-form"
            label-width="100px"
            style="width: 60%"
          >
            <el-form-item label="信息类型" style="margin-bottom: 20px">
              <el-radio-group v-model="data.type" @change="radioChange">
                <el-radio label="job_list"> 职位列表 </el-radio>
                <el-radio label="company_list"> 企业列表 </el-radio>
                <!--<el-radio label="resumelist">-->
                <!--简历列表-->
                <!--</el-radio>-->
                <el-radio label="appoint_company"> 指定企业 </el-radio>
              </el-radio-group>
            </el-form-item>
            <components :is="data.type" ref="conditionData" />
            <el-form-item label="">
              <el-button
                type="primary"
                :loading="progressFlag"
                @click="onSubmit('form')"
              >
                搜索并添加
              </el-button>
            </el-form-item>
          </el-form>

          <div class="rightList">
            <span class="rightTitle"><b>推文列表</b>（拖拽可排序）</span>
            <div
              v-if="progressFlag"
              style="width: 100%;height: 100%;background: rgba(0,0,0,.5);padding: 50% 10% 0 10%;"
            >
              <el-row>
                <el-progress
                  :text-inside="true"
                  :stroke-width="17"
                  :percentage="progressPercent"
                />
              </el-row>
            </div>
            <div
              v-if="!progressFlag && datalist.length == 0"
              class="autos emptyImg"
            >
              <img src="../../../../assets/images/empty.jpg" alt="">
              <span class="words">暂无数据，请点击搜索并添加</span>
            </div>
            <el-checkbox-group
              v-if="!progressFlag && datalist.length > 0"
              v-model="checkList"
              @change="checkChange"
            >
              <draggable
                v-model="datalist"
                class="autos"
                :options="{ animation: 200 }"
                @update="datadragEnd"
              >
                <div
                  v-for="(item, index) of datalist"
                  :key="index"
                  class="dataCard"
                >
                  <el-checkbox :label="JSON.stringify(item)" class="checkBox">
                    <div class="left">
                      <div class="inner">
                        <span class="title">{{
                          data.type == "job_list"
                            ? item.job_name
                            : item.company_name
                        }}</span>
                        <div v-if="data.type == 'job_list'" class="content">
                          {{
                            item.company_name ? item.company_name : "公司异常"
                          }}
                        </div>
                        <div v-else class="content">
                          <span
                            v-if="item.job_list.length > 0"
                          ><b>{{ item.job_list[0].job_name }}</b>等{{ item.job_list.length }}个职位</span>
                          <span v-else>暂无发布职位</span>
                        </div>
                      </div>
                      <span>ID：{{
                        data.type == "job_list"
                          ? item.job_id
                          : item.company_id
                      }}</span>
                    </div>
                  </el-checkbox>
                </div>
              </draggable>
            </el-checkbox-group>
            <div class="rightBottom">
              <div>
                <el-checkbox
                  v-model="checkAll"
                  :disabled="datalist.length > 0 ? false : true"
                  :indeterminate="isIndeterminate"
                  @change="handleCheckAllChange"
                >全选</el-checkbox>
                <span
                  style="margin-left: 16px; font-size: 14px; color: #999999"
                >当前已选{{ checkList.length }}条</span>
              </div>
              <div>
                <el-button
                  size="small"
                  type="danger"
                  @click="delClick"
                >删除</el-button>
                <el-button
                  size="small"
                  type="primary"
                  @click="releaseClick"
                >发布预览</el-button>
              </div>
            </div>
          </div>
        </div>
      </el-tab-pane>
      <el-tab-pane label="模板管理" name="2">
        <wx-template v-if="tabActive == 2" @addTeamplate="addTeamplate" />
      </el-tab-pane>
    </el-tabs>
    <div class="table_popup">
      <el-drawer
        :visible.sync="drawer"
        size="50%"
        :show-close="false"
        :wrapper-closable="false"
      >
        <div slot="title">模板列表</div>
        <div class="popup_box">
          <el-card shadow="never" :body-style="{ padding: '10px 20px 20px' }">
            <div slot="header">1.选择模板</div>
            <el-radio-group
              v-model="radio"
              class="radioGroup"
              size="small"
              @change="templateRadioChange"
            >
              <el-radio
                v-for="(item, index) of templateList"
                :key="index"
                class="radios"
                :label="index"
              >{{ item.name }}</el-radio>
            </el-radio-group>
          </el-card>
          <el-card shadow="never" class="top10">
            <div slot="header">2.复制使用</div>
            <el-card
              shadow="never"
              class="top10"
              :body-style="{ padding: '10px 20px' }"
              style="background: #f7f8f9; font-size: 14px"
            >
              推荐复制内容后，在微信后台编辑器或135等排版编辑器中使用或二次编辑
            </el-card>
            <el-card
              shadow="never"
              class="top10"
              :body-style="{ padding: '10px 20px' }"
              style="background: #fdf6ec; color: #e6a23c; font-size: 14px"
            >
              部分模板存在图片资源，建议关闭微信后台设置中的【图片水印】功能
            </el-card>

            <el-button
              type="primary"
              class="top34"
              plain
              @click="copy"
            >一键复制</el-button>
          </el-card>
          <!-- 关闭按钮 -->
          <div class="close" @click="handleClose">
            <i class="el-icon-close" />
          </div>
        </div>
      </el-drawer>
    </div>
    <div v-if="drawer" class="iphone">
      <div class="iphoneInner" v-html="preview" />
    </div>
  </div>
</template>

<script>
import job_list from './components/joblist.vue'
import company_list from './components/companylist.vue'
// import resumelist from './components/resumelist.vue'
import appoint_company from './components/appointCompany.vue'
// import showform from './components/showform.vue'
import wxTemplate from './template/index'
import { marketingSearch } from '@/api/marketing_wxoffiaccount'
import draggable from 'vuedraggable'
import addTemplate from './template/add'

import { templateOption, wxTemplateList } from '@/api/marketing_wxoffiaccount'
import { parseTime } from '@/utils'
export default {
  components: {
    job_list,
    company_list,
    // resumelist,
    appoint_company,
    // showform,
    draggable,
    wxTemplate,
    addTemplate
  },
  data() {
    return {
      // 进度条初始值
      initVal: 0,
      // 进度条增加数量
      step: 5,
      // 进度条停止值
      stopVal: 100,
      chooseDone: false,
      submiting: false,
      data: {
        type: 'job_list',
        condition: {
          jobcategory: [],
          district: [],
          trade: [],
          tag: [],
          wage: [],
          setmeal_id: [],
          content: 'refreshtime',
          refreshtime: -1,
          num: 10,
          page_num: 1
        }
      },
      totalList: 0,
      datalist: [],
      checkList: [],

      checkAll: false,
      isIndeterminate: false,

      dragList: [],
      tabActive: '1',

      templateFlag: false,
      templateId: '',
      templateType: 'add',
      drawer: false,

      // 头尾数据
      common: {},
      // 模板对象
      templateObj: {},
      // 预览对象
      preview: '',
      // 模板列表参数
      templateParams: {
        page_size: '100',
        page_num: '1',
        type: '1'
      },
      // 模板列表
      templateList: [],
      // 选择模板
      radio: '',

      headOption: {},
      tailOption: {},
      jobBodyOption: {},
      companyBodyOption: {},
      progressFlag: false,
      progressPercent: 0,
      /**
       * 【bug】公众号营销生成条数条件改变无效
       * zch 2022.10.10
       * 新增条件判断
       * */
      is_concat: false

    }
  },
  created() {
    this.templateOptionFun()
  },
  mounted() {},
  methods: {
    templateOptionFun() {
      templateOption().then((res) => {
        const { code, data, message } = res
        if (code == '200') {
          this.headOption = data.head_option
          this.tailOption = data.tail_option
          this.jobBodyOption = data.body_option.job
          this.companyBodyOption = data.body_option.company
        } else {
          this.$message.error(message)
        }
      })
    },
    // 模板列表
    wxTemplateListFun() {
      if (this.data.type == 'job_list') {
        this.templateParams.type = '1'
      } else {
        this.templateParams.type = '2'
      }
      wxTemplateList(this.templateParams).then((response) => {
        this.templateList = response.data.rows
        this.radio = 0
        this.templateObj = this.templateList[0]
        this.previewFun()
      })
    },
    // 关闭弹出
    handleClose() {
      this.drawer = false
      this.templateList = []
      this.preview = ''
      this.radio = ''
    },
    // 头部/尾部数据替换
    headFootObjStr(str, obj, common) {
      let newStr = str
      for (let i = 0; i < obj.length; i++) {
        const reg = '/' + `{${obj[i].label}}` + '/g'
        const key = common[`${obj[i].value}`]
        newStr = newStr.replace(eval(reg), key)
      }
      return newStr
    },
    // 内容区数据替换
    bodyObjStr(str, obj, items) {
      let newBodyStr = ''
      for (let i = 0; i < items.length; i++) {
        let bodyStr = str
        for (let j = 0; j < obj.length; j++) {
          const reg = '/' + `{${obj[j].label}}` + '/g'
          if (
            obj[j].label == '职位福利' &&
            bodyStr.indexOf('{职位福利}') != -1
          ) {
            const arr = items[i][`${obj[j].value}`]
            let arrStr = ''
            if (arr) {
              arrStr = arr.join(',')
            }
            bodyStr = bodyStr.replace(
              /{职位福利开始}{职位福利}{职位福利结束}/g,
              arrStr
            )
          } else if (
            obj[j].label == '企业福利' &&
            bodyStr.indexOf('{企业福利}') != -1
          ) {
            const arr1 = items[i][`${obj[j].value}`]
            let arrStr1 = ''
            if (arr1) {
              arrStr1 = arr1.join(',')
            }
            bodyStr = bodyStr.replace(/{企业福利}/g, arrStr1)
          } else if (
            obj[j].label == '企业职位' &&
            bodyStr.indexOf('{职位列表开始}') != -1
          ) {
            const arr = items[i]['job_list']
            console.log(arr)
            console.log(arr.length)
            if (arr) {
              let newJob = '暂无发布职位'
              if (arr.length > 0) {
                newJob = this.jobObjStr(arr, items[i])
              }
              const newStr = bodyStr.match(
                /({职位列表开始}(.+?){职位列表结束})/g
              )[0]
              bodyStr = bodyStr.replace(newStr, newJob)
            }
          } else if (
            obj[j].label == '企业H5场景码' &&
            bodyStr.indexOf('企业H5场景码') != -1
          ) {
            const src = items[i][`${obj[j].value}`]
            if (src) {
              const reg = /{企业H5场景码\|样式=&quot;(.+?)&quot;}/g
              bodyStr = this.matchStr(reg, bodyStr, src)
            }
          } else if (
            obj[j].label == '职位H5场景码' &&
            bodyStr.indexOf('职位H5场景码') != -1
          ) {
            const src = items[i][`${obj[j].value}`]
            if (src) {
              const reg = /{职位H5场景码\|样式=&quot;(.+?)&quot;}/g
              bodyStr = this.matchStr(reg, bodyStr, src)
            }
          } else if (obj[j].label == '企业小程序链接') {
            const src = items[i][`${obj[j].value}`]
            if (src) {
              bodyStr = bodyStr.replace(/{企业小程序跳转}/g, src)
            }
            bodyStr = bodyStr.replace(
              /{小程序APPID}/g,
              this.common.mini_program_appid
            )
          } else if (obj[j].label == '职位小程序链接') {
            const src = items[i][`${obj[j].value}`]
            if (src) {
              bodyStr = bodyStr.replace(/{职位小程序跳转}/g, src)
            }
            bodyStr = bodyStr.replace(
              /{小程序APPID}/g,
              this.common.mini_program_appid
            )
          } else if (obj[j].label == '企业H5链接') {
            const src = items[i][`${obj[j].value}`]
            if (src) {
              bodyStr = bodyStr.replace(/{企业H5链接}/g, src)
            }
          } else if (obj[j].label == '职位H5链接') {
            const src = items[i][`${obj[j].value}`]
            if (src) {
              bodyStr = bodyStr.replace(/{职位H5链接}/g, src)
            }
          } else if (obj[j].label == '网站名称') {
            bodyStr = bodyStr.replace(
              /{网站名称}/g,
              this.common.site_name || ''
            )
          } else if (obj[j].label == '网站地址') {
            bodyStr = bodyStr.replace(
              /{网站地址}/g,
              this.common.site_domain || ''
            )
          } else if (obj[j].label == '当期日期') {
            bodyStr = bodyStr.replace(
              /{当期日期}/g,
              parseTime(this.common.date, '{y}-{m}-{d}') || ''
            )
          } else if (obj[j].label == '职位描述') {
            const key = items[i][`${obj[j].value}`]
            if (key) {
              bodyStr = bodyStr.replace(
                eval(reg),
                '<p style="white-space: pre-wrap">' + key + '</p>'
              )
            }
          } else if (obj[j].label == '职位职责') {
            const key = items[i][`${obj[j].value}`]
            if (key) {
              bodyStr = bodyStr.replace(
                eval(reg),
                '<p style="white-space: pre-wrap">' + key + '</p>'
              )
            }
          } else {
            const key = items[i][`${obj[j].value}`]
            if (key) {
              bodyStr = bodyStr.replace(eval(reg), key || '')
            } else {
              bodyStr = bodyStr.replace(eval(reg), `{${obj[j].label}}`)
            }
          }
        }
        newBodyStr += bodyStr
      }
      return newBodyStr
    },
    // 替换场景码函数
    matchStr(reg, bodyStr, src) {
      const newStr = bodyStr.replace(reg, function (matchStr, group1) {
        if (src == '' || !src) {
          return (matchStr = '')
        } else {
          return (matchStr = '<img src="' + src + '" style=' + group1 + '>')
        }
      })
      return newStr
    },
    // 企业职位的遍历
    jobObjStr(arr, newArr) {
      const bodyStr = this.templateObj.body
      let newStr = ''
      const reg = /({职位列表开始}(.+?){职位列表结束})/g
      const that = this
      bodyStr.replace(reg, function (matchStr, group1, offset, string) {
        newStr = that.bodyObjStr(offset, that.companyBodyOption, [newArr])
      })
      return this.bodyObjStr(newStr, this.companyBodyOption, arr)
    },
    radioChange(event) {
      this.progressPercent = 0
      this.datalist = []
      this.checkList = []
      this.checkAll = false
      this.isIndeterminate = false
    },
    // 选择
    checkChange(value) {
      const checkedCount = value.length
      // this.checkAll = checkedCount === this.datalist.length;
      if (checkedCount === this.datalist.length) {
        this.checkAll = true
      } else {
        this.checkAll = false
      }
      this.isIndeterminate =
        checkedCount > 0 && checkedCount < this.datalist.length
    },
    // // 全选
    handleCheckAllChange(val) {
      const dataList = JSON.parse(JSON.stringify(this.datalist))
      for (let i = 0; i < dataList.length; i++) {
        dataList[i] = JSON.stringify(dataList[i])
      }
      this.checkList = val ? dataList : []
      this.isIndeterminate = false
    },
    async datadragEnd(evt) {
      evt.preventDefault()
    },
    // 删除
    delClick() {
      if (this.checkList.length == 0) {
        this.$message.error('请选择要删除项~')
      } else {
        this.$confirm(
          '此操作将删除选中信息,如需恢复数据，请点击生成按钮，是否继续?',
          '提示',
          {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }
        )
          .then(() => {
            const checkList = []
            for (let i = 0; i < this.checkList.length; i++) {
              checkList.push(JSON.parse(this.checkList[i]))
            }
            let arr = []
            if (this.data.type == 'job_list') {
              arr = this.datalist.filter(
                (item) => !checkList.some((ele) => ele.job_id === item.job_id)
              )
            } else {
              arr = this.datalist.filter(
                (item) =>
                  !checkList.some((ele) => ele.company_id === item.company_id)
              )
            }
            this.datalist = arr
            this.checkList = []
            this.checkAll = false
            this.isIndeterminate = false
          })
          .catch(() => {})
      }
    },
    // 发布预览
    releaseClick() {
      if (this.checkList.length == 0) {
        this.$message.error('请选择需展示的推文！')
      } else {
        this.drawer = true
        this.checkList = this.sortList(this.checkList)

        this.wxTemplateListFun()
      }
    },
    // 处理最终排序问题
    sortList(list) {
      const that = this
      list.sort(function (a, b) {
        if (that.data.type == 'job_list') {
          return that.datalist
            .map((s) => s.job_id)
            .indexOf(JSON.parse(a).job_id) <
            that.datalist.map((s) => s.job_id).indexOf(JSON.parse(b).job_id)
            ? -1
            : 1
        } else {
          return that.datalist
            .map((s) => s.company_id)
            .indexOf(JSON.parse(a).company_id) <
            that.datalist
              .map((s) => s.company_id)
              .indexOf(JSON.parse(b).company_id)
            ? -1
            : 1
        }
      })
      return list
    },
    // 选择模板
    templateRadioChange(row) {
      this.templateObj = this.templateList[row]
      this.previewFun()
    },
    // 生成预览
    previewFun() {
      const bodyArr = JSON.parse(JSON.stringify(this.checkList))
      for (let i = 0; i < bodyArr.length; i++) {
        bodyArr[i] = JSON.parse(bodyArr[i])
      }
      const headStr = this.templateObj.head
      const tailStr = this.templateObj.tail
      const bodyStr = this.templateObj.body
      this.common.date = parseTime(this.common.date, '{y}-{m}-{d}')
      const newHead = this.headFootObjStr(
        headStr,
        this.headOption,
        this.common
      )
      const newTail = this.headFootObjStr(
        tailStr,
        this.tailOption,
        this.common
      )
      let newBody = ''
      if (this.data.type == 'job_list') {
        newBody = this.bodyObjStr(bodyStr, this.jobBodyOption, bodyArr)
      } else {
        newBody = this.bodyObjStr(bodyStr, this.companyBodyOption, bodyArr)
      }
      this.preview = newHead + newBody + newTail
    },
    // 复制
    copy() {
      const copyHandler = this.copys(this.preview)
      document.addEventListener('copy', copyHandler)
      document.execCommand('copy')
      document.removeEventListener('copy', copyHandler)
      this.$message.success('复制成功')
    },
    copys(article) {
      return function (event) {
        event.clipboardData.setData('text/html', article)
        event.preventDefault()
      }
    },
    onSubmit() {
      const that = this
      if (
        that.data.type == 'appoint_company' &&
        that.$refs.conditionData.form.company_ids.length === 0
      ) {
        that.$message.error('请选择企业')
        that.submiting = false
        return false
      }
      // that.chooseDone = true
      if (that.submiting === true) {
        return false
      }
      // 显示进度条
      this.progressFlag = true
      // 初始化后加载进度，加载到百分之多少由stopVal决定
      // this.progressPercent = this.initVal
      // const step = this.step
      // 每500ms执行一次函数（进度条加step=5%）

      that.submiting = true
      that.data.condition = { ...that.$refs.conditionData.form }
      this.getList()
    },
    getList(){
      this.progressPercent = 0 // 重置
      this.data.condition.page_num = 1
      this.data.condition.num = this.$refs.conditionData.form.num
      const nums = this.$refs.conditionData.form.num
      this.totalList = nums / 10
      this.getData()
    },
    getData(){
      marketingSearch(this.data)
        .then((response) => {
          this.submiting = false
          /**
           * 【bug】公众号营销生成条数条件改变无效
           * zch 2022.10.10
           * 新增条件判断
           * */
          if (this.is_concat === true) {
            this.datalist = this.datalist.concat(response.data.items)
          } else {
            this.datalist = response.data.items
          }
          this.common = response.data.common
          this.checkList = []
          if (response.code == 200) {
            this.data.condition.page_num++
            var time = null
            if (this.data.condition.page_num <= this.totalList && response.data.items.length == 10){
              const progressPercentData = parseInt(100 / this.totalList) * this.data.condition.page_num
              time = setInterval(() => {
                if (this.progressPercent < progressPercentData) {
                  this.progressPercent += 1
                } else {
                  this.progressPercent = this.progressPercent
                  clearInterval(time)
                }
              }, 200)
              // this.progressPercent = parseInt(100 / this.totalList) * this.data.condition.page_num
              /**
               * 【bug】公众号营销生成条数条件改变无效
               * zch 2022.10.10
               * 新增条件判断
               * */
              this.is_concat = true
              this.getData()
            } else {
              this.progressPercent = 100
              this.progressFlag = false
              /**
               * 【bug】公众号营销生成条数条件改变无效
               * zch 2022.10.10
               * 新增条件判断
               * */
              this.is_concat = false
              clearInterval(time)
            }
          }
        })
        .catch(() => {
          this.submiting = false
        })
    },
    addTeamplate(row) {
      this.templateFlag = !this.templateFlag
      if (row) {
        this.templateId = row.id
        this.templateType = row.name
      } else {
        this.templateId = ''
        this.templateType = 'add'
      }
    }
  }
}
</script>
<style scoped lang="scss">
.rightList {
  width: 40%;
  border: 1px solid #f7f8f9;
  height: 700px;
  overflow-y: auto;
  overflow-x: hidden;
  padding: 20px 20px 60px;
  box-sizing: border-box;
  position: relative;
  background: #fff;
  .rightTitle {
    font-size: 14px;
    color: #999999;
    display: block;
    margin-bottom: 10px;
    b {
      color: #333;
      font-size: 16px;
    }
  }
}
.dataCard {
  width: 100%;
  height: auto;
  overflow: hidden;
  font-size: 16px;
  background: #f7f8f9;
  border-radius: 4px;
  cursor: pointer;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  .bottom12 {
    margin-bottom: 12px;
  }
  .content {
    display: block;
    margin-top: 8px;
    font-size: 12px;
    color: #999999;
    b {
      margin-right: 4px;
    }
  }
  .padding25 {
    padding-left: 25px;
  }
  .checkBox {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    flex-direction: row;
    padding: 16px 15px;

    ::v-deep .el-checkbox__label {
      width: 100%;
    }
  }
  .left {
    width: 100%;
    display: flex;
    flex-direction: row;
    align-items: flex-start;
    justify-content: space-between;
    text-align: end;
    font-size: 12px;
    color: #666666;
    .inner {
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      align-items: flex-start;
      font-size: 14px;
      color: #111;
      line-height: 20px;
      .title {
        font-weight: 600;
      }
    }
  }
}

.rightBottom {
  position: absolute;
  bottom: 0;
  left: 0;
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-between;
  padding: 10px;
  box-sizing: border-box;
  background: #f7f8f9;
  width: 100%;
}

.iphone {
  width: 350px;
  height: 700px;

  background: red;
  position: fixed;
  left: 15%;
  top: 10%;
  border-radius: 45px;
  background: url("../../../../assets/images/iphone.png") no-repeat 0 0;
  background-size: 100%;
  padding: 92px 26px 40px;
  box-sizing: border-box;
  z-index: 3000;
  .iphoneInner {
    width: 100%;
    height: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    font-size: 14px;
    line-height: 1;
    white-space: normal;
    word-break: break-all;
    p {
      word-break: break-all;
    }
    a {
      white-space: normal;
      word-break: break-all;
    }
  }
  .preview {
    position: absolute;
    bottom: -50px;
    left: 110px;
    margin: auto;
  }
}
.popup_box {
  height: 100%;
  padding: 0 20px;
}
.close {
  width: 40px;
  height: 60px;
  position: absolute;
  left: -40px;
  top: 50%;
  transform: translateY(-50%);
  background: #f5f5f5;
  border-radius: 8px 0 0 8px;
  cursor: pointer;
  .el-icon-close {
    font-size: 30px;
    color: #777;
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
  }
}
.top10 {
  margin-top: 10px;
}
.top34 {
  margin-top: 34px;
}
.autos {
  height: 588px;
  overflow-y: auto;
}
.emptyImg {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  .words {
    font-size: 13px;
    color: #999;
    margin-top: 5px;
  }
}
.radioGroup {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  .radios {
    margin-top: 10px;
  }
}
</style>
