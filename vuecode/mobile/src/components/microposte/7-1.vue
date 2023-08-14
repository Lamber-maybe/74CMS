<template>
<div class="hp">
  <div class="whb_newbg">
    <div class="micropost_img">
      <div class="whb_newbg_list">
        <ul>
          <li v-for="(item,index) in joblist" :key="index">{{item.jobname}}</li>
        </ul>
      </div>
      <div class="whb_address">
        <p>{{joblist[0].companyname}}</p>
        <p>{{joblist[0].address}}</p>
      </div>
      <div class="whb_sqr">
        <div class="whb_sqr_img">
          <img :src="qrcode"/>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_7_1',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
.substring{overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}

/*7_1*/
.whb_newbg{
  width: 100%;
  max-width: 375px;
  background: url(../../assets/images/posteimages/7_1_bg.jpg) no-repeat center;
  background-size: 375px 660px;
  overflow: hidden;
  height: 660px;
  position: relative;
  margin: 0 auto;
}
.whb_newbg_list{
  width: 144px;
  max-height: 221px;
  overflow: hidden;
  color: #542b21;
  margin-top: 300px;
  margin-left: 25px;

}
.whb_newbg_list ul li{
  font-size: 12px;
  width: 100%;
  height: 23px;
  border: 1px solid #542b21;
  padding-right: 13px;
  box-sizing: border-box;
  text-indent: 25px;
  line-height: 22px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  background: url(../../assets/images/posteimages/whb_circle.png) no-repeat 12px 8px;
  background-size: 5px 5px;
  margin-top: 15px;
}
.whb_newbg_list ul li:first-child{
  margin-top: 0;
}

.whb_address{
  position: absolute;
  left: 23px;
  bottom: 23px;
  color: #fdc2ab;
  font-size: 12px;
}
.whb_address p{
  width: 270px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  line-height: 20px;
  height: 20px;
}
.whb_sqr{
  width: 70px;
  height: 50px;
  position: absolute;
  right: 28px;
  bottom: 33px;
}
.whb_sqr_img{
  width: 36px;
  height: 36px;
  margin-right: 4px;
  margin-top: 21px;
  float: right;
}
.whb_sqr_img img{
  width: 36px;
  height: 36px;
}
</style>
