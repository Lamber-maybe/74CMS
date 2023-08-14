<template>
  <div class="warpper" v-loading="loading">
    <div class="save_wrapper">
      <div class="resume_preview" ref="imageDom" id="saveBox">
        <div>
          <div class="resume_name">
            {{ base_info.fullname }}的个人简历
          </div>

          <div class="data_wrapper">
            <div class="baseData">
              <div class="list">
                <div>
                  <span>姓名：</span>
                  {{ base_info.fullname }}
                </div>
              </div>
              <div class="list">
                <div>
                  <span>性别：</span>
                  {{ base_info.sex_text }}
                </div>
              </div>
              <div class="list">
                <div>
                  <span>年龄：</span>
                  {{ base_info.age }}岁
                </div>
              </div>
              <div class="list">
                <div>
                  <span>工作经验：</span>
                  {{ base_info.experience_text }}
                </div>
              </div>
              <div class="list">
                <div>
                  <span>学历：</span>
                  {{ base_info.education_text }}
                </div>
              </div>
              <div class="list" v-if="field_rule.basic.marriage !== undefined && field_rule.basic.marriage.is_display == 1 && base_info.marriage_text">
                <div>
                  <span>{{ field_rule.basic.marriage.field_cn }}：</span>
                  {{ base_info.marriage_text }}
                </div>
              </div>
              <div class="list" v-if="field_rule.basic.height !== undefined && field_rule.basic.height.is_display == 1 && base_info.height!='' && base_info.height>0">
                <div>
                  <span>{{ field_rule.basic.height.field_cn }}：</span>
                  {{ base_info.height }} CM
                </div>
              </div>
              <div class="list" v-if="field_rule.basic.major !== undefined && field_rule.basic.major.is_display == 1 && base_info.major_text">
                <div>
                  <span>{{ field_rule.basic.major.field_cn }}：</span>
                  {{ base_info.major_text }}
                </div>
              </div>
              <div class="list" v-if="field_rule.basic.householdaddress !== undefined && field_rule.basic.householdaddress.is_display == 1 && base_info.householdaddress">
                <div>
                  <span>{{ field_rule.basic.householdaddress.field_cn }}：</span>
                  {{ base_info.householdaddress }}
                </div>
              </div>
              <div class="list" v-if="field_rule.basic.residence !== undefined && field_rule.basic.residence.is_display == 1 && base_info.residence">
                <div>
                  <span>{{ field_rule.basic.residence.field_cn }}：</span>
                  {{ base_info.residence }}
                </div>
              </div>
              <div class="list" v-if="field_rule.basic.custom_field_1 !== undefined && field_rule.basic.custom_field_1.is_display == 1 && base_info.custom_field_1">
                <div>
                  <span>{{ field_rule.basic.custom_field_1.field_cn }}：</span>
                  {{ base_info.custom_field_1 }}
                </div>
              </div>
              <div class="list" v-if="field_rule.basic.custom_field_2 !== undefined && field_rule.basic.custom_field_2.is_display == 1 && base_info.custom_field_2">
                <div>
                  <span>{{ field_rule.basic.custom_field_2.field_cn }}：</span>
                  {{ base_info.custom_field_2 }}
                </div>
              </div>
              <div class="list" v-if="field_rule.basic.custom_field_3 !== undefined && field_rule.basic.custom_field_3.is_display == 1 && base_info.custom_field_3">
                <div>
                  <span>{{ field_rule.basic.custom_field_3.field_cn }}：</span>
                  {{ base_info.custom_field_3 }}
                </div>
              </div>
              <div class="clearfix"></div>

            </div>

            <div class="photo">
              <img :src="base_info.photo_img_src" />
            </div>
          </div>
        </div>

        <div class="bottom-line">
          <div class="title">求职意向</div>
          <div class="intention_list">
            <p v-for="(item, index) in intention_list" :key="index">
              <span>第{{index==0?'一':(index==1?'二':'三')}}意向：</span>
              [{{ item.district_text }}]{{ item.category_text }}，{{ item.nature_text }}{{ field_rule.intention.trade!==undefined && field_rule.intention.trade.is_display === 1 && item.trade_text ? `，${item.trade_text}` : '' }}，{{ item.wage_text }}/月
            </p>
          </div>
        </div>
        <div class="bottom-line">
          <div class="title">联系方式</div>
          <div class="intention_list" v-if="show_contact===0">
            <p>
              <span>下载后查看联系方式</span>
            </p>
          </div>
          <div class="intention_list" v-else>
            <p>
              <span>手机号：</span>{{contact_info.mobile}}
            </p>
            <p v-if="
                field_rule.contact.weixin != undefined &&
                  field_rule.contact.weixin.is_display == 1 &&
                  contact_info.weixin != ''
              ">
              <span>微信：</span>{{contact_info.weixin}}
            </p>
            <p v-if="
                field_rule.contact.email != undefined &&
                  field_rule.contact.email.is_display == 1 &&
                  contact_info.email != ''
              ">
              <span>邮箱：</span>{{contact_info.email}}
            </p>
            <p v-if="
                field_rule.contact.qq != undefined &&
                  field_rule.contact.qq.is_display == 1 &&
                  contact_info.qq != ''
              ">
              <span>QQ：</span>{{contact_info.qq}}
            </p>
          </div>
        </div>
        <div class="bottom-line" v-if="base_info.specialty != ''">
          <div class="title">自我描述</div>
          <div class="intention_list">
            <p style="white-space: pre-line;">
              {{ base_info.specialty }}
            </p>
          </div>
        </div>

        <div class="bottom-line" v-if="education_list.length > 0">
          <div class="title">教育经历</div>
          <div class="undergo_con" v-for="(item, index) in education_list" :key="index">
            <div class="undergo_title">
              <span>{{ monthTimeFilter(item.starttime) }} 至{{item.todate == 1?'今':( ' '+monthTimeFilter(item.endtime)) }}</span>
              <p>
                <span class="border">{{ item.school }}</span>
                <span class="border">{{ item.education_text }}</span>
                <span class="border" v-if="field_rule.education.major!==undefined && field_rule.education.major.is_display === 1">{{ item.major }}</span>
              </p>
            </div>
          </div>
        </div>
        <div class="bottom-line" v-if="work_list.length > 0">
          <div class="title">工作经历</div>
          <div class="undergo_con" v-for="(item, index) in work_list" :key="index">
            <div class="undergo_title">
              <span>{{ monthTimeFilter(item.starttime) }} 至{{item.todate == 1?'今':( ' '+monthTimeFilter(item.endtime)) }}</span>
              <p>
                <span class="border">{{ item.companyname }}</span>
                <span class="border">{{ item.jobname }}</span>
              </p>
            </div>
            <div class="undergo_list">
              <p><span>工作内容：</span>{{ item.duty }}</p>
            </div>
          </div>
        </div>
        <div class="bottom-line" v-if="resume_module.project !== undefined && resume_module.project.is_display == 1 && project_list.length > 0">
          <div class="title">项目经历</div>
          <div class="undergo_con" v-for="(item, index) in project_list" :key="index">
            <div class="undergo_title">
              <span>{{ monthTimeFilter(item.starttime) }} 至{{item.todate == 1?'今':( ' '+monthTimeFilter(item.endtime)) }}</span>
              <p>
                <span class="border">{{ item.projectname }}</span>
                <span class="border">{{ item.role }}</span>
              </p>
            </div>
            <div class="undergo_list">
              <p><span>项目描述：</span>{{ item.description }}</p>
            </div>
          </div>
        </div>
        <div class="bottom-line" v-if="resume_module.training!==undefined && resume_module.training.is_display == 1 && training_list.length > 0">
          <div class="title">培训经历</div>
          <div class="undergo_con" v-for="(item, index) in training_list" :key="index">
            <div class="undergo_title">
              <span>{{ monthTimeFilter(item.starttime) }} 至{{item.todate == 1?'今':( ' '+monthTimeFilter(item.endtime)) }}</span>
              <p>
                <span class="border">{{ item.agency }}</span>
                <span class="border">{{ item.course }}</span>
              </p>
            </div>
            <div class="undergo_list">
              <p><span>培训内容：</span>{{ item.description }}</p>
            </div>
          </div>
        </div>
        <!-- 获得证书 -->
        <div class="certificate bottom-line" v-if="resume_module.certificate!==undefined && resume_module.certificate.is_display == 1 && certificate_list.length > 0">
          <div class="title">获得证书</div>
          <div class="certificate_list">
            <p>
              <span class="li-item" v-for="(item, index) in certificate_list" :key="index">
                <span class="border2">{{ item.name }}</span>
                <span class="border2">{{ monthTimeFilter(item.obtaintime) }}</span>
              </span>
            </p>
          </div>
        </div>
        <!-- 语言能力 -->
        <div class="language bottom-line" v-if="resume_module.language!==undefined &&resume_module.language.is_display == 1 && language_list.length > 0">
          <div class="title">语言能力</div>
          <div class="language_list">
            <p>
              <span class="li-item" v-for="(item, index) in language_list" :key="index">
                <span class="border2">{{ item.language_text }}</span>
                <span class="border2">{{ item.level_text }}</span>
              </span>
            </p>
          </div>
        </div>
      </div>

      <div class="resume_template" :class="rightFixed===true?'beFixed':''" ref="rightBox">
        <div class="resume_con">
          <div class="template_title">简历模板</div>
          <div class="imgBox">
            <div>
              <img src="../../assets/images/jlyl.jpg" />
            </div>
            <p>通用简历模板</p>
          </div>
          <el-button type="primary" round @click="handleDown">导出PDF简历</el-button>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import htmlToPdf from '@/utils/htmlToPdf'
