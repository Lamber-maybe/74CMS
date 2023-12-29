<template>
  <div>
    <script :id="randomId" type="text/plain" />
  </div>
</template>
<script>
export default {
  name: 'UE',
  props: {
    defaultMsg: {
      type: String
    },
    randomId: {
      type: String
    }
  },
  data() {
    return {
      editor: null,
      // 富文本配置项
      config: {
        initialFrameWidth: null, // 宽度
        initialFrameHeight: 400, // 高度
        toolbars: [[
          // 'fullscreen', // 全屏
          'source', // 源代码

          '|',

          'undo', // 撤销
          'redo', // 重做

          '|',

          'bold', // 加粗
          'italic', // 斜体
          'underline', // 下划线
          'fontborder', // 字符边框
          'strikethrough', // 删除线
          // 'superscript', // 上标
          // 'subscript', // 下标
          // 'removeformat', // 清除格式
          // 'formatmatch', // 格式刷
          // 'autotypeset', // 自动排版
          // 'blockquote', // 引用
          'pasteplain', // 纯文本粘贴模式

          '|',

          'forecolor', // 字体颜色
          'backcolor', // 背景颜色
          'insertorderedlist', // 有序列表
          'insertunorderedlist', // 无序列表
          'selectall', // 全选
          'cleardoc', // 清空文档

          '|',

          'rowspacingtop', // 段前距
          'rowspacingbottom', // 段后距
          'lineheight', // 行间距

          '|',

          // 'customstyle', // 自定义标题
          // 'paragraph', // 段落格式
          'fontfamily', // 字体
          'fontsize', // 字号

          '|',

          // 'directionalityltr', // 从左向右输入
          // 'directionalityrtl', // 从右向左输入
          'indent', // 首行缩进

          '|',

          'justifyleft', // 居左对齐
          'justifycenter', // 居中对齐
          'justifyright', // 居右对齐
          'justifyjustify', // 两端对齐

          '|',

          'link', // 超链接
          'unlink', // 取消链接

          '|',

          // 'imagenone', // 默认
          // 'imageleft', // 左浮动
          // 'imageright', // 右浮动
          // 'imagecenter', // 居中

          // '|',

          'simpleupload', // 单个图片上传
          // 'attachment', //附件
          // 'emotion', // 表情
          // 'pagebreak', // 分页
          // 'template', // 模板
          //			'background', // 背景

          '|',

          'horizontal', // 分割线
          'date', // 日期
          'time', // 时间
          // 'wordimage', // 图片转存

          // '|',
          //
          // 'inserttable', // 插入表格
          // 'deletetable', // 删除表格
          // 'insertparagraphbeforetable', // 表格前插入行
          // 'insertrow', // 前插入行
          // 'deleterow', // 删除行
          // 'insertcol', // 前插入列
          // 'deletecol', // 删除列
          // 'mergecells', // 合并多个单元格
          // 'mergeright', // 右合并单元格
          // 'mergedown', // 下合并单元格
          // 'splittocells', // 完全拆分单元格
          // 'splittorows', // 拆分成行
          // 'splittocols', // 拆分成列
          // 'charts' // 图表
        ]]
      }

    }
  },
  watch: {
    defaultMsg(newVal) {
      this.editor.setContent(newVal)
    }
  },
  mounted() {
    const _this = this
    _this.$nextTick(() => {
      this.editor = UE.getEditor(_this.randomId, this.config)
      // 初始化UE
      this.editor.addListener('ready', function () {
        _this.editor.setContent(_this.defaultMsg) // 确保UE加载完成后，放入内容。
      })
    })
  },
  destroyed() {
    this.editor.destroy()
  },
  methods: {
    setUEContent(label){
      this.editor.execCommand('insertHtml', label)
    },
    setContent(obj){
      this.editor.setContent(obj)
    },
    getUEContent() { // 获取内容方法
      return this.editor.getContent()
    }

  }
}
</script>
<style scoped>

</style>
