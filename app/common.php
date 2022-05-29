<?php
function print_g() {
    $param = func_get_args();
    foreach ($param as $value) {
        echo '<pre>';
        if (is_bool($value)) {
            var_dump($value);
        } elseif (is_scalar($value)) {
            print_r($value);
        } else {
            if (is_array($value) and count($value) < 10000) {
                var_export($value);
            } else {
                print_r($value);
            }
        }
        echo "</pre>";
    }
    exit();
}

function fail($message = null, $data = null) {
    $message = $message ?: '执行失败';
    return ([
        'status' => 0,
        'msg' => $message,
        'data' => $data,]
    );
}

function success($data = null, $message = null) {
    $message = $message ?: '执行成功';
    return ([
        'status' => 1,
        'msg' => $message,
        'data' => $data,]
    );
}

function apphost() {
    if (isset($_SERVER['HTTPS'])) {
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    } else {
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'];
}

function rand_str($leng = 32, $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    $chars_length = strlen($chars) - 1;
    $_str = '';
    for ($i = $leng; $i > 0; $i--) {
        $_str .= $chars[mt_rand(0, $chars_length)];
    }
    return $_str;
}

function is_product() {
    return \think\facade\Config::get('app.app_debug') === true ?: false;
}

function checkSign() {
    $token = env('request.token');
    $timestamp = \think\facade\Request::get('timestamp');
    $noise = \think\facade\Request::get('noise');
    $sign = \think\facade\Request::get('sign');
    if (abs(time() - abs($timestamp)) > 100 and is_product()) {
        return fail('接口超时');
    }
    $resign = sha1($noise . $timestamp . $token);
    if ($resign !== $sign) {
        return fail('签名错误');
    }
    return true;
}

function getSign() {
    $token = env('request.token');
    $noise = rand_str(10);
    $timestamp = time();
    $sign = sha1($noise . $timestamp . $token);
    return compact('noise', 'timestamp', 'sign');
}

function appRequest($url, $data = [], $is_json = false, $header = array(), $timeout = 10) {
    Curlhelper::getInstance()->setopt([CURLOPT_PROXY => '127.0.0.1:8888']);
    if ($is_json and !is_scalar($data))
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
    if ($is_json && empty($header)) {
        $header = array(
            'Content-Type: application/json;charset=UTF-8',
            'Content-Length: ' . strlen($data)
        );
    }
    $url = apphost() . '/' . ltrim($url, '/') . '?' . http_build_query(getSign());
    if ($data) {
        $result = Curlhelper::getInstance()->post($url, $data, $header);
    } else {
        $result = Curlhelper::getInstance()->get($url, []);
    }
    return json_decode($result, true) ?: $result;
}

function excel_read($xls_file_path) {
    if (!file_exists($xls_file_path)) {
        throw new Exception('表格文件不存在');
    }
    $reader_type = (function () use ($xls_file_path) {
        $ext = pathinfo($xls_file_path, PATHINFO_EXTENSION);
        if (strtolower($ext) == 'xlsx') {
            return 'Excel2007';
        } else {
            return 'Excel5';
        }
    })();

    //require dirname($_SERVER['SCRIPT_FILENAME']) . '/../../phpexcel/third_party/PHPExcel.php';
    $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
    $cacheSettings = array('memoryCacheSize' => '16MB');
    \PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

    $xlsReader = \PHPExcel_IOFactory::createReader($reader_type);
    $xlsReader->setReadDataOnly(TRUE);
    $xlsReader->setLoadSheetsOnly(TRUE);
    $_Sheets = $xlsReader->load($xls_file_path);
    //$Sheets = $_Sheets->getSheetByName('Template');
    $Sheets = $_Sheets->getActiveSheet();
    $highestRow = $Sheets->getHighestRow(); // 取得总行数
    $highestColumm = (int)\PHPExcel_Cell::columnIndexFromString($Sheets->getHighestColumn()); //取得总列数 AMT
    //print_g(get_class_methods($Sheets),$highestColumm);
    //$excel_data = $Sheets->toArray();
    //print_g($excel_data);
    /** 循环读取每个单元格的数据 */
    $excel_data = array();
    for ($row = 1; $row <= $highestRow; $row++) {//行数是以第1行开始
        for ($column = 0; $column <= $highestColumm; $column++) {//列数是以A列开始
            //var_dump($column.$row,$Sheets->getCell($column.$row));die();
            $columnName = \PHPExcel_Cell::stringFromColumnIndex($column);
            if (!$Sheets->cellExists($columnName . $row)) {
                $excel_data[$row][] = null;
            } else {
                $excel_data[$row][] = $Sheets->getCellByColumnAndRow($column, $row)->getValue();
            }
        }
    }
    return $excel_data;
}


/**
 * @description excel数据导出方法封装 列数支持700+
 * @param array $title 列标题、宽度和数据字段 二维数组  必须 array(array('title'='name','width'=>30,'field'=>'user_name'),array('title'='sex','width'=>10,'field'=>'sex'))
 * @param array $data 二维数组数据
 * @param string $fileName 导出文件名
 * @param bool $bold 列标题是否加粗
 * @param bool $returnFile 是否保存为文件
 * useage:
 * $title = array(
 * array('title'=>'Certificate name','width'=>20,'field'=>'cert_name'),
 * array('title'=>'Name','width'=>30,'field'=>'user_name'),
 * array('title'=>'Company','width'=>30,'field'=>'user_company'),
 * );
 * excel_export($title,$data,'Certificate_list_'.date("Ymd", time()).'.xlsx');
 */
function excel_export($title = array(), $data = array(), $fileName = '', $bold = true, $returnFile = false) {
    if (empty($title)) {
        return false;
    }
    $objPHPExcel = new \PHPExcel();
    $count = count($title);

    for ($i = 0; $i < $count; $i++) {
        $i_name = PHPExcel_Cell::stringFromColumnIndex($i);//处理列大于26个的方法
        if (isset($title[$i]['width'])) {
            //设置列宽度
            $objPHPExcel->getActiveSheet()->getColumnDimension($i_name)->setWidth($title[$i]['width']);
        }
        //设置列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($i_name . '1', $title[$i]['title']);
        //列名是否加粗
        if ($bold) {
            $objPHPExcel->getActiveSheet()->getStyle($i_name . '1')->getFont()->setBold(true);
        }
        //数据处理
        if ($data) {
            foreach ($data as $key => $item) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($i_name . ($key + 2), $item[$title[$i]['field']]);
            }
        }
    }
    $objPHPExcel->getActiveSheet()->setTitle('Simple');
    $objPHPExcel->setActiveSheetIndex(0);
    $fileName = $fileName ? $fileName : date("Ymd", time()) . '.xlsx';
    if ($returnFile) {
        $dirPath = '/';//注意要设置为绝对路径
        $newFileName = date('YmdHis') . '_' . $fileName;
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($dirPath . '/' . $newFileName);
        return $newFileName;
    } else {
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Access-Control-Expose-Headers: Content-Disposition');
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
}




/**
 * Parses the PHPDoc comments for metadata. Inspired by Documentor code base
 * @category   Framework
 * @package    restler
 * @subpackage helper
 * @author     Murray Picton <info@murraypicton.com>
 * @author     R.Arul Kumaran <arul@luracast.com>
 * @copyright  2010 Luracast
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @link       https://github.com/murraypicton/Doqumentor
 */
class DocParser {
    private $params = array();

    function parse($doc = '') {
        $this->params = [];
        if ($doc == '') {
            return $this->params;
        }
        // Get the comment
        if (preg_match('#^/\*\*(.*)\*/#s', $doc, $comment) === false)
            return $this->params;
        $comment = trim($comment [1]);
        // Get all the lines and strip the * from the first character
        if (preg_match_all('#^\s*\*(.*)#m', $comment, $lines) === false)
            return $this->params;
        $this->parseLines($lines [1]);
        return $this->params;
    }

    private function parseLines($lines) {
        foreach ($lines as $line) {
            $parsedLine = $this->parseLine($line); // Parse the line

            if ($parsedLine === false && !isset ($this->params ['description'])) {
                if (isset ($desc)) {
                    // Store the first line in the short description
                    $this->params ['description'] = implode(PHP_EOL, $desc);
                }
                $desc = array();
            } elseif ($parsedLine !== false) {
                $desc [] = $parsedLine; // Store the line in the long description
            }
        }
        $desc = implode(' ', $desc);
        if (!empty ($desc))
            $this->params ['long_description'] = $desc;
    }

    private function parseLine($line) {
        // trim the whitespace from the line
        $line = trim($line);

        if (empty ($line))
            return false; // Empty line

        if (strpos($line, '@') === 0) {
            if (strpos($line, ' ') > 0) {
                // Get the parameter name
                $param = substr($line, 1, strpos($line, ' ') - 1);
                $value = substr($line, strlen($param) + 2); // Get the value
            } else {
                $param = substr($line, 1);
                $value = '';
            }
            // Parse the line and return false if the parameter is valid
            if ($this->setParam($param, $value))
                return false;
        }

        return $line;
    }

    private function setParam($param, $value) {
        if ($param == 'param' || $param == 'return')
            $value = $this->formatParamOrReturn($value);
        if ($param == 'class')
            list ($param, $value) = $this->formatClass($value);

        if (empty ($this->params [$param])) {
            $this->params [$param] = $value;
        } else if ($param == 'param') {
            $arr = array(
                $this->params [$param],
                $value
            );
            $this->params [$param] = $arr;
        } else {
            $this->params [$param] = $value . $this->params [$param];
        }
        return true;
    }

    private function formatClass($value) {
        $r = preg_split("[\(|\)]", $value);
        if (is_array($r)) {
            $param = $r [0];
            parse_str($r [1], $value);
            foreach ($value as $key => $val) {
                $val = explode(',', $val);
                if (count($val) > 1)
                    $value [$key] = $val;
            }
        } else {
            $param = 'Unknown';
        }
        return array(
            $param,
            $value
        );
    }

    private function formatParamOrReturn($string) {
        $pos = strpos($string, ' ');

        $type = substr($string, 0, $pos);
        return '(' . $type . ')' . substr($string, $pos + 1);
    }
}

function getAccess() {

    $ignore_class = [
        'Base',
        'Login',
        'Test',
        'UserBase',
        'Index'
    ];
    $handle = opendir(\think\facade\App::getAppPath() . 'controller');
    $class_list = [];
    while (false !== ($file = readdir($handle))) {
        $file_name = pathinfo($file, PATHINFO_FILENAME);
        if ($file == '.' or $file == '..' or in_array($file_name, $ignore_class)) {
            continue;
        }
        $_clss_name = 'app\admin\controller\\' . pathinfo($file, PATHINFO_FILENAME);
        if (!class_exists($_clss_name)) {
            continue;
        }
        $class_list[] = $_clss_name;
    }
    closedir($handle);
    $docParse = new \DocParser();
    $ignore_method = [
        '__construct',
    ];
    $list = [];
    foreach ($class_list as $class_name) {
        $refClassObj = new \ReflectionClass($class_name);
        $_res = $docParse->parse($refClassObj->getDocComment());
        $_res['access'] = $_res['access'] ?? '';
        if (empty($_res['access'])) {
            throw new \Exception($class_name . '控制器权限注释为空');
        }
        $_module = explode(DIRECTORY_SEPARATOR, trim(\think\facade\App::getAppPath(), DIRECTORY_SEPARATOR));
        $_module = end($_module);
        $list[] = [
            'access' => $_res['access'],
            'module' => $_module,
            'controller' => join('/', array_slice(explode('\\', $refClassObj->getName()), 3)),
            'description' => $_res['description'] ?? '',
        ];
        $method_list = $refClassObj->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($method_list as $refMethodObj) {
            if ($refMethodObj->getDeclaringClass()->getName() != $class_name) {
                continue;
            }
            if (in_array($refMethodObj->getName(), $ignore_method)) {
                continue;
            }
            $_res = $docParse->parse($refMethodObj->getDocComment());
            $_res['access'] = $_res['access'] ?? '';
            if (empty($_res['access'])) {
                throw new \Exception($class_name . '控制器下' . $refMethodObj->getName() . '方法权限注释为空');
            }
            $list[] = [
                'access' => $_res['access'],
                'module' => $_module,
                'controller' => join('/', array_slice(explode('\\', $refClassObj->getName()), 3)),
                'method' => $refMethodObj->getName(),
                'description' => $_res['description'] ?? '',
            ];

        }
    }

    $access = [];
    foreach ($list as $value) {
        $_keys = ['access_name', 'module', 'access_sn', 'level'];
        $_access = explode('|', $value['access']);
        if (count($_access) < 2) {
            throw new \Exception($value['access'] . '不符合(权限名称|权限层级)的权限规则');
        }
        //$_data = array_combine($_keys, $_access);
        //$_module = explode('/', $_data['module']);
        $_data = [];
        $_data['module'] = strtolower($value['module']);
        $_data['controller'] = strtolower($value['controller']);
        $_data['action'] = strtolower($value['method'] ?? '');
        $_data['access_name'] = array_shift($_access);
        $_data['level'] = array_pop($_access);
        $_data['access_sn'] = md5(strtolower($_data['module'] . $_data['controller'] . $_data['action']));
        $_data['p_access_sn'] = md5(strtolower($_data['module'] . $_data['controller']));
        $_data['href'] = "/{$_data['module']}/{$_data['controller']}/{$_data['action']}";
        $_data['parent'] = "/{$_data['module']}/{$_data['controller']}";
        $access[] = $_data;
    }
    return $access;
}

/**
 * 生成itemId
 * @return string
 */
function getItemId() {
    $hour = date('z') * 24 + date('H');
    $hour = str_repeat('0', 4 - strlen($hour)) . $hour;
    //	echo date('y') . $hour . PHP_EOL;
    return date('y') . $hour . getRandNumber(10);
}

/**
 * 生成固定长度的随机数
 * @param int $length
 * @return string
 */
function getRandNumber($length = 6) {
    $num = '';
    if ($length >= 10) {
        $t = intval($length / 9);
        $tail = $length % 9;
        for ($i = 1; $i <= $t; $i++) {
            $num .= substr(mt_rand(intval('1' . str_repeat('0', 9)), intval(str_repeat('9', 10))), 1);
        }
        $num .= substr(mt_rand('1' . str_repeat('0', $tail), str_repeat('9', $tail + 1)), 1);
        return $num;
    } else {
        return substr(mt_rand('1' . str_repeat('0', $length), str_repeat('9', $length + 1)), 1);
    }
}

function array_type(array $arr) {
    if (count(array_filter(array_keys($arr), 'is_string')) > 0) {
        return 'object';
    } else {
        return 'list';
    }
}

function is_list_array(array $arr) {
    return array_type($arr) == 'list' ? true : false;
}

function is_object_array(array $arr) {
    return array_type($arr) == 'object' ? true : false;
}

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @return string
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suff = true)
{
    if (function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif (function_exists('iconv_substr')) {
        $slice = iconv_substr($str, $start, $length, $charset);
    } else {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
    }
    if (mb_strlen($str, 'utf8') > $length && $suff) {
        return $slice . '...';
    } else {
        return $slice;
    }
}

/**
 * @param $image_url  图片链接
 * @param string $size 缩略尺寸
 * @param string $document_root 静态文件根目录
 * @return mixed|string
 */
function getImage($image_url, $size = '_150_150', $document_root = '')
{
    $default_url = '/static/default/no_image.jpg';
    if (empty($image_url)) {
        return $default_url;
    }
    if (empty($size)) {
        return $image_url;
    }
    $image_name = pathinfo($image_url, PATHINFO_FILENAME);
    $image_ext = pathinfo($image_url, PATHINFO_EXTENSION);
    $document_root = $document_root ? $document_root : $_SERVER['DOCUMENT_ROOT'];
    $thumb_name = mb_substr($image_name, 0, 2) . '/' . mb_substr($image_name, 2, 2) . '/' . $image_name . $size;
    $thumb_url = str_replace(['/upload/', 'image/', $image_name], ['/thumb/', '', $thumb_name,], $image_url);
    $real_thumb_file = $document_root . $thumb_url;
    if (is_file($real_thumb_file)) {
        return $thumb_url;
    }
    $real_image_file = $document_root . $image_url;
    if (!is_file($real_image_file)) {
        return $default_url;
    }
    if (!is_dir(pathinfo($real_thumb_file, PATHINFO_DIRNAME))) {
        mkdir(pathinfo($real_thumb_file, PATHINFO_DIRNAME), 0777, true);
    }
    $_size = explode('_', $size);
    \think\Image::open($real_image_file)
        ->thumb($_size[1]??150, $_size[2]??150)
        ->save($real_thumb_file, $image_ext, 30);
    if (is_file($real_thumb_file)) {
        return str_replace($document_root, '', $real_thumb_file);
    } else {
        return str_replace($document_root, '', $real_image_file);
    }
}


/**
 * PHP 非递归实现查询该目录下所有文件
 * @param unknown $dir
 * @return multitype:|multitype:string
 */
function readdir_normal($dir) {
    if (!is_dir($dir))
        return array();
    // 兼容各操作系统
    $dir = rtrim(str_replace('\\', '/', $dir), '/') . '/';
    // 栈，默认值为传入的目录
    $dirs = array($dir);
    // 放置所有文件的容器
    $rt = array();
    do {
        // 弹栈
        $dir = array_pop($dirs);
        // 扫描该目录
        $tmp = scandir($dir);
        foreach ($tmp as $f) {
            // 过滤. ..
            if ($f == '.' || $f == '..')
                continue;
            // 组合当前绝对路径
            $path = $dir . $f;
            // 如果是目录，压栈。
            if (is_dir($path)) {
                array_push($dirs, $path . '/');
            } else if (is_file($path)) { // 如果是文件，放入容器中
                $rt [] = $path;
            }
        }
    } while ($dirs); // 直到栈中没有目录
    return $rt;
}

/**
 * 递归循环获取指定目录下的所有文件
 * @param string $real_path
 * @return array
 */
if (!function_exists('readdir_recursion')) {
    function readdir_recursion(string $real_path) {
        $real_path = rtrim($real_path, DIRECTORY_SEPARATOR);
        static $file_list = [];
        $fh = opendir($real_path);
        while (false !== ($file_name = readdir($fh))) {
            if (in_array($file_name, ['.', '..'])) {
                continue;
            }
            $file_path = $real_path . DIRECTORY_SEPARATOR . $file_name;
            if (is_file($file_path)) {
                $file_list[] = $file_path;
            } elseif (is_dir($file_path)) {
                readdir_recursion($file_path);
            } else {
                continue;
            }
        }
        closedir($fh);
        return $file_list;
    }
}

function isImage($imgPath) {
    $file = fopen($imgPath, "rb");
    $bin = fread($file, 2); // 只读2字节

    fclose($file);
    // 标识前两个字符按照 c格式 数组索引chars1、chars2
    $strInfo = unpack("C2chars", $bin);
    $typeCode = intval($strInfo['chars1'] . $strInfo['chars2']);
    $fileType = '';

    if ($typeCode == 255216 /*jpg*/ || $typeCode == 7173 /*gif*/ || $typeCode == 13780 /*png*/) {
        return $typeCode;
    } else {
        return false;
    }
}

/**
 * ThinkPHP 获取当前页面完整的url
 * @return string
 */
function getCurrentUrl() {
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
    return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}

function delete_dir($dir) {
    //先删除目录下的文件：
    $dh = opendir($dir);
    while ($file = readdir($dh)) {
        if ($file != "." && $file != "..") {
            $fullpath = $dir . "/" . $file;
            if (!is_dir($fullpath)) {
                $res = unlink($fullpath);
            } else {
                delete_dir($fullpath);
            }
        }
    }
    closedir($dh);
    if (rmdir($dir)) {
        return true;
    } else {
        return false;
    }

}











