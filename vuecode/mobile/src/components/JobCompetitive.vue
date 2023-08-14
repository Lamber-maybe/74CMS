<template>
  <div id="app">
    <div class="box_1">
      <div class="self_head">
        <div class="self_back" @click="$emit('closeCompetitive')"></div>
        竞争力分析
      </div>
      <div class="info_box">
        <div class="tx1">职位统计</div>
        <div class="tx2">全面了解竞争对手，帮您知己知彼</div>
        <div class="tx3">
          竞争者：
          <span class="num">{{ info.competitor_total }}</span>
        </div>
        <div class="tx3 right">
          被查看：
          <span class="num">{{ info.looked_total }}</span>
        </div>
      </div>
    </div>
    <div class="cop_put"><div class="self_content">我的职位匹配度</div></div>
    <div class="box_2">
      <div class="self_content " :class="'level_' + info.match_level">
        <div class="sp_block s1"></div>
        <div class="sp_block s2"></div>
        <div class="sp_block s3"></div>
        <div class="percent"></div>
        <div class="cir_block"></div>
        <div class="percent_text t1">一般</div>
        <div class="percent_text t2">良好</div>
        <div class="percent_text t3">优秀</div>
      </div>
    </div>
    <div class="box_3">
      <div class="cop_put"><div class="self_content">匹配详情</div></div>
      <div class="list">
        学历：
        <span>{{ info.match_result.education.cn }}</span>
        <div
          class="ico "
          :class="info.match_result.education.is_match == 0 ? 'fail' : 'done'"
        ></div>
      </div>
      <div class="list">
        经验：
        <span>{{ info.match_result.experience.cn }}</span>
        <div
          class="ico "
          :class="info.match_result.experience.is_match == 0 ? 'fail' : 'done'"
        ></div>
      </div>
      <div class="list">
        类别：
        <span>{{ info.match_result.category.cn }}</span>
        <div
          class="ico "
          :class="info.match_result.category.is_match == 0 ? 'fail' : 'done'"
        ></div>
      </div>
      <div class="list">
        地区：
        <span>{{ info.match_result.district.cn }}</span>
        <div
          class="ico "
          :class="info.match_result.district.is_match == 0 ? 'fail' : 'done'"
        ></div>
      </div>
      <div class="list">
        薪资：
        <span>{{ info.match_result.wage.cn }}</span>
        <div
          class="ico "
          :class="info.match_result.wage.is_match == 0 ? 'fail' : 'done'"
        ></div>
      </div>
      <div class="list">
        简历完整度：
        <span>{{ info.match_result.complete_percent.cn }}</span>
        <div
          class="ico "
          :class="
            info.match_result.complete_percent.is_match == 0 ? 'fail' : 'done'
          "
        ></div>
      </div>
    </div>
    <div class="sp_bar"></div>
    <div class="box_4">
      <div class="cop_put">
        <div class="self_content">全站同行学历分布统计</div>
      </div>
      <div class="sheet_box">
        <div class="y">100%</div>
        <div class="x">
          <div
            class="list"
            v-for="(item, index) in info.statistics_education"
            :key="index"
            :style="'height:' + item.percent + '%;'"
          >
            <div class="text">{{ item.label }}</div>
            <div class="num">{{ item.percent }}%</div>
            <div class="position" v-if="item.here == 1">我在这里</div>
          </div>
        </div>
        <div class="clear"></div>
      </div>
    </div>
    <div class="box_4">
      <div class="cop_put">
        <div class="self_content">全站同行工作经验分布</div>
      </div>
      <div class="sheet_box">
        <div class="y">100%</div>
        <div class="x">
          <div
            class="list"
            v-for="(item, index) in info.statistics_experience"
            :key="index"
            :style="'height:' + item.percent + '%;'"
          >
            <div class="text">{{ item.label }}</div>
            <div class="num">{{ item.percent }}%</div>
            <div class="position" v-if="item.here == 1">我在这里</div>
          </div>
        </div>
        <div class="clear"></div>
      </div>
    </div>
    <div class="box_4">
      <div class="cop_put">
        <div class="self_content">全站同行期望薪资分布</div>
      </div>
      <div class="sheet_box">
        <div class="y">100%</div>
        <div class="x">
          <div
            class="list"
            v-for="(item, index) in info.statistics_wage"
            :key="index"
            :style="'height:' + item.percent + '%;'"
          >
            <div class="text">{{ item.label }}</div>
            <div class="num">{{ item.percent }}%</div>
            <div class="position" v-if="item.here == 1">我在这里</div>
          </div>
        </div>
        <div class="clear"></div>
      </div>
    </div>
    <div class="box_5">
      *以上所展示的数据仅对{{
        $store.state.config.sitename
      }}的用户样本数据负责，反应的竞争力数据仅供参考。简历完整度越高，求职竞争力越大。
    </div>
  </div>
