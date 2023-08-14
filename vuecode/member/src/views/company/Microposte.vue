<template>
    <el-card>
		<company-title>微海报</company-title>
		<el-alert title="选择模板。点击预览可在手机端查看并保存海报"  type="warning" :closable='false'  show-icon> </el-alert>
        <div class="whb_template_bottom">
            <ul>
                <li v-for="(item,index) in tpllist" :key="index">
                    <div class="whb_template_img">
                        <a href="javascript:;"><img :src="require('../../assets/images/microposte/'+item.thumb+'_bg.jpg')"></a>
                    </div>
                    <div class="whb_template_imgtxt">
                        <a href="javascript:;">{{item.name}}</a>
                        <a href="javascript:;" class="preview">
                            预览
                        <div class="whb_template_position">
                            <div class="whb_template_sqr">
                                <img :src="makeUrl(item.thumb)"/>
                            </div>
                            <div class="whb_template_sqrtext">
                                手机扫码保存海报
                            </div>
                        </div>
                        </a>
                    </div>
                </li>
                <div class="clearfix"></div>
            </ul>
        </div>
    </el-card>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default{
	components:{
	},
  data() {
    return {
      result: [],
      tpllist: [],
      qrcodeUrl:'',
    }
  },
  created() {
    this.qrcodeUrl = window.global.RequestBaseUrl + api.get_qrcode
    if (this.$route.query.jobid !== undefined) {
      let jobidArr = this.$route.query.jobid.split(',')
      jobidArr.forEach(element => {
          this.result.push(element)
      });
      
      this.initCB()
      return false
    }
  },
  methods: {
    getTplByJobnum () {
      this.tpllist = []
      let jobnum = this.result.length
      http.get(api.microposte_get_tpl_by_jobnum, {jobnum}).then(res => {
        this.tpllist = res.data.items
      }).catch(() => {})
    },
    initCB () {
      if (this.result.length === 0) {
        this.$message.error('请选择职位')
        return false
      }
      this.getTplByJobnum()
    },
    makeUrl(tpl){
        let mobile_url = this.$store.state.config.mobile_domain + 'member/scan_microposte?jobid='+this.$route.query.jobid+'&tpl='+tpl;
        mobile_url = encodeURIComponent(mobile_url)
        return this.qrcodeUrl+'?type=normal&url='+mobile_url
    }
  },
}
</script>

<style lang="scss" scoped>
.clearfix{
    clear:both;
}
.whb_template_bottom{
    margin-top:20px;
	// width: 938px;
	// margin: 0 auto;
	// height: auto;
	// background: red;
}
.whb_template_bottom ul li{
	width: 300px;
	height: 435px;
	float: left;
	position: relative;
	margin-right: 18px;
    margin-bottom:20px;
	box-shadow: 0 0 6px 0 rgba(0,0,0,.19);
}
.whb_template_bottom ul li:hover{
	box-shadow: 0 0 6px 0 rgba(0,0,0,.19);
}

.whb_template_img{
	width: 300px;
	height: 370px;
	margin: 0 auto;
	overflow:hidden;
}
.whb_template_img img{
	width: 300px;

}
.whb_template_imgtxt{
	width: 300px;
	height: 53px;
	line-height: 64px;
	font-size: 14px;
}
.whb_template_imgtxt a:first-child{
	color: #666666;
	display: block;
	float: left;
	margin-left: 16px;
}
.whb_template_imgtxt a:first-child+a{
	display: block;
	float: right;
	color: #666666;
	width: 70px;
	background: url('../../assets/images/whb3.png') no-repeat left;
	padding-left: 21px;
}
.whb_template_imgtxt a:first-child+a:hover .whb_template_position{
	display: block;
}
.whb_template_imgtxt a:hover{
	text-decoration: underline;
}
.whb_template_position{
	width:300px;
	height: 380px;
	position: absolute;
	left: 0;
	top: 0;
	background: url('../../assets/images/whb4.png') no-repeat center;
	background-size: 300px 380px;
	display: none;
}
.whb_template_sqr{
	width: 130px;
	height: 130px;
	background: #fff;
	margin: 100px auto 5px;
}
.whb_template_sqr img{
	width: 130px;
	height: 130px;
}
.whb_template_sqrtext{
	font-size: 16px;
	color: #fff;
	text-align: center;
}
.whb_template_bottom ul li:hover .whb_template_position{
	display:block;
}
</style>