import http from '@/utils/http'
import api from '@/api'
import { parseTime } from '@/utils/index'
export default {
  data() {
    return {
      loading:false,
      query_id:0,
      imgUrl: '',
      done: false,
      resume_module: {},
      field_rule: { basic: {}, contact: {}, intention: {}, education: {} },
      base_info: {},
      intention_list: [],
      show_contact: 0,
      show_contact_note: '',
      contact_info: {},
      work_list: [],
      education_list: [],
      project_list: [],
      training_list: [],
      language_list: [],
      certificate_list: [],
      rightFixed:false,
    }
  },
  created() {
    document.querySelector('body').setAttribute('style', 'background-color:#f3f3f3')
    this.query_id = this.$route.params.id
    this.fetchData()
  },
  mounted() {
    // 滚动条的获取
    window.addEventListener('scroll', this.getScrollTop)
   },
  methods: {
      // 监听页面滚动
      getScrollTop () {
        let scrollTop = 0
        if (document.documentElement && document.documentElement.scrollTop) {
          scrollTop = document.documentElement.scrollTop
        } else if (document.body) {
          scrollTop = document.body.scrollTop
        }
        this.rightFixed = scrollTop >= 114
      },
    handleDown () {
      window.pageYOffset = 0
      document.documentElement.scrollTop = 0
      document.body.scrollTop = 0
      htmlToPdf.downloadPDF(document.querySelector('#saveBox'), this.base_info.fullname+'的个人简历-'+this.$store.state.config.sitename)
    },
    monthTimeFilter (timestamp) {
      return parseTime(timestamp, '{y}-{m}')
    },
    fetchData () {
      const params = {
        id: this.query_id
      }
      http.get(api.resumeshow, params).then(res=>{
        const {
          resume_module,
          field_rule,
          base_info,
          intention_list,
          show_contact,
          show_contact_note,
          contact_info,
          work_list,
          education_list,
          project_list,
          training_list,
          language_list,
          certificate_list
        } = { ...res.data }
        this.resume_module = resume_module
        this.field_rule = field_rule
        this.base_info = base_info
        this.intention_list = intention_list
        this.show_contact = show_contact
        this.show_contact_note = show_contact_note
        this.contact_info = contact_info
        this.work_list = work_list
        this.education_list = education_list
        this.project_list = project_list
        this.training_list = training_list
        this.language_list = language_list
        this.certificate_list = certificate_list
      })
    }
  },
}
</script>

