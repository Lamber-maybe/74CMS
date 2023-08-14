<template>
  <div class="app">
    <div class="title">
      职位推广工具
    </div>
    <div class="job_details">
      <jobdetail ref="jobdetail"/>
    </div>
    <div class="app-container">
      <div class="job">
        <div class="jobname">{{jobname}}</div>
        <div class="companName">发布企业：{{company_name}}</div>
      </div>
      <div class="button">
        <div class="button1" @click="funCopy(row)">一键复制</div>
        <div class="button2" @click="funPoster">生成海报</div>
      </div>
      <div class="button_p">
        <div class="information">
          <p>快速复制职位信息</p>
          <p>发送到QQ、微信等社交媒体</p>
        </div>
        <div class="information2">
          <p>生成招聘海报</p>
          <p>分享到微信朋友圈、微信群等</p>
        </div>
      </div>
      <div class="hr"></div>
      <div class="jobLink">
        <div class="link">
          <div class="sx"></div>
          <div class="zclj">职位链接</div>
        </div>
        <div class="link1">
          <div class="link2">职位链接：</div>
          <div class="convertLinks" v-if="urlType==1" @click="urlType=2">转换短链接</div>
          <div class="convertLinks" v-else @click="urlType=1">转换长链接</div>
        </div>
        <div class="zclink clearfix">
          <div class="linkurl" v-if="urlType==1">{{job_link}}</div>
          <div class="linkurl" v-else>{{short_link}}</div>
          <div class="fzurl" @click="copy('joburl')">复制链接</div>
        </div>
        <div class="appletLink">小程序链接：</div>
        <div class="appletLink2 clearfix">
          <div class="appletLinkurl">{{applet_link}}</div>
          <div class="fzappletLink" @click="copy('appleturl')">复制链接</div>
        </div>
      </div>
      <div class="qrcodemodule">
        <div class="qr">
          <div class="sx"></div>
          <div class="ewm">二维码</div>
        </div>
        <div class="qrcode">
          <div class="qrcode1" :class="isQrcode==1 ? 'isQrcode1' : '' " @click="setQrcode(1)">触屏端二维码</div>
          <div class="qrcode2" :class="isQrcode==2 ? 'isQrcode2' : '' " @click="setQrcode(2)">微信场景码</div>
          <div class="qrcode3" :class="isQrcode==3 ? 'isQrcode3' : '' " @click="setQrcode(3)">小程序二维码</div>
        </div>
        <div class="qrcideImg">
          <img :src="mobile_qrcode" alt="" v-if="isQrcode==1">
          <img :src="wechat_qrcode" alt="" v-else-if="isQrcode==2">
          <img :src="applet_qrcode" alt="" v-else>
        </div>
      </div>
      <Poster
        v-if="showPoster"
        :poster-id="posterId"
        :poster-type="posterType"
        @closeDialog="showPoster = false"
      />
    </div>
  </div>
</template>

<script>
import { jobEdit, jobLink } from '@/api/job'
import Poster from '@/components/Poster'
import jobdetail from '../list/jobdetail'