</template>

<script>
export default {
  name: 'JobCompetitive',
  props: ['info']
}
</script>

<style lang="scss" scoped>
.box_1 {
  width: 100%;
  background: url('../assets/images/competitive_top_bg.png') center 0 no-repeat;
  background-size: 100% 151px;
  margin-bottom: 36px;
}
.box_1 .self_head {
  position: relative;
  text-align: center;
  font-size: 18px;
  font-weight: bold;
  color: #ffffff;
  line-height: 1;
  padding: 18px 0;
}
.box_1 .self_head .self_back {
  position: absolute;
  left: 0;
  top: 0;
  width: 45px;
  height: 100%;
  background: url('../assets/images/competitive_back.png') center no-repeat;
  background-size: 20px;
}
.box_1 .info_box {
  width: 340px;
  height: 100px;
  margin: 0 auto;
  border-radius: 7px;
  box-shadow: 0 1px 5px #c2c2c2;
  line-height: 1;
  position: relative;
  background: #ffffff url('../assets/images/competitive_top_info_bg.png') 0
    bottom no-repeat;
  background-size: 340px 36px;
}
.box_1 .info_box .tx1 {
  padding: 15px 0 0 15px;
  font-size: 15px;
  font-weight: bold;
  color: #333333;
}
.box_1 .info_box .tx2 {
  font-size: 12px;
  color: #999999;
  position: absolute;
  right: 15px;
  top: 16px;
}
.box_1 .info_box .tx3 {
  font-size: 14px;
  color: #666666;
  position: absolute;
  left: 50px;
  top: 55px;
}
.box_1 .info_box .tx3 .num {
  color: #fa7445;
}
.box_1 .info_box .tx3.right {
  left: 220px;
}

