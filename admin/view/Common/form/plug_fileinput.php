    <!--上传插件 开始-->
    <link rel="stylesheet" href="/static/admin/js/bootstrap-fileinput/css/fileinput.css">
    <script src="/static/admin/js/bootstrap-fileinput/js/fileinput.js"></script>
    <script src="/static/admin/js/bootstrap-fileinput/themes/fa/theme.js"></script>
    <script src="/static/admin/js/bootstrap-fileinput/js/locales/zh.js"></script>
    <script>
        function fileInput(uploadId) {
//            var inputName = $('#' + uploadId).parent().find('input[type=hidden]').attr('name')
            var inputName = $('#' + uploadId).attr('name')
            var fileInput =  $('#' + uploadId).siblings('input[type=file]');
//            console.log(fileInput);
//            console.log(inputName);
            /**上传组件 配置 开始**/
            var config = {};
            config.language = 'zh';                                             //语言
            config.uploadUrl = '<?=U('admin/Upload/index')?>';                  //服务端上传链接
            config.enctype = 'multipart/form-data';                             //表单设置
            config.showPreview = true;                                          //显示预览
            config.showRemove = false;                                           //显示移除按钮
            config.showCaption = false;                                         //显示标题
            config.allowedFileExtensions = ['jpg', 'jpeg', 'png', 'gif'];       //允许上传文件类型
            config.uploadAsync = true;                                          //异步上传
            config.dropZoneEnabled = false;                                     //允许拖拽
            config.maxFileCount = 1;                                            //允许上传数量
            config.autoReplace = false;                                         //自动替换 当达到maxFileCount最大值时
            config.overwriteInitial = true;                                     //覆写初始文件
            config.initialPreviewAsData = true;                                 //true:允许预览只提供数据,false:预览需要包含完整的html img标签
            config.initialPreview = _getPreview();                              //文件预览
            config.initialPreviewConfig = _getPreviewConfig()
            config.initialPreviewShowDelete = true;
            config.deleteUrl   ='<?=U('admin/Upload/deleteFile')?>';             //服务端删除操作链接
            /**上传组件 配置 结束**/

            /***上传组件 初始化**/
            var uploadObj = fileInput.fileinput(config);

            /***上传组件 事件处理 开始**/
            //上传成功处理
            uploadObj.on('fileuploaded', function (event, data, previewId, index) {
                var result = data.response;
                layer.alert(result.msg);
                if (result.status == 1) {
                    $('[name=' + inputName + ']').val(result.data.name);
                }
            });
            //删除前确认
            uploadObj.on("filepredelete", function (jqXHR) {
                var abort = true;
                if (confirm("确定要删除该图片吗?")) {
                    abort = false;
                }
                return abort; // you can also send any data/object that you can receive on `filecustomerror` event
            });
            //删除文件 处理
            uploadObj.on('filedeleted', function (event, data,key){
                $('[name=' + inputName + ']').val('');
                layer.alert('删除成功');
            });
            /***上传组件 事件处理 结束**/

            /**上传组件 私有方法 开始**/
            //获取预览图片
            function _getPreview() {
                var imageUrl = [];
                var preview = $('[type=file]').data('preview')
                if (preview) {
                    imageUrl.push(preview)
                }
                return imageUrl;
            }
            //获取预览图片子配置(删除图片使用)
            function _getPreviewConfig() {
                var imageName = [];
                var preview = $('[type=file]').data('preview')
                if (preview) {
                    preview = preview.split('/');
                    var sub = new Object();
                    sub.key = preview.pop();
                    imageName.push(sub)
                }
                return imageName;
            }
            /**上传组件 私有方法 结束**/
        }
    </script>
    <script>
        //插件实例化
        $(function () {
            var field = <?=$field?>;
            $.each(field, function (i, n) {
                fileInput(n);
            })
        })
    </script>
    <!--上传插件 结束-->