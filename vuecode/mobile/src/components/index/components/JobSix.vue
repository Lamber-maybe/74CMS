<template>
  <div>
    <div class="b1">
      <div class="list" v-for="(level1, index1) in list" :key="index1">
        <div class="title">
          {{ level1.text }}
          <div
            class="more"
            @click="
              $router.push(
                '/joblist?category1=' + level1.id + '&category2=0&category3=0'
              )
            "
          >
            更多
          </div>
        </div>
        <div class="content">
          <router-link
            class="link"
            v-for="(level2, index2) in level1.children"
            :key="index2"
            :to="{
              path:
                '/joblist?category1=' +
                level1.id +
                '&category2=' +
                level2.pid +
                '&category3=' +
                level2.id
            }"
          >
            {{ level2.text }}
          </router-link>
          <div class="clear"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'JobSix',
  data () {
    return {
      list: []
    }
  },
  created () {
    this.$store.dispatch('getClassify', 'jobcategory').then(() => {
      this.$store.state.classifyJobCategory.forEach(itemLevel1 => {
        let tmparr = {
          text: itemLevel1.text,
          id: itemLevel1.id,
          children: []
        }
        if (itemLevel1.children.length > 0) {
          itemLevel1.children.forEach(itemLevel2 => {
            if (itemLevel2.children.length > 0) {
              itemLevel2.children.forEach(element => {
                element.pid = itemLevel2.id
              })
              tmparr.children = tmparr.children.concat(itemLevel2.children)
            }
          })
        }
        tmparr.children = tmparr.children.slice(0, 10)
        this.list.push(tmparr)
      })
    })
  },
  computed: {}
}
</script>

<style lang="scss" scoped>
.b1 {
  .list {
    .content {
      .link {
        display: block;
        float: left;
        text-decoration: none;
        font-size: 13px;
        color: #666666;
        margin: 0 15px 9px 0;
      }
      padding: 0 10px 5px 30px;
    }
    .title {
      .more {
        &::after {
          position: absolute;
          right: 2px;
          top: 50%;
          width: 6px;
          height: 6px;
          margin-top: -3px;
          border-top: 1px solid #999999;
          border-right: 1px solid #999999;
          transform: rotate(45deg);
          content: " ";
        }
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 12px;
        color: #999999;
        line-height:normal;
        padding: 1px 11px 1px 0;
      }
      &::before {
        position: absolute;
        left: 18px;
        top: 21px;
        width: 3px;
        height: 16px;
        background-color: #1787fb;
        content: " ";
      }
      position: relative;
      padding: 16.5px 0 12px 30px;
      font-size: 18px;
      font-weight: bold;
      color: #333333;
    }
    &:not(:last-child) {
      border-bottom: 1px solid #f3f3f3;
    }
  }
  width: 100%;
  background-color: #ffffff;
}
</style>