.cop_put {
  width: 100%;
  text-align: center;
}
.cop_put .self_content {
  display: inline-block;
  font-size: 15px;
  font-weight: bold;
  color: #333333;
  position: relative;
  padding: 0 55px;
  line-height: 1;
}
.cop_put .self_content::before {
  content: ' ';
  position: absolute;
  left: 0;
  top: 50%;
  transform: translate(0, -50%);
  width: 45px;
  height: 1px;
  background: linear-gradient(to right, #fdfdfd, #e2e2e2);
}
.cop_put .self_content::after {
  content: ' ';
  position: absolute;
  right: 0;
  top: 50%;
  transform: translate(0, -50%);
  width: 45px;
  height: 1px;
  background: linear-gradient(to right, #e2e2e2, #fdfdfd);
}

.box_2 {
  padding: 25px 0 50px;
}
.box_2 .self_content {
  position: relative;
  width: 325px;
  height: 10px;
  margin: 0 auto;
  background-color: #f3f3f3;
  border-radius: 10px;
}
.box_2 .self_content .percent {
  position: absolute;
  left: 0;
  top: 0;
  border-radius: 5px;
  height: 100%;
}
.box_2 .self_content .sp_block {
  position: absolute;
  width: 2px;
  height: 100%;
  background-color: #ffffff;
  z-index: 2;
}
.box_2 .self_content .sp_block.s1 {
  left: 62px;
  top: 0;
}
.box_2 .self_content .sp_block.s2 {
  left: 167px;
  top: 0;
}
.box_2 .self_content .sp_block.s3 {
  left: 262px;
  top: 0;
}
.box_2 .self_content .cir_block {
  position: absolute;
  width: 15px;
  height: 15px;
  border-radius: 100%;
  background-color: #ffffff;
  box-shadow: 0 0 0.1rem #c2c2c2;
  z-index: 3;
  top: -3px;
}
.box_2 .self_content.level_1 .percent {
  width: 62px;
  background: linear-gradient(to right, #6de078, #9ae068);
}
.box_2 .self_content.level_1 .cir_block {
  left: 55px;
}
.box_2 .self_content.level_1 .t1 {
  color: #fa7445;
}
.box_2 .self_content.level_2 .percent {
  width: 167px;
  background: linear-gradient(to right, #6de078, #cfe055, #ead447);
}
.box_2 .self_content.level_2 .cir_block {
  left: 160px;
}
.box_2 .self_content.level_2 .t2 {
  color: #fa7445;
}
.box_2 .self_content.level_3 .percent {
  width: 100%;
  background: linear-gradient(to right, #6de078, #f6d144, #ff7a31);
}
.box_2 .self_content.level_3 .cir_block {
  left: 260px;
}
.box_2 .self_content.level_3 .t3 {
  color: #fa7445;
}
.box_2 .self_content .percent_text {
  position: absolute;
  bottom: -25px;
  font-size: 13px;
  color: #666666;
}
.box_2 .self_content .percent_text.t1 {
  left: 50px;
}
.box_2 .self_content .percent_text.t2 {
  left: 157px;
}
.box_2 .self_content .percent_text.t3 {
  left: 251px;
}

.box_3 {
  width: 340px;
  margin: 0 auto 35px;
  border-radius: 7px;
  padding: 27px 19px 17px;
  box-shadow: 0 0 15px #f2f1f1;
}
.box_3 .cop_put {
  margin-bottom: 13px;
}
.box_3 .list {
  position: relative;
  width: 100%;
  line-height: 1;
  padding: 10px 30px 10px 0;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  font-size: 14px;
  color: #999999;
}
.box_3 .list span {
  color: #666666;
}
.box_3 .list .ico {
  position: absolute;
  right: 0;
  top: 0;
  width: 17px;
  height: 100%;
}
.box_3 .list .ico.done {
  background: url('../assets/images/cop_list_done.png') 0 center no-repeat;
  background-size: 17px;
}
.box_3 .list .ico.fail {
  background: url('../assets/images/cop_list_fail.png') 0 center no-repeat;
  background-size: 17px;
}

.sp_bar {
  width: 100%;
  height: 5px;
  background-color: #f3f3f3;
}

.box_4 {
  padding-top: 32px;
}
.box_4 .sheet_box {
  width: 100%;
  padding-top: 29px;
  margin-bottom: 27px;
}
.box_4 .sheet_box .y {
  float: left;
  width: 50px;
  height: 140px;
  border-right: 2px solid #e2e2e2;
  color: #666666;
  font-size: 12px;
  writing-mode: tb;
  padding: 3px 4px;
}
.box_4 .sheet_box .x {
  float: left;
  width: 290px;
  height: 140px;
  border-bottom: 2px solid #e2e2e2;
  display: flex;
  align-items: flex-end;
}
.box_4 .sheet_box .x .list {
  flex: 1;
  background: #1787fb;
  margin: 0 18px;
  position: relative;
  height: 30%;
}
.box_4 .sheet_box .x .list .num {
  position: absolute;
  top: -20px;
  left: 0;
  width: 100%;
  color: #1787fb;
  font-size: 12px;
  text-align: center;
}
.box_4 .sheet_box .x .list .position {
  position: absolute;
  top: -50px;
  left: 50%;
  width: 56px;
  color: #ffffff;
  font-size: 10px;
  background-color: #ff6600;
  line-height: 1;
  padding: 5px 0;
  transform: translate(-50%, 0);
  text-align: center;
  border-radius: 21px;
}
.box_4 .sheet_box .x .list .position::after {
  content: ' ';
  display: block;
  position: absolute;
  width: 0;
  height: 0;
  border: 5px solid transparent;
  border-top-color: #ff6600;
  left: 50%;
  transform: translate(-50%, 0);
  bottom: -10px;
}
.box_4 .sheet_box .x .list .text {
  font-size: 12px;
  color: #666666;
  text-align: center;
  line-height: 1;
  width: 86px;
  position: absolute;
  bottom: -20px;
  left: 50%;
  transform: translate(-50%, 0);
}

.box_5 {
  padding: 10px 16px 0 16px;
  line-height: 1.8;
  font-size: 12px;
  color: #999999;
  word-break: break-all;
}
</style>