<style lang="scss" scoped>
.bottom-line{
    border-bottom: 1px solid #f6f6f6;
}
.clearfix{
  clear:both;
}
  .save_wrapper {
    position:relative;
    display: flex;
    padding: 20px;
  }

  .resume_preview {
    width: 880px;
    margin-right: 20px;
    background: #fff;
    padding:35px;
  }

  .resume_template {
    width: 262px;
    &.beFixed{
      position:fixed;
      left:50%;
      top:0;
      margin-left:320px;
    }
  }

  .data_wrapper {
    display: flex;
    position:relative;
  }

  .baseData {
    width: 642px;
    margin-right: 40px;
  }

  .photo {
    position:absolute;
    right:0px;
    border:1px solid #f3f3f3;
    width:122px;height:122px;
    img{width:120px;height:120px;}
  }

  .list {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid #f6f6f6;
    padding: 10px 0;
    color: #919191;
    font-size: 13px;
    float:left;
    width:320px;
  }
  .list div {
    width: 300px;
  }


  .list div span {
    display: inline-block;
    // width: 70px;
    // margin-right: 10px;
  }

  .resume_name {
    color: #333;
    font-size: 24px;
    margin: 0 0 20px;
  }

  .preview_title {
    position: absolute;
    color: #2b2b2b;
    font-size: 18px;
    /* margin-top: 10px; */
    top: 44px;
    left: 60px;
  }
  .undergo_con{
    margin-bottom: 20px;
  }

  .title {
    color: #676767;
    font-size: 18px;
    padding: 28px 0 20px;
    font-weight: bold;
  }

  .intention_list {
    padding-bottom: 25px;
  }

  .intention_list p {
    line-height: 24px;
    color: #787878;
    font-size: 14px;
  }

  .undergo_title {
    margin-bottom: 10px;
  }

  .undergo_title p {
    display: inline-block;
    color: #333;
    font-size: 14px;
  }

  .undergo_title>span {
    margin-right: 50px;
    color: #333;
    font-size: 14px;
  }

  .undergo_title p>span {
    margin-right: 8px;
    position: relative;
  }

  .border::after {
    content: "";
    width: 1px;
    height: 15px;
    position: absolute;
    right: -4px;
    top: 0;
    background: #E2E2E2;
  }

  .border:last-child::after {
    content: "";
    width: 0px;
    height: 0px;
  }

  .certificate_list {
    padding: 0 0 30px;
    .li-item{
      width:200px;
      display:inline-block;
      margin-bottom:10px;
    }
  }

  .certificate_list p span {
    padding-right: 8px;
    position: relative;
    color: #646464;
    font-size: 13px;
  }
  .language_list {
    padding: 0 0 30px;
    .li-item{
      width:200px;
      display:inline-block;
      margin-bottom:10px;
    }
  }

  .language_list p span {
    padding-right: 8px;
    position: relative;
    color: #646464;
    font-size: 13px;
  }

  .border2::after {
    content: "";
    width: 1px;
    height: 15px;
    position: absolute;
    right: 4px;
    top: 2px;
    background: #b6b6b6;
  }

  .border2:last-child::after {
    content: "";
    width: 0px;
    height: 0px;
  }

  .undergo_list {
    color: #9c9c9c;
    font-size: 13px;
  }

  .undergo_list p {
    padding: 0 0 5px;
    line-height: 22px;
  }
  .template_title{
    color: #545454;
    font-size: 18px;
  }
  .resume_con{
    background:#fff;
    padding: 20px;
  }
  .el-button{
    width:226px ;
    margin-bottom: 20px;
  }
  .imgBox{
    text-align: center;
    margin:24px 0 26px;
  }
  .imgBox div{
    width: 200px;
    height: 230px;
    box-shadow: 0 0 2px 2px #f8f8f8;
    margin: auto;
  }
    .imgBox p{
      margin: 10px 0 0;
      color: #525252;
      font-size: 14px;
    }
</style>
