<?php
/**
 * Created by PhpStorm.
 * User: mgckid
 * Date: 2019/1/19
 * Time: 23:54
 */

namespace ueditor;


class Ueditor {

    /**
     * ueditor配置
     * @return mixed
     */
    public function getConfig() {
        #编辑器默认配置
        $configFileName = __DIR__ . '/config.json';
        $default_config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents($configFileName)), true);
        #系统自定义配置
        $ueditor_config = (array)config('ueditor.');
        #合并配置
        $config = array_merge($default_config, $ueditor_config);
        return $config;
    }

    /**
     * ueditor 上传
     * @return string
     */
    public function action_upload() {
        $CONFIG = $this->getConfig();
        /* 上传配置 */
        $base64 = "upload";
        switch (htmlspecialchars($_GET['action'])) {
            case 'uploadimage':
                $config = array(
                    "pathFormat" => $CONFIG['imagePathFormat'],
                    "maxSize" => $CONFIG['imageMaxSize'],
                    "allowFiles" => $CONFIG['imageAllowFiles']
                );
                $fieldName = $CONFIG['imageFieldName'];
                break;
            case 'uploadscrawl':
                $config = array(
                    "pathFormat" => $CONFIG['scrawlPathFormat'],
                    "maxSize" => $CONFIG['scrawlMaxSize'],
                    "allowFiles" => $CONFIG['scrawlAllowFiles'],
                    "oriName" => "scrawl.png"
                );
                $fieldName = $CONFIG['scrawlFieldName'];
                $base64 = "base64";
                break;
            case 'uploadvideo':
                $config = array(
                    "pathFormat" => $CONFIG['videoPathFormat'],
                    "maxSize" => $CONFIG['videoMaxSize'],
                    "allowFiles" => $CONFIG['videoAllowFiles']
                );
                $fieldName = $CONFIG['videoFieldName'];
                break;
            case 'uploadfile':
            default:
                $config = array(
                    "pathFormat" => $CONFIG['filePathFormat'],
                    "maxSize" => $CONFIG['fileMaxSize'],
                    "allowFiles" => $CONFIG['fileAllowFiles']
                );
                $fieldName = $CONFIG['fileFieldName'];
                break;
        }
        /* 生成上传实例对象并完成上传 */
        $up = new \app\common\libs\ueditor\Uploader($fieldName, $config, $base64);

        /**
         * 得到上传文件所对应的各个参数,数组结构
         * array(
         *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
         *     "url" => "",            //返回的地址
         *     "title" => "",          //新文件名
         *     "original" => "",       //原始文件名
         *     "type" => ""            //文件类型
         *     "size" => "",           //文件大小
         * )
         */

        /* 返回数据 */
//        return json_encode($up->getFileInfo());
        #上传oss
        $result = $up->getFileInfo();
        $title = $result['title'];
        $file_save_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $title;
        $fileStorage = \app\common\libs\fileStorage\FileStorage::getInstance($file_save_path);
        $new_file_name = $fileStorage->generateNewFileName();
        $group_id = \app\common\libs\Message::getInfo('loginInfo')['group_id'];
        $prefix_path = $group_id . '/' . date('Y') . '/' . $fileStorage->getFileMimeType();
        $oss_result = $fileStorage->setPreFilePath($prefix_path)
            ->saveFile($new_file_name);
        $result['url'] = getImage($fileStorage->saved_file_path);
        $result['title'] = $fileStorage->saved_file_path;
        #删除本地文件
        unlink($file_save_path);
        return json_encode($result);
    }

    public function action_list() {

        $CONFIG = $this->getConfig();
        $fileStorage = \app\common\libs\fileStorage\FileStorage::getInstance();
        $group_id = \app\common\libs\Message::getInfo('loginInfo')['group_id'];
        $prefix_file_path = $group_id . '/' . date('Y') . '/';
        switch ($_GET['action']) {
            /* 列出文件 */
            case 'listfile':
                $allowFiles = $CONFIG['fileManagerAllowFiles'];
                $listSize = $CONFIG['fileManagerListSize'];
                $path = $CONFIG['fileManagerListPath'];
                $prefix_file_path .= $fileStorage::file_type_application;
                break;
            /* 列出图片 */
            case 'listimage':
            default:
                $allowFiles = $CONFIG['imageManagerAllowFiles'];
                $listSize = $CONFIG['imageManagerListSize'];
                $path = $CONFIG['imageManagerListPath'];
                $prefix_file_path .= $fileStorage::file_type_image;
        }
        /* 获取参数 */
        $size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
        $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
        $end = $start + $size;

        $files = $fileStorage->setPreFilePath($prefix_file_path)->listFile();
        if (!count($files)) {
            return json_encode(array(
                "state" => "no match file",
                "list" => array(),
                "start" => $start,
                "total" => count($files)
            ));
        }

        /* 获取指定范围的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--) {
            $list[] = $files[$i];
        }
        /* 返回数据 */
        $result = json_encode(array(
            "state" => "SUCCESS",
            "list" => $list,
            "start" => $start,
            "total" => count($files)
        ));
        return $result;
    }

    /**
     * ueditor 获取已上传的文件列表
     */
    public function action_list1() {
        $CONFIG = $this->getConfig();

        /* 判断类型 */
        switch ($_GET['action']) {
            /* 列出文件 */
            case 'listfile':
                $allowFiles = $CONFIG['fileManagerAllowFiles'];
                $listSize = $CONFIG['fileManagerListSize'];
                $path = $CONFIG['fileManagerListPath'];
                break;
            /* 列出图片 */
            case 'listimage':
            default:
                $allowFiles = $CONFIG['imageManagerAllowFiles'];
                $listSize = $CONFIG['imageManagerListSize'];
                $path = $CONFIG['imageManagerListPath'];
        }

        $allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);

        /* 获取参数 */
        $size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
        $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
        $end = $start + $size;

        /* 获取文件列表 */
        $path = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == "/" ? "" : "/") . $path;
        $files = getfiles($path, $allowFiles);
        if (!count($files)) {
            return json_encode(array(
                "state" => "no match file",
                "list" => array(),
                "start" => $start,
                "total" => count($files)
            ));
        }

        /* 获取指定范围的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--) {
            $list[] = $files[$i];
        }
//倒序
//for ($i = $end, $list = array(); $i < $len && $i < $end; $i++){
//    $list[] = $files[$i];
//}

        /* 返回数据 */
        $result = json_encode(array(
            "state" => "SUCCESS",
            "list" => $list,
            "start" => $start,
            "total" => count($files)
        ));

        return $result;


    }

    /***
     * ueditor 抓取远程图片
     * @return string
     */
    public function action_crawler() {
        $CONFIG = $this->getConfig();
        set_time_limit(0);

        /* 上传配置 */
        $config = array(
            "pathFormat" => $CONFIG['catcherPathFormat'],
            "maxSize" => $CONFIG['catcherMaxSize'],
            "allowFiles" => $CONFIG['catcherAllowFiles'],
            "oriName" => "remote.png"
        );
        $fieldName = $CONFIG['catcherFieldName'];

        /* 抓取远程图片 */
        $list = array();
        if (isset($_POST[$fieldName])) {
            $source = $_POST[$fieldName];
        } else {
            $source = $_GET[$fieldName];
        }
        foreach ($source as $imgUrl) {
            $item = new \app\common\libs\ueditor\Uploader($imgUrl, $config, "remote");
            $info = $item->getFileInfo();
            array_push($list, array(
                "state" => $info["state"],
                "url" => $info["url"],
                "size" => $info["size"],
                "title" => htmlspecialchars($info["title"]),
                "original" => htmlspecialchars($info["original"]),
                "source" => htmlspecialchars($imgUrl)
            ));
        }
        if ($list) {
            foreach ($list as $key => $value) {
                #上传oss
                $title = $value['title'];
                $file_save_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $title;
                $fileStorage = \app\common\libs\fileStorage\FileStorage::getInstance($file_save_path);
                $new_file_name = $fileStorage->generateNewFileName();
                $group_id = \app\common\libs\Message::getInfo('loginInfo')['group_id'];
                $prefix_path = $group_id . '/' . date('Y') . '/' . $fileStorage->getFileMimeType();
                $oss_result = $fileStorage->setPreFilePath($prefix_path)
                    ->saveFile($new_file_name);
                $value['url'] = getImage($fileStorage->saved_file_path);
                $value['title'] = $fileStorage->saved_file_path;
                $list[$key] = $value;
                #删除本地文件
                unlink($file_save_path);
            }
        }
        /* 返回抓取数据 */
        return json_encode(array(
            'state' => count($list) ? 'SUCCESS' : 'ERROR',
            'list' => $list
        ));
    }
}

/**
 * 遍历获取目录下的指定类型的文件
 * @param $path
 * @param array $files
 * @return array
 */
function getfiles($path, $allowFiles, &$files = array()) {
    if (!is_dir($path)) return null;
    if (substr($path, strlen($path) - 1) != '/') $path .= '/';
    $handle = opendir($path);
    while (false !== ($file = readdir($handle))) {
        if ($file != '.' && $file != '..') {
            $path2 = $path . $file;
            if (is_dir($path2)) {
                getfiles($path2, $allowFiles, $files);
            } else {
                if (preg_match("/\.(" . $allowFiles . ")$/i", $file)) {
                    $files[] = array(
                        'url' => substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
                        'mtime' => filemtime($path2)
                    );
                }
            }
        }
    }
    return $files;
}
