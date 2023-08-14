<template>
  <div class="box">
    <el-table
      :data="tableData"
      style="width: 100%"
      v-loading="loading"
    >
      <el-table-column
        prop="seat_number"
        label="坐席编号"
      />
      <el-table-column
        prop="start_date"
        label="通话日期"
      >
        <template slot-scope="scope">
          <span>{{ scope.row.start_date | timeFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column
        prop="address"
        label="开始时间"
      >
        <template slot-scope="scope">
          <span>{{ scope.row.start_time | timeFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column
        prop="address"
        label="结束时间"
      >
        <template slot-scope="scope">
          <span>{{ scope.row.end_time | timeFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column
        prop="call_duration_time"
        label="通话时长"
      />
      <el-table-column
        label="呼叫录音"
      >
        <template slot-scope="scope">
          <div class="sound">
            <div class="sound_img">
              <img v-if="scope.row.is_play == 1" src="../../assets/images/outbound/play.png" alt="" @click="handlePlay(scope.row.record_mp3_url,scope.row.id)">
              <img v-if="scope.row.is_play == 0" src="../../assets/images/outbound/suspend.png" alt="" @click="suspend(scope.row.id)">
            </div>
            <div class="sound_text">{{ scope.row.call_duration_time }}</div>
            <audio
              ref="audio"
              :src="play_src"
              autoplay="autoplay"
              controls="controls"
              @ended="overAudio"
            >Your browser does not support the audio element.</audio>
          </div>
        </template>
      </el-table-column>
    </el-table>
    <div class="bortton-page">
      <el-col :span="24" style="text-align: right">
        <el-pagination
          background
          destroy-on-close
          :current-page="currentPage"
          :page-sizes="[10, 15, 20, 30, 40]"
          :page-size="pagesize"
          layout="total, sizes, prev, pager, next, jumper"
          :total="total"
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
        />
      </el-col>
    </div>
  </div>
</template>

<script>
import { parseTime } from '@/utils'
import { recordDetails } from '@/api/outbound'

export default {
  name: 'OutboundDetails',
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  props: ['groupId', 'staffName'],
  data() {
    return {
      tableData: [],
      currentPage: 1,
      pagesize: 10,
      total: 0,
      play_src: '',
      loading: false
    }
  },
  created() {
    this.getData()
  },
  methods: {
    getData(){
      this.loading = true
      recordDetails({
        'group_id': this.groupId,
        'page': this.currentPage,
        'per_page': this.pagesize
      }).then(res => {
        this.currentPage = res.data.current_page
        this.total = res.data.total
        this.tableData = res.data.data
        for (var i = 0; i <= this.tableData.length - 1; i++){
          this.tableData[i].is_play = 1
        }
        this.loading = false
      }).catch(() => {

      })
    },
    handleSizeChange(size){
      this.pagesize = size
      this.getData()
    },
    handleCurrentChange(page){
      this.currentPage = page
      this.getData()
    },
    suspend(id){
      this.play_src = ''
      for (var i = 0; i <= this.tableData.length - 1; i++){
        if (this.tableData[i].id == id){
          this.tableData[i].is_play = 1
        }
      }
      this.stop()
    },
    handlePlay(url, id){
      if (url == ''){
        this.$message.error('未获取到播放地址')
      }
      this.play_src = url
      for (var i = 0; i <= this.tableData.length - 1; i++){
        if (this.tableData[i].id == id){
          this.tableData[i].is_play = 0
        } else {
          this.tableData[i].is_play = 1
        }
      }
      this.play()
    },
    play() {
      this.$refs.audio.play()
    },
    stop() {
      this.$refs.audio.pause()
      this.$refs.audio.currentTime = 0
    },
    overAudio() {
      this.play_src = ''
      for (var i = 0; i <= this.tableData.length - 1; i++){
        this.tableData[i].is_play = 1
      }
    }
  }
}
</script>

<style scoped lang="scss">
  .sound{
    width: 84px;
    height:30px;
    background-color: #edf9e8;
    border-radius: 50px;
    .sound_img{
      cursor: pointer;
      float: left;
      margin-top: 5px;
      display: inline-block;
      margin-left: 10px;
    }
    .sound_text{
      float: right;
      margin-right: 10px;
      margin-top: 5px;
      display: inline-block;
      font-size: 14px;
      color:#4cbb15;
    }
  }
  .bortton-page{
    margin-top: 20px;
  }
  .box{
    padding: 30px;
  }
  .audios{
    width: 200px;
    height: 30px;
    margin-top: 5px;
  }
</style>
