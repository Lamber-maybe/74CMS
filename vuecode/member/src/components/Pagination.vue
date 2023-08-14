<template>
  <div class="page_group">
    <div class="page_content">
      <el-pagination
        background
        layout="prev, pager, next"
        :total="total"
        :page-size="pageSize === undefined ? 10 : pageSize"
        :current-page="currentPage === undefined ? 1 : currentPage"
        @current-change="handleCurrentChange"
      >
      </el-pagination>
    </div>
  </div>
</template>

<script>
export default {
  name: "Pagination",
  props: ["total", "pageSize", "currentPage"],
  methods: {
    handleCurrentChange(val) {
      this.$emit("handleCurrentChange", val);
    }
  }
};
</script>

<style lang="scss" scoped>
.page_group {
  text-align: center;
  font-size: 0;
  padding: 25px 0;
  .page_content {
    display: inline-block;
  }
}
</style>
