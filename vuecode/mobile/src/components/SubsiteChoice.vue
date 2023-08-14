<template>
  <div id="app">
    <Head>选择分站</Head>
    <div class="isTop">
      <div class="d3">
        <van-field class="search-input" v-model="searchVal" placeholder="请输入搜索的分站名称" clearable
                   @input="searchTxtInput"></van-field>
      </div>
      <div class="d1">当前选择地区 <span class="s_name">{{ config.subsite_info.sitename }}</span></div>
    </div>
    <div id="" class="addr-list">
      <div class="all-subsite" @click="choose_subsite(0,config.mobile_domain)">总站</div>
      <div class="mui-content">
        <div id="showLetter" class="showLetter"><span></span></div>
        <div class="city">
          <div ref="cityWrapper" style="background: #ffffff" class="city-wrapper city-wrapper-hook">
            <div ref="cityScroller" class="scroller-hook">
              <div v-if="searchVal!=''">
                <div class="cities cities-hook" v-for="(item,index) in newArr">
                  <div :id="`city${item.initial}`" class="title" v-if="item.cityInfo.length>0">
                    {{ item.initial }}
                  </div>
                  <ul>
                    <li v-for="(item1,index) in item.cityInfo" class="item bg-white"
                        :data-name="item1" :data-id="item1">
											<span @click="choose_subsite(item1.id,item1.m_site_url)"
                            :class="config.subsite_info.sitename==item1.sitename?'name name-act':'name'"
                            :data-id="item1" :data-name="item1">
												{{ item1.sitename }}
											</span>
                    </li>
                  </ul>
                </div>
              </div>
              <div v-else>
                <div class="cities cities-hook" v-for="(item,index) in subsiteList">
                  <div :id="`city${item.initial}`" class="title" v-if="item.cityInfo.length>0">
                    {{ item.initial }}
                  </div>
                  <ul>
                    <li v-for="(item1,index) in item.cityInfo" class="item bg-white"
                        :data-name="item1" :data-id="item1">
											<span @click="choose_subsite(item1.id,item1.m_site_url)"
                            :class="config.subsite_info.sitename==item1.sitename?'name name-act':'name'"
                            :data-id="item1" :data-name="item1">
												{{ item1.sitename }}
											</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <!--屏幕右边字母检索触摸滑动-->
            <div ref="shortcut" class="shortcut shortcut-hook">
              <ul class="shortcut-ul" v-if="searchVal!=''&&newArr.length>0">
                <li v-for="(item,index) in newArr" @click="goZM(`city${item.initial}`)"
                    :class="item.cityInfo.length>0?'item':'item displayNone'">
                  {{ item.initial }}
                </li>
              </ul>
              <ul class="shortcut-ul" v-else>
                <li v-for="(item,index) in subsiteList" @click="goZM(`city${item.initial}`)"
                    :class="item.cityInfo.length>0?'item':'item displayNone'">
                  {{ item.initial }}
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VueCookies from 'vue-cookies'

