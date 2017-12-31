<?php


namespace app\controller;


use Upload\File;
use Upload\Storage\FileSystem;
use Upload\Validation\Dimensions;
use Upload\Validation\Extension;
use Upload\Validation\Mimetype;
use Upload\Validation\Size;

/**
 * 上传管理
 * Desc: UploadController
 * @privilege 上传管理|Admin/Upload|c254964d-200a-11e7-8ad5-9cb3ab404081|3
 * @author CPR137
 */
class UploadController extends UserBaseController
{



    /**
     * 上传图片
     * @privilege 百度编辑器操作|Admin/Upload/index|c28f457a-200a-11e7-8ad5-9cb3ab404081|3
     */
    public function index()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法访问');
        }
        if (empty($_FILES)) {
            $this->ajaxFail('请选择图片');
        }
        $key = key($_FILES);
        $storage = new FileSystem(C('UPLOAD_PATH'));
        $file = new File($key, $storage);

        $new_filename = md5(uniqid());
        $file->setName($new_filename);

        #验证
        $roles = array(
            new Extension(array('png', 'bmp', 'jpeq', 'jpg')),
            new Size('5M')
        );
        $file->addValidations($roles);

        #上传
        try {
            $file->upload();
            $data = array(
                'name' => $file->getNameWithExtension(),
                'thumb' => getImage($file->getNameWithExtension())
            );
            $this->ajaxSuccess('上传成功', $data);
        } catch (\Exception $e) {
            $this->ajaxFail('上传失败:' . current($file->getErrors()));
        }
    }

    /**
     * 删除文件
     * @privilege 删除文件|Admin/Upload/deleteFile|c29d42ec-200a-11e7-8ad5-9cb3ab404081|3
     */
    public function deleteFile()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法访问');
        }
        $imageName = isset($_POST['key']) ? trim($_POST['key']) : '';
        if (!$imageName) {
            $this->ajaxFail('请选择需要删除的文件');
        }
        $filePath = C('UPLOAD_PATH') . '/' . $imageName;
        $result = false;
        if (file_exists($filePath)) {
            $result = unlink($filePath);
        }
        if ($result) {
            $this->ajaxSuccess('删除成功');
        } else {
            $this->ajaxFail('文件不存在或者文件删除失败');
        }
    }

}
