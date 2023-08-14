<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>系统升级</span>
      </div>
      <div class="upgrade-tip">
        <b>更新注意事项：</b>
        <p>1.本次更新不会删除您的用户数据，但仍然建议您在更新前做好备份。</p>
        <p>2.如当前版本太低，需要按顺序从低到高逐步升级。</p>
        <p>3.更新完成后，请及时<b>更新缓存</b>。</p>
        <p>4.授权用户可以联系官方售后客服协助升级，如果您在升级过程中遇到任何问题，请及时联系您的专属客服。</p>
        <img src="../../../assets/images/02.png" class="img" />
      </div>
    <el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      fit
    >
      <el-table-column label="" min-width="380">
        <template slot-scope="scope">
            <span class="row-title">{{ scope.row.title }}</span>
        </template>
      </el-table-column>
      <el-table-column label="" min-width="130">
        <template slot-scope="scope">
          最新版本：<span style="color:#ff6600;">v{{ latest_version }}</span>
        </template>
      </el-table-column>
      <el-table-column label="" prop="size_show" min-width="100"/>
      <el-table-column label="" min-width="200">
        <template slot-scope="scope">
          <span>发布时间：{{ scope.row.addtime | timeFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column fixed="right" label="" min-width="180">
        <template slot-scope="scope">
          <el-button
            v-if="scope.row.enable==1"
            size="small"
            type="primary"
            @click="openDialog(scope.row)"
          >
            升级
          </el-button>
          <el-button
            v-else
            size="small"
            type="info"
            disabled
          >
            升级
          </el-button>
          <el-button
            size="small"
            @click="openLogDialog(scope.row)"
          >
            更新日志
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    </el-card>
    <el-dialog :title="title" width="600px" :visible.sync="showDialog" v-if="showDialog" :destroy-on-close="true" :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false">
      <div class="upgrade-box">
        <div class="pre-notice" v-if="step==1">
          <div class="txt">
            1. 升级不可逆，为保证数据安全性，您在升级前请完成相应备份。未备份将无法还原，如因此导致的问题，我们不承担任何责任！<br />
            2. 升级过程中，请勿关闭升级页面，否则可能造成升级失败或其他未知问题！<br />
            3. 授权用户可以联系官方售后客服协助升级。
          </div>
          <el-button type="primary" :disabled="startDisable" @click="updateStart">
            我已备份，开始升级{{secondText}}
          </el-button>
          <br />
          <el-button type="text" @click="closeDialog" style="margin-top:10px;">
            取消升级
          </el-button>
        </div>
        <div v-if="step!=1">
          <img class="top-bg" :src="require('@/assets/images/04.png')"/>
          <div class="notice" v-if="step!=3">正在为您升级更新，不要关闭界面</div>
          <div class="notice done" v-else>升级成功</div>
          <el-progress :stroke-width="20" text-inside :percentage="percentage"></el-progress>
          <div class="progress-tip">{{progress_text}}</div>
          <el-button class="btn" type="primary" v-if="step==2">
            升级中
            <div class="loader">
              <div class="loader-inner ball-pulse">
                <div></div>
                <div></div>
                <div></div>
              </div>
            </div>
          </el-button>
          <el-button class="btn" type="primary" v-else @click="clearCache">
            清除缓存
          </el-button>
        </div>

      </div>
    </el-dialog>
    <el-dialog title="更新日志" width="600px" :visible.sync="showLogDialog" v-if="showLogDialog" :destroy-on-close="true" :close-on-click-modal="false" :close-on-press-escape="false">
      <div style="white-space: pre-wrap;line-height: 36px;max-height: 500px;overflow-y: auto;">{{logContent}}</div>
    </el-dialog>
  </div>
</template>

<script>
import { newVersionList,updateStart,updateDownload,speedProgress,unzip,update } from '@/api/upgrade'
import { clearCache } from '@/api/configuration'
import { parseTime } from '@/utils/index'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d}')
    }
  },
  data() {
    return {
      nextVersion:'',
      title:'升级提示',
      startDisable:true,
      timer: '',
      timer_progress:'',
      second:10,
      secondText:'（10s）',
      step:1,
      percentage:20,
      waiting:false,
      waitingText:'升级中',
      showDialog:false,
      list: null,
      listLoading: true,
      latest_version:'',
      filePath:'',
      size:0,
      timestamp:'',
      progress_text:'正在下载升级文件中，请稍等...',
      showLogDialog:false,
      logContent:'',
      stop:0,
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    openLogDialog(row){
      this.logContent = row.content2
      this.showLogDialog = true
    },
    clearCache() {
      clearCache({}).then(response => {
        if (response.code == 200) {
          this.$store.dispatch('config/getConfigInfo')
          window.location.reload()
        }
      })
    },
    getSecond() {
      if(this.second>1){
        this.second --;
        this.secondText = `（${this.second}s）`
      }else{
        this.secondText = ''
        this.startDisable = false
        clearInterval(this.timer);
      }
    },
    openDialog(row){
      if (localStorage.getItem('qscms_access') != 'all'){
        this.$alert('只有超级管理员才能执行系统升级操作！', '系统提示', {
          confirmButtonText: '确定',
          type: 'warning'
        });
        return false;
      }
      this.stop = 0
      this.size = row.size
      this.title = '升级提示'
      this.progress_text = '正在下载升级文件中，请稍等...'
      this.startDisable = true
      this.second = 10
      this.secondText = '（10s）'
      this.step = 1
      this.percentage = 0
      this.showDialog = true
      this.nextVersion = row.version_text
      this.timer = setInterval(this.getSecond, 1000);
    },
    closeDialog(){
      this.showDialog = false
      clearInterval(this.timer);
    },
    updateStart() {
      let domain = window.location.host
      const param = {
        domain:domain
      }
      updateStart(param).then(response => {
        this.step = 2
        this.filePath = response.data.path
        this.timestamp = response.data.timestamp
        this.updateDownload()
        this.speedProgress()
      })
    },
    updateDownload() {
      const param = {
        path:this.filePath,
        timestamp:this.timestamp
      }
      updateDownload(param).then(res => {
        if(res.data==500){
          this.stop = 1
          this.$alert(res.message, '系统提示', {
            confirmButtonText: '关闭',
            type: 'warning',
            callback: action => {
              this.closeDialog()
            }
          });
        }
      })
    },
    speedProgress() {
      console.log(this.stop)
      if(this.stop==1){
          return false;
      }
      let that = this
      const param = {
        path:this.filePath,
        size:this.size,
        timestamp:this.timestamp
      }
      speedProgress(param).then(response => {
        this.percentage = parseInt(response.data)
        if(this.percentage<80){
          setTimeout(function(){
            that.speedProgress()
          },500)
        }else{
          this.progress_text = '正在解压...'
          this.unzip()
          // this.step = 3
        }
      })
    },
    unzip() {
      const param = {
        path:this.filePath,
        timestamp:this.timestamp
      }
      unzip(param).then(response => {
        this.percentage = 90
        this.progress_text = '解压完成，正在执行升级...'
        this.update()
        // this.step = 3
      })
    },
    update() {
      const param = {
        path:this.filePath,
        timestamp:this.timestamp
      }
      update(param).then(response => {
        this.percentage = 100
        this.progress_text = '您已成功升级到V'+this.nextVersion
        this.step = 3
      })
    },
    fetchData() {
      this.listLoading = true
      const param = {
      }
      newVersionList(param).then(response => {
        this.list = response.data.items
        this.listLoading = false
        this.latest_version = response.data.latest_version
      })
    },
  }
}
</script>
<style scoped>
.row-title{
  background-image: url("../../../assets/images/03.png");
  background-repeat: no-repeat;
  background-size: 48px;
  display: block;
  padding-left:58px;
  height: 48px;
  line-height: 48px;
}
  .upgrade-tip{
    background-color: #fbfbfb;
    padding:20px 20px 10px;
    color:#666;
    font-size:14px;
    position:relative;
  }
  .upgrade-tip .img{
    position: absolute;
    right:100px;
    top:10px;
  }
  ::v-deep .el-table .cell{
    line-height:44px;
  }
  .upgrade-box{
    width:480px;
    text-align: center;
    margin:0 auto;
  }
  .pre-notice .txt{
    text-align:left;
    line-height: 30px;
    margin-bottom: 40px;
  }
  .notice{
    font-size:16px;
    color:red;
    margin-top: 20px;
    margin-bottom: 40px;
  }
  .notice.done{
    color:#666;
    font-weight: bold;
  }
  .progress-tip{
    margin-top: 30px;
    color:#999;
  }
  .btn{
    margin-top: 30px;
  }
  .loader {
    box-sizing: border-box;
    display: flex;
    flex: 0 1 auto;
    flex-direction: column;
    flex-grow: 1;
    flex-shrink: 0;
    flex-basis: 25%;
    max-width: 35%;
    /*height: 200px;*/
    align-items: center;
    justify-content: center;
    display: inline-block;
  }
  @-webkit-keyframes scale {
    0% {
      -webkit-transform: scale(1);
      transform: scale(1);
      opacity: 1; }

    45% {
      -webkit-transform: scale(0.1);
      transform: scale(0.1);
      opacity: 0.7; }

    80% {
      -webkit-transform: scale(1);
      transform: scale(1);
      opacity: 1; } }
  @keyframes scale {
    0% {
      -webkit-transform: scale(1);
      transform: scale(1);
      opacity: 1; }

    45% {
      -webkit-transform: scale(0.1);
      transform: scale(0.1);
      opacity: 0.7; }

    80% {
      -webkit-transform: scale(1);
      transform: scale(1);
      opacity: 1; } }

  .ball-pulse > div:nth-child(0) {
    -webkit-animation: scale 0.75s 0s infinite cubic-bezier(.2, .68, .18, 1.08);
    animation: scale 0.75s 0s infinite cubic-bezier(.2, .68, .18, 1.08); }
  .ball-pulse > div:nth-child(1) {
    -webkit-animation: scale 0.75s 0.12s infinite cubic-bezier(.2, .68, .18, 1.08);
    animation: scale 0.75s 0.12s infinite cubic-bezier(.2, .68, .18, 1.08); }
  .ball-pulse > div:nth-child(2) {
    -webkit-animation: scale 0.75s 0.24s infinite cubic-bezier(.2, .68, .18, 1.08);
    animation: scale 0.75s 0.24s infinite cubic-bezier(.2, .68, .18, 1.08); }
  .ball-pulse > div:nth-child(3) {
    -webkit-animation: scale 0.75s 0.36s infinite cubic-bezier(.2, .68, .18, 1.08);
    animation: scale 0.75s 0.36s infinite cubic-bezier(.2, .68, .18, 1.08); }
  .ball-pulse > div {
    background-color: #fff;
    width: 8px;
    height: 8px;
    border-radius: 100%;
    margin: 2px;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
    display: inline-block; }
</style>