export default {
  name: 'SubsiteChoice',
  computed: {
    config () {
      return this.$store.state.config
    }
  },
  mounted () {
    this.getCityNameList()
  },
  data () {
    return {
      searchVal: '',
      subsiteList: [],
      newArr: [],
      // 城市首字母
      searchCityLetter: ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S',
        'T', 'W', 'X', 'Y', 'Z'
      ]
    }
  },
  methods: {
    searchTxtInput () {
      let newList = [...this.subsiteList],
        newArr = []
      newList.forEach(item => {
        if (item.cityInfo.length > 0) {
          let newCityInfo = [...item.cityInfo],
            newCityInfoObj = {},
            itemCity = {}
          itemCity.cityInfo = []
          newCityInfo.forEach(items => {
            if (items.sitename.indexOf(this.searchVal) != -1) {
              newCityInfoObj.initial = item.initial
              newCityInfoObj.cityInfo = []
              newCityInfoObj.cityInfo.push(items)
            }
            itemCity.initial = newCityInfoObj.initial
            if (newCityInfoObj.cityInfo) {
              itemCity.cityInfo.push(newCityInfoObj.cityInfo[0])
            }
          })
          if (itemCity.cityInfo.length > 0 && itemCity.initial) {
            newArr.push(itemCity)
          }
        }
      })
      this.newArr = newArr
    },
    choose_subsite (subsiteid, url = this.$store.state.config.mobile_domain) {
      // localStorage.setItem("qscms_subsiteid",subsiteid)
      if (this.$store.state.config.subsite_open === 1 && this.$store.state.config.subsite_domain != '') {
        VueCookies.set('qscms_subsiteid', subsiteid, 0, null, this.$store.state.config.subsite_domain)
      } else {
        VueCookies.set('qscms_subsiteid', subsiteid)
      }
      window.location.href = url
    },
    /**
     * 城市数据处理
     */
    getCityNameList () {
      this.subsiteList = []
      for (var i = 0; i < this.searchCityLetter.length; i++) {
        var initial = this.searchCityLetter[i]
        var cityInfo = []
        var tempArr = {}
        let citydata = this.config.subsite_list
        for (var j = 0; j < citydata.length; j++) {
          if (initial == citydata[j].initial) {
            cityInfo.push(citydata[j])
          }
        }
        tempArr.initial = initial
        tempArr.cityInfo = cityInfo
        this.subsiteList.push(tempArr)
      }
    },
    /**
     * 平滑锚点定位
     */
    goZM (item) {
      this.$el.querySelector(`#${item}`).scrollIntoView({
        behavior: 'smooth', // 平滑过渡
        block: 'center' // start 上边框  center 中间  end 底部边框 与视窗顶部平齐
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.isTop {
  width: 100%;
  position: fixed;
  top: 53px;
  left: 0;
  background: #fff;
  z-index: 9;
  box-shadow: 0 1px 1px rgba(0, 21, 41, .08);
}

.addr-list {
  padding-top: 108px;
}

.addr-list .all-subsite {
  width: 100%;
  box-sizing: border-box;
  padding: 15px;
  font-size: 16px;
  color: #010101;
}

.d3 {
  border-top: 1px solid #dcdfe6;
  box-sizing: border-box;
  padding: 15px;

  .search-input {
    background: #F5F5F5 url(../assets/images/mobileSearch.png) 10px center no-repeat;
    background-size: 14px 14px;
    border-radius: 25px;
    box-sizing: border-box;
    padding: 8px 18px 8px 30px;
  }
}

.d1 {
  padding: 0 15px 15px;
  font-size: 15px;

  .s_name {
    padding-left: 18px;
    color: #1989fa;
    margin-left: 5px;
    background: url("../assets/images/sub_current_ico.png") 0 center no-repeat;
    background-size: 13px;
  }
}

.d2 {
  padding: 0 15px;

  .d2_cell {
    width: 108px;
    float: left;
    margin-right: 10px;
    text-align: center;
    padding: 6px 10px;
    border: 1px solid #dcdfe6;
    border-radius: 4px;
    font-size: 15px;
    margin-bottom: 10px;

    &.checked {
      background: #1989fa;
      border-color: #1989fa;
      color: #ffffff;
    }

    &:nth-of-type(3n) {
      margin-right: 0;
    }
  }
}

.city {
  display: block;
}

.city .city-wrapper {
  width: 100%;
}

.city .city-wrapper .cities .title {
  height: 28px;
  padding-left: 16px;
  line-height: 28px;
  background-color: #f5f5f5;
  font-family: Helvetica;
  font-size: 12px;
  color: #878787;
}

.city .city-wrapper .cities .item {
  height: 55px;
  padding: 0 16px;
  line-height: 55px;
  font-size: 16px;
}

.city .city-wrapper .shortcut .displayNone {
  display: none;
}

.city .city-wrapper .cities .item .name {
  display: block;
  position: relative;
  color: #010101;
}

.city .city-wrapper .cities .item .name-act {
  color: #1989fa;
}

.city .city-wrapper .cities .item .name:before,
.city .city-wrapper .cities .item .name:after {
  display: block;
  position: absolute;
  border-top: 1px solid #EAEAEA;
  left: 0;
  width: 100%;
  content: " ";
}

.city .city-wrapper .cities .item .name:before {
  display: none;
  top: 0;
}

.city .city-wrapper .cities .item .name:after {
  display: block;
  bottom: 0;
}

.city .city-wrapper .cities .item:active {
  background-color: #f0f0f0;
}

.city .city-wrapper .cities .item:last-child .name:after {
  display: none;
}

.city .city-wrapper .shortcut {
  position: fixed;
  z-index: 30;
  width: 40px;
  top: 0;
  right: 8px;
  font-family: Helvetica;
  height: 100vh;
  display: flex;
  align-items: center;
}

.city .city-wrapper .shortcut .shortcut-ul {
  background: #F6F6F6;
  border: 2px solid #CCCCCC;
  border-radius: 40px;
  padding: 10px 0;
}

.city .city-wrapper .shortcut .item {
  height: 20px;
  text-align: center;
  font-size: 12px;
  color: #666666;
  margin-top: 4px;
  padding: 0 10px;
}

.showLetter {
  position: fixed;
  color: #896a0d;
  width: 50px;
  height: 50px;
  top: 45%;
  left: 45%;
  border-radius: 10%;
  border: #896a0d 1px solid;
  text-align: center;
  z-index: 9999;
  display: none;
}

.showLetter span {
  width: 50px;
  height: 50px;
  line-height: 50px;
  font-size: 30px;
}

/*二级菜单div视图容器*/
.conts {
  overflow: auto;
  display: none;
  width: 60%;
  height: 100%;
  right: 0;
  box-shadow: 0 0 2px 2px #e0e0e0;
  background-color: #efeff4;
  position: fixed;
  z-index: 2;
}

/*标题*/
.conts .cont_ser {
  overflow: auto;
  position: fixed;
  top: 0;
  bottom: 0;
  width: 100%;
}

/*车系*/
.car_series {
  overflow: auto;
  background-color: #fff;
  height: 28px;
  padding-left: 16px;
  line-height: 28px;
  background-color: #f5f5f5;
  font-family: Helvetica;
  font-size: 12px;
  color: #878787;
}

.car_seriess {
  overflow: auto;
  display: block;
  position: relative;
  margin: 0;
  border: 0;
  height: 44px;
  padding: 0 16px;
  line-height: 44px;
  font-size: 16px;
  border-top: 1px solid #e5e5e5;
}

/*三级菜单*/
.car_bse {
  overflow: auto;
  display: none;
  width: 65%;
  height: 100%;
  right: 0;
  box-shadow: 0 0 0 2px #e0e0e0;
  background-color: #efeff4;
  position: fixed;
  z-index: 3;
}

.car_bse .cont_ser {
  overflow: auto;
  position: fixed;
  width: 100%;
}

.carbse_ser {
  overflow: auto;
  display: block;
  position: relative;
  margin: 0;
  border-top: 1 solid #e5e5e5;
  height: 44px;
  padding: 0 16px;
  width: 230px;
  line-height: 44px;
  font-size: 16px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

@media only screen and (max-height: 6rem) {
  .city .city-wrapper .shortcut .item {
    padding-top: 3px;
  }
}
</style>
