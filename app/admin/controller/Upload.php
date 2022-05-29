<?php


namespace app\admin\controller;

use app\common\libs\storage\StorageFactory;
use app\common\libs\storage\Validate;

/**
 * 上传管理
 * @access 上传管理|Admin/Upload|3
 */
class Upload extends UserBase {

    /**
     * 上传图片
     * @access 上传图片|Admin/Upload/imageUpload|3
     */
    public function imageUpload() {
        if (!$this->request->isPost()) {
            return $this->ajaxFail('非法访问');
        }
        // 获取表单上传文件 例如上传了001.jpg
        if (empty($_FILES['file'])) {
            return $this->ajaxFail('请选择上传文件');
        }
        $validate = Validate::getInstance();
        $rule = [
            $validate::RULE_SIZE => [0, 5000000],
            $validate::RULE_EXTENSION => array('png', 'bmp', 'jpeg', 'jpg'),
        ];
        if (!$validate->validate($rule, 'file')) {
            return $this->ajaxFail('上传失败:' . $validate->get_error());
        }
        $storage = StorageFactory::create(StorageFactory::DRIVER_LOCAL);
        $upload_path = config('file_storage.local_path') . $this->site_id . '/';
        $storage->setFileKey('file');
        $storage->setLocalPath($upload_path);
        $storage->set_name();
        $storage->set_extension();
        $storage->set_file_type();
        $result = $storage->save();
        /*$storage->set_delete_target($result);
        $result = $storage->delete();
        $storage->set_lists_path($upload_path);
        $result = $storage->lists();*/
        $url = substr($result, strlen(dirname(config('admin.UPLOAD_PATH'))));
        if ($result) {
            $data = array(
                'name' => $url,
                'thumb' => getImage($url),
            );
            return $this->ajaxSuccess('上传成功', $data);
        } else {
            return $this->ajaxFail('上传失败:' . $storage->get_error());
        }
        //print_g($url);
        $fileStorage = \app\common\libs\fileStorage\FileStorage::getInstance($_FILES['file']);
        /*		#验证
                $rule = [
                    'size' => 5000000,#size的单位是bit
                    'ext' => array('png', 'bmp', 'jpeq', 'jpg'),
                ];
                if (!$fileStorage->validate($rule)) {
                    return $this->ajaxFail('上传失败:' . $fileStorage->getError());
                }*/
        $new_filename = $fileStorage->generateNewFileName();
        $prefix_path = $this->site_id . '/' . date('Y') . '/' . $fileStorage->getFileMimeType();
        $result = $fileStorage->setPreFilePath($prefix_path)
            ->saveFile($new_filename);
        if ($result) {
            $data = array(
                'name' => $fileStorage->saved_file_path,
                'thumb' => getImage($fileStorage->saved_file_path),
            );
            return $this->ajaxSuccess('上传成功', $data);
        } else {
            return $this->ajaxFail('上传失败:' . $fileStorage->getError());
        }
    }


    /**
     * 上传文件
     * @access 上传文件|Admin/Upload/fileUpload|3
     */
    public function fileUpload() {
        if (!$this->request->isPost()) {
            return $this->ajaxFail('非法访问');
        }
        $fileStorage = \app\common\libs\fileStorage\FileStorage::getInstance();
        $fileStorage = $fileStorage->getStorageObject($fileStorage::storage_object_local_storage);
        // 获取表单上传文件 例如上传了001.jpg
        if (empty($_FILES)) {
            return $this->ajaxFail('请选择上传文件');
        }
//		$upload_path = config('admin.UPLOAD_PATH');
        #验证
        $rule = [
            'size' => 5000000,#size的单位是bit
            'ext' => array('png', 'bmp', 'jpeq', 'jpg'),
        ];
        if (!$fileStorage->validate($rule)) {
            return $this->ajaxFail('上传失败:' . $fileStorage->getError());
        }
        $prefix_path = $this->site_id . '/' . date('Y') . '/' . $fileStorage->getFileMimeType();
        $new_filename = $fileStorage->generateNewFileName();
        $fileStorage->setPreFilePath($prefix_path);
        $result = $fileStorage->saveFile($new_filename);
        if ($result) {
            $data = array(
                'name' => $fileStorage->saved_file_path,
                'thumb' => getImage($fileStorage->saved_file_path),
            );
            return $this->ajaxSuccess('上传成功', $data);
        } else {
            return $this->ajaxFail('上传失败:' . $fileStorage->getError());
        }
    }

    /**
     * 删除文件
     * @access 删除文件|Admin/Upload/deleteFile|3
     */
    public function deleteFile() {
        if (!$this->request->isPost()) {
            return $this->ajaxFail('非法访问');
        }
        $file_name = $this->request->post('key');
        if (!$file_name) {
            return $this->ajaxFail('请选择需要删除的文件');
        }
        $result = $this->deleteFileOss($file_name);
        if ($result) {
            return $this->ajaxSuccess('删除成功');
        } else {
            return $this->ajaxFail('删除失败,' . $this->getMessage());
        }
    }

    /**
     * 删除本地文件
     * @param $file_name
     * @return bool
     */
    private function deleteFileLocal($file_name) {
        $upload_path = config('admin.UPLOAD_PATH');
        $filePath = $upload_path . '/' . $file_name;
        if (!file_exists($filePath)) {
            $this->setMessage('文件不存在');
            return false;
        }
        #图片删除缩略图
        if (isImage($filePath)) {
            $size_config = config('admin.IMAGE_SIZE');
            $thumb_path = config('admin.UPLOAD_PATH') . '/' . config('admin.THUMB_DIR') . '/';
            $thumb_image_name = function ($name, $size) {
                $names = explode('.', $name);
                $ext = array_pop($names);
                $newName = array_pop($names);
                return implode('.', array($newName . $size, $ext));
            };
            foreach ($size_config as $key => $value) {
                $thumb_image_path = $thumb_path . $thumb_image_name($file_name, $value);
                if (file_exists($thumb_image_path)) {
                    unlink($thumb_image_path);
                }
            }
        }
        #删除文件
        $result = unlink($filePath);
    }

    /**
     * 删除oss存储图片
     * @param $file_name
     * @return bool
     */
    private function deleteFileOss($file_name) {
        $aliyunOss = \app\common\libs\fileStorage\driver\aliyunOssStorage\AliyunOss::getInstance();
        if (!$aliyunOss->doesObjectExist($aliyunOss->aliyun_oss_bucket, $file_name)) {
            $this->setMessage('文件不存在');
            return false;
        }
        return $aliyunOss->deleteObject($aliyunOss->aliyun_oss_bucket, $file_name);
    }

    /**
     * 转存本地文件到oss
     * @access 转存本地文件到oss|Admin/Upload/localToOss|a702a45e-22ae-11e9-9fd7-00163e003500|3
     */
    public function localToOss() {
        try {
            if (!$this->request->isPost()) {
                return $this->ajaxFail('非法请求');
            }
            $file_name = $this->request->param('file_name');
            $file_save_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $file_name;
            $fileStorage = \app\common\libs\fileStorage\FileStorage::getInstance($file_save_path);
            $new_file_name = $fileStorage->generateNewFileName();
            $prefix_path = $this->site_id . '/' . date('Y') . '/' . $fileStorage->getFileMimeType();
            $oss_result = $fileStorage->setPreFilePath($prefix_path)
                ->saveFile($new_file_name);
            $data['url'] = getImage($fileStorage->saved_file_path);
            $data['title'] = $fileStorage->saved_file_path;
            unlink($file_save_path);
            return success('', $data);
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }

}
