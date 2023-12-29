<template>
  <div class="add_group">
    <div class="import_title">快速导入简历</div>
    <div class="import_content">
      <div class="loadingStatus" v-if="loading">
        <div class="loadingImg"><img class="loadingIcon" src="../../assets/images/personal/resume/loading.gif" alt="" /></div>
        <div class="loadingTxt">简历正在解析中，请耐心等待...</div>
      </div>
      <el-upload v-else class="upload-demo" drag action="#" :show-file-list="false" :before-upload="beforeUpload" :http-request="handlerUpload">
        <div class="el-upload__text">拖动文件到这里</div>
        <div class="uploadTips">简历解析支持的格式：txt、html、pdf、doc、docx，文件不超过2M</div>
      </el-upload>
    </div>
    <div class="import_btn_box">
      <el-upload class="upload-demo" action="#" :http-request="handlerUpload" :show-file-list="false" :before-upload="beforeUpload">
        <el-button v-if="loading" class="import_btn" size="small" type="primary" disabled>附件简历上传中..</el-button>
        <el-button v-else class="import_btn" size="small" type="primary">选择上传的文件</el-button>
      </el-upload>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http';
import api from '@/api';

export default {
  name: 'PersonalOneClickImport',
  data() {
    return {
      loading: false,
      timer: null
    };
  },
  computed: {},
  beforeDestroy() {},
  created() {
  },
  mounted() {},
  watch: {},
  methods: {
    // 上传文件之前验证格式及大小
    beforeUpload(file) {
      let fileArr = file?.name.split('.');
      let fileType = fileArr[fileArr.length - 1];
      let typeArr = ['txt', 'html', 'pdf', 'doc', 'docx'];
      if (-1 === typeArr.indexOf(fileType)) {
        this.$message.error('文件类型不支持');
        return false;
      }
      if (file.size > 2048 * 1024) {
        let size = 2048 / 1024;
        size = size.toFixed(1);
        this.$message.error('文件大小超出限制，最大' + size + 'M');
        return false;
      }
    },
    handlerUpload(file) {
      http
        .postFormData(api.uploadFile, {
          file: file.file
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            const enclosure_id = res.data.file_id
            this.loading = true;
            http
              .post(api.analysisCreate, {
                enclosure_id: enclosure_id
              })
              .then(data => {
                if (data.code == 200) {
                  this.loading = false;
                  data.data.enclosure_id = enclosure_id
                  localStorage.setItem('resumeDetail', JSON.stringify(data.data));
                  this.$router.push('/personal/resume/resume_import');
                }
              })
              .catch(() => {});
          } else {
            this.loading = false;
          }
        })
        .catch(err => {
          console.log(err);
        });
    }
  }
};
</script>

<style lang="scss" scoped>
.add_group {
  width: 1200px;
  background-color: #fff;
  margin-top: 15px;
  padding-top: 75px;

  .import_title {
    width: 100%;
    text-align: center;
    color: #333;
    font-size: 26px;
    margin: 0 auto 45px;
  }

  .import_content {
    width: 578px;
    height: 375px;
    background: #fbfbfb;
    margin: 0 auto;
    border: 1px dashed #eeeeee;
    border-radius: 10px;

    .upload-demo {
      text-align: center;
      color: #666666;
      font-size: 14px;

      ::v-deep .el-upload-dragger {
        background: transparent;
      }

      .el-upload__text {
        margin: 140px auto 60px;
      }

      .el-upload__tip {
        font-size: 14px;
        color: #999;
      }

      .uploadTips{
        font-size: 14px;
        color: #999;
      }
    }

    .loadingStatus {
      .loadingTxt {
        width: 100%;
        text-align: center;
        font-size: 16px;
        color: #409eff;
      }

      .loadingImg {
        width: 100px;
        height: 100px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 102px auto 20px;

        .loadingIcon {
          display: block;
          width: 50px;
          height: 50px;
        }
      }
    }
  }

  .import_btn_box {
    width: 100%;
    text-align: center;
    padding: 34px 0 160px;

    .import_btn {
      width: 360px;
      height: 54px;
      background: #409eff;
      box-shadow: 0px 2px 6px 0px rgba(64, 158, 255, 0.5);
      border-radius: 4px;
      font-size: 18px;
    }
  }
}
::v-deep .el-upload {
  width: 100%;
  height: 100%;
  padding-bottom: 139px;
}
::v-deep .el-upload .el-upload-dragger {
  width: 100%;
  height: 100%;
  padding-bottom: 139px;
}
</style>
