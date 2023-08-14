<?php
/**
 * 文件存储
 *
 * @author
 */

namespace app\common\lib;

use app\common\lib\Qiniu;
use think\File;

class FileManager
{
    protected $_error;
    protected $fileupload_type;
    protected $fileupload_size;
    protected $fileupload_ext;
    protected $validate;
    protected $uploadfile_dir;
    protected $uploadfile_path;
    protected $filter;

    public function __construct($config = [])
    {
        $global_config = config('global_config');
        if (!empty($config)) {
            $global_config = array_merge($global_config, $config);
        }
        $this->fileupload_type = $global_config['fileupload_type']
            ? $global_config['fileupload_type']
            : 'default';

        $this->fileupload_size = $global_config['fileupload_size']
            ? $global_config['fileupload_size'] * 1024
            : '';
        $this->fileupload_ext = $global_config['fileupload_ext']
            ? $global_config['fileupload_ext']
            : '';
        $this->validate = [];
        $this->fileupload_size &&
        ($this->validate['size'] = $this->fileupload_size);
        $this->fileupload_ext &&
        ($this->validate['ext'] = $this->fileupload_ext);
        $this->uploadfile_dir = 'files';
        $this->uploadfile_path = SYS_UPLOAD_PATH . $this->uploadfile_dir;
        $this->filter = isset($global_config['filter']) ? $global_config['filter'] : 1;
    }

    /**
     * 上传文件
     */
    public function uploadReturnPath($file)
    {
        if (!$file) {
            $this->_error = '请上传文件';
            return false;
        }
        $info = $file->validate($this->validate)->move($this->uploadfile_path);
        if ($info) {
            $return = [
                'save_path' => $this->uploadfile_dir . '/' . $info->getSaveName()
            ];
            return $return;
        } else {
            // 上传失败获取错误信息
            $this->_error = $file->getError();
            return false;
        }
    }

    /**
     * 上传视频文件
     */
    public function uploadVideoReturnPath($file)
    {
        if (!$file) {
            $this->_error = '请上传文件';
            return false;
        }
        $info = $file->move($this->uploadfile_path);
        if ($info) {
            $return = [
                'save_path' => $this->uploadfile_dir . '/' . $info->getSaveName()
            ];
            return $return;
        } else {
            // 上传失败获取错误信息
            $this->_error = $file->getError();
            return false;
        }
    }

    /**
     * 上传文件
     */
    public function upload($file, $filter = true)
    {
        if (!$file) {
            $this->_error = '请上传文件';
            return false;
        }
        $method = '_upload_' . $this->fileupload_type;
        if ($this->fileupload_type === 'default') {
            $upload_result = $this->$method($file, $filter);
        } else {
            $upload_result = $this->$method($file);
        }
        if (false !== $upload_result) {
            $file_id = $this->recordToDb($upload_result['save_path']);
            return [
                'file_url' => make_file_url(
                    $upload_result['save_path'],
                    $this->fileupload_type
                ),
                'file_id' => $file_id
            ];
        } else {
            return false;
        }
    }

    public function save_path($file_path, $fileupload_type)
    {
        $file_id = $this->recordToDb($file_path, $fileupload_type);
        return [
            'file_url' => make_file_url(
                $file_path,
                $fileupload_type
            ),
            'file_id' => $file_id
        ];
    }

    /**
     * 上传视频
     */
    public function uploadVideo($file)
    {
        if (!$file) {
            $this->_error = '请选择文件';
            return false;
        }
        $file_url = (new Qiniu())->uploadStream($file, uuid() . '.mp4');

        if ($file_url) {
            $file_id = $this->recordToDb($file_url, 'qiniu');
            return [
                'file_url' => make_file_url(
                    $file_url, 'qiniu'
                ),
                'file_id' => $file_id
            ];
        } else {
            return false;
        }
    }

    /**
     * 删除文件
     */
    public function delete($file_id)
    {
        if (is_array($file_id)) {
            $file_id_arr = $file_id;
        } else {
            $file_id_arr = [$file_id];
        }
        foreach ($file_id_arr as $key => $value) {
            $file_id_arr[$key] = intval($value);
        }
        $file_info_arr = \app\common\model\Uploadfile::all($file_id_arr);
        if (!empty($file_info_arr)) {
            $qiniu = new Qiniu();
            foreach ($file_info_arr as $key => $value) {
                if ($value->platform == 'qiniu') {
                    $_qiniu = clone $qiniu;
                    $_qiniu->delete($value->save_path);
                    unset($_qiniu);
                } else {
                    $_tmp_path = str_replace(
                        $this->uploadfile_dir,
                        '',
                        $value->save_path
                    );
                    @unlink($this->uploadfile_path . $_tmp_path);
                }
            }
            $this->deleteFromDb($file_id_arr);
        }
        return true;
    }

    /**
     * 上传文件到本地存储
     */
    protected function _upload_default($file, $filter = true)
    {
        $info = $file->validate($this->validate)->move($this->uploadfile_path);
        if ($info) {
            if ($this->filter == 1 && $filter) {
                $image = \think\Image::open(
                    $this->uploadfile_path . '/' . $info->getSaveName()
                );
                // 返回图片的宽度
                $width = $image->width();
                // 返回图片的高度
                $height = $image->height();
                $image
                    ->thumb($width, $height)
                    ->save($this->uploadfile_path . '/' . $info->getSaveName());
            }

            $return = [
                'save_path' => $this->uploadfile_dir . '/' . $info->getSaveName()
            ];
            return $return;
        } else {
            // 上传失败获取错误信息
            $this->_error = $file->getError();
            return false;
        }
    }

    /**
     * 上传文件到七牛云
     */
    protected function _upload_qiniu($file)
    {
        $file = $file->getInfo();
        $validate = [];
        isset($this->validate['size']) &&
        ($validate['maxSize'] = $this->validate['size']);
        isset($this->validate['ext']) &&
        ($validate['exts'] = $this->validate['ext']);
        $qiniu = new Qiniu($validate);
        $file_url = $qiniu->upload($file);
        if ($file_url) {
            $return = [
                'save_path' => $file_url
            ];
            return $return;
        } else {
            $this->_error = $qiniu->getError();
            return false;
        }
    }

    /**
     * 记录到数据表
     */
    public function recordToDb($save_path, $fileupload_type = '')
    {
        $model = new \app\common\model\Uploadfile();
        $model->save_path = $save_path;
        $model->platform = $fileupload_type ?: $this->fileupload_type;
        $model->addtime = time();
        $model->save();
        return $model->id;
    }

    /**
     * 从数据表中删除记录
     */
    protected function deleteFromDb($file_id)
    {
        if (is_array($file_id)) {
            $file_id_arr = $file_id;
        } else {
            $file_id_arr = [$file_id];
        }
        foreach ($file_id_arr as $key => $value) {
            $file_id_arr[$key] = intval($value);
        }
        model('Uploadfile')->destroy($file_id_arr);
    }

    /**
     * 获取错误信息
     */
    public function getError()
    {
        return $this->_error;
    }
}