export default {
  components: {
    Poster,
    jobdetail
  },
  data() {
    return {
      isQrcode: 1,
      showPoster: false,
      posterId: '',
      posterType: '',
      applet_qrcode: '',
      job_link: '',
      mobile_qrcode: '',
      short_link: '',
      wechat_qrcode: '',
      urlType: 1,
      applet_link: '',
      qrcude_type: 1,
      jobname: '',
      company_name: '',
      row: []
    }
  },
  created() {
    this.getJobLink()
  },
  methods: {
    setQrcode(parm){
      this.isQrcode = parm
    },
    //  复制连接
    copy(type) {
      const that = this
      var copyMessage = this.short_link
      if (type == 'joburl') {
        if (that.urlType === 1) {
          copyMessage = this.job_link
        } else {
          copyMessage = this.short_link
        }
      } else {
        copyMessage = this.applet_link
      }
      this.$copyText(copyMessage).then(function (e) {
        that.$message.success('链接已复制到剪切板！')
      }, function (e) {
        that.$message.error('抱歉，复制失败！')
      })
    },
    // 获取职位推广链接
    getJobLink() {
      const param = {
        id: this.$route.query.id
      }
      jobEdit(param,'get').then(res => {
        if (res.code === 200) {
          this.row = res.data.info
          this.$nextTick(() => {
            this.$refs.jobdetail.getJobLink(res.data.info)
          })
          this.company_name = res.data.info.company_name
          this.jobname = res.data.info.jobname
        }
      })
      jobLink(param).then(response => {
        if (response.code == 200) {
          this.applet_qrcode = response.data.applet_qrcode
          this.job_link = response.data.job_link
          this.mobile_qrcode = response.data.mobile_qrcode
          this.short_link = response.data.short_link
          this.wechat_qrcode = response.data.wechat_qrcode
          this.applet_link = response.data.applet_link
        }
      })
    },
    funPoster() {
      this.showPoster = true
      this.posterId = this.$route.query.id
      this.posterType = 'job'
    },
    // 一键复制
    funCopy(row) {
      const that = this
      const copyMessage = `${row.company_name}
招聘：${row.jobname}
要求：工作经验${row.experience_text}、学历要求${row.education_text}
工资：${row.wage_text}
查看联系方式：${row.job_link}
-招聘求职就上${row.sitename}-`
      this.$copyText(copyMessage).then(function (e) {
        that.$message.success('内容已复制到剪切板！')
      }, function (e) {
        that.$message.error('抱歉，复制失败！')
      })
    }
  }
}
</script>
<style lang="scss" scoped>
  .title{
    padding: 33px 0px 0px 27px;
    font-weight: bold;
  }
  .app{
      display: flex;
  }
  .job_details{
    .content{
    }
  }
  .app-container{
    margin: 108px 0px 0px 151px;
    padding: 20px;
    width: 387px;
    .hr{
      width: 352px;
      height: 1px;
      background: #E2E2E2;
    }
    .button_p{
      display: flex;
      padding: 10px 0 21px 0;
      .information2 p{
        font-size: 12px;
        font-weight: 400;
        color: #999999;
        margin-left: 39px;
        line-height: 5px;
      }
      .information p{
        font-size: 12px;
        font-weight: 400;
        color: #999999;
        line-height: 5px;
      }
    }
    .button{
      display: flex;
      .button2{
        width: 160px;
        height: 40px;
        background: #FF712B;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 400;
        color: #FFFFFF;
        line-height: 39px;
        margin-left: 30px;
        text-align: center;
        cursor:pointer
      }
      .button1{
        width: 160px;
        height: 40px;
        background: #409EFF;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 400;
        color: #FFFFFF;
        line-height: 39px;
        text-align: center;
        cursor:pointer
      }
    }
    .job{
      .companName{
        font-size: 14px;
        font-weight: 400;
        color: #666666;
        line-height: 36px;
        padding-bottom: 20px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }
      .jobname{
        font-size: 18px;
        font-weight: bold;
        color: #333333;
        line-height: 36px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }
    }
    .jobLink{
      .link1{
        display: flex;
        padding-bottom: 12px;
        .convertLinks{
          font-size: 12px;
          font-weight: 400;
          color: #409EFF;
          margin-left: 225px;
          cursor:pointer
        }
        .link2{
          font-size: 12px;
          font-weight: 400;
          color: #666666;
        }
      }
      .appletLink{
        padding: 33px 0 30px 0;
      }
      .appletLink2{
        margin-left: 13px;
        display: flex;
        .fzappletLink{
          width: 100px;
          height: 40px;
          background: #409EFF;
          border-radius: 4px;
          font-size: 14px;
          font-weight: 400;
          color: #FFFFFF;
          text-align: center;
          line-height: 39px;
          margin-left: 10px;
          cursor:pointer
        }
        .appletLinkurl{
          font-size: 14px;
          font-weight: 400;
          color: #333333;
          padding-top: 11px;
          width: 236px;
          overflow: hidden;
          text-overflow: ellipsis;
        }
      }
      .zclink{
        width: 346px;
        .fzurl{
          width: 100px;
          height: 40px;
          background: #409EFF;
          border-radius: 4px;
          text-align: center;
          line-height: 39px;
          margin-left: 10px;
          font-size: 14px;
          font-weight: 400;
          color: #FFFFFF;
          cursor:pointer;
          float: right;
        }
        .linkurl{
          padding-top: 11px;
          font-size: 14px;
          font-weight: 400;
          color: #333333;
          width: 236px;
          overflow: hidden;
          text-overflow: ellipsis;
          float: left;
          padding-left: 13px;
        }
      }
      .link{
        font-size: 14px;
        font-weight: 400;
        color: #333333;
        padding: 20px 0 26px 0;
        display: flex;
        .sx{
          width: 3px;
          height: 16px;
          background: #409EFF;
        }
        .zclj{
          margin-left: 6px;
        }
      }
    }
    .qrcideImg{
      height: 179px;
      width: 179px;
      margin: 32px 0 0 82px;
      img{
        width: 100%;
        height: 100%;
      }
    }
    .qrcodemodule{
      .qr{
        font-size: 14px;
        font-weight: 400;
        color: #333333;
        padding: 20px 0 20px 0;
        display: flex;
        .sx{
          width: 3px;
          height: 16px;
          background: #409EFF;
        }
        .ewm{
          margin-left: 6px;
        }
      }
      .qrcode{
        display: flex;
        .qrcode1{
          width: 116px;
          height: 35px;
          background: #FFFFFF;
          border: 1px solid #E2E2E2;
          border-radius: 4px 0 0 4px;
          text-align: center;
          font-size: 12px;
          font-weight: 400;
          color: #666666;
          line-height: 32px;
          cursor:pointer
        }
        .qrcode2{
          width: 116px;
          height: 35px;
          background: #FFFFFF;
          border: 1px solid #E2E2E2;
          text-align: center;
          font-size: 12px;
          font-weight: 400;
          color: #666666;
          line-height: 32px;
          cursor:pointer
        }
        .qrcode3{
          width: 116px;
          height: 35px;
          background: #FFFFFF;
          border: 1px solid #E2E2E2;
          border-radius: 0 4px 4px 0;
          text-align: center;
          font-size: 12px;
          font-weight: 400;
          color: #666666;
          line-height: 32px;
          cursor:pointer
        }
        .isQrcode1{
          border: 1px solid #409EFF;
          color: #409EFF;
        }
        .isQrcode2{
          border: 1px solid #409EFF;
          color: #409EFF;
        }
        .isQrcode3{
          border: 1px solid #409EFF;
          color: #409EFF;
        }
      }
      }
  }
</style>
