
<!--plupload上传插件 开始-->
<script type="text/javascript" src="/static/admin/js/plupload-master/js/plupload.full.min.js"></script>
<style>
    .upload-box ul{overflow:hidden;_zoom:1;padding-left:0px; }
    .upload-box ul li{width: 150px;height: 150px;background: #EFEFEF;float:  left;overflow:hidden;border: 4px dashed #ddd;margin-right: 10px; position: relative;margin-bottom: 10px;}
    .upload-box ul li .add{font-size: 80px; color: #CCCCCC;width: 100%;text-align: center;line-height: 150px;}
    .upload-box ul li .remove{position: absolute;width: 14px;height: 14px;line-height:14px;text-align:center;background: #E9523F;color:#fff;overflow:hidden;border-radius:5px;right: 0px;top: 17px;}
    .upload-box ul li .preview{height: 80px;overflow: hidden;}
    .upload-box ul li .preview img{max-width: 150px;}
    .upload-box ul li .progress{margin-top: 5px;width: 0px;}
</style>
<script>
    function init_upload(input_name_arr) {
        var max_file_upload = 1;
        $.each(input_name_arr, function (i, n) {
            var browse_id = n;
            var config = {
                browse_button: browse_id, // this can be an id of a DOM element or the DOM element itself
                url: '<?=U('admin/Upload/index')?>',
                multipart: true,//为true时将以multipart/form-data的形式来上传文件，为false时则以二进制的格式来上传文件。
                multipart_params: {source: 'upload'},//上传时的附加参数，
                max_retries: 0, //	当发生plupload.HTTP_ERROR错误时的重试次数，为0时表示不重试
                chunk_size: 0,   //	分片上传文件时，每片文件被切割成的大小，为数字时单位为字节。也可以使用一个带单位的字符串，如"200kb"。当该值为0时表示不使用分片上传功能
                multi_selection: false, //	是否可以在文件浏览对话框中选择多个文件，true为可以，false为不可以。默认true，
                unique_names: false,//当值为true时会为每个上传的文件生成一个唯一的文件名，
                file_data_name: 'file',//指定文件上传时文件域的名称，默认为file,例如在php中你可以使用$_FILES['file']来获取上传的文件信息
                /*不需要用到的属性 开始*/
                /*flash_swf_url: '/static/admin/js/plupload-master/js/Moxie.swf',//flash上传组件的url地址
                 silverlight_xap_url: '/static/admin/js/plupload-master/js/Moxie.xap'//silverlight上传组件的url地址
                 filters: {
                 prevent_duplicates: true, //不允许选取重复文件
                 mime_types: [ //只允许上传图片和zip文件
                 {title: "Image files", extensions: "jpg,gif,png,jpeg,bmp"},
                 {title: "Zip files", extensions: "zip"}
                 ],
                 max_file_size: '5000kb' //最大只能上传400kb的文件
                 },
                 resize: {},
                 drop_element: '',
                 required_features: '',
                 container: '',//用来指定Plupload所创建的html结构的父容器，默认为前面指定的browse_button的父元素。*/
                /*不需要用到的属性 结束*/

                /*事件处理开始*/
                init: {
                    //当Init事件发生后触发
                    PostInit: function (uploader) {
                        //指定id
                        $('#' + browse_id).parents('.upload-box').attr('id', uploader.id);
                        //点击上传
//                        $(document).on('click', '#' + uploader.id + ' .upload-btn', function () {
//                            uploader.start();
//                        })
                        //点击删除
                        $(document).on('click', '.upload-box li .remove', function () {
                            var obj = $(this).parent();
                            var upload_id = obj.parents('.upload-box').attr('id');
                            var id = obj.attr('id');
                            var file_name = obj.find('input[type=hidden]').val();
                            if (id && id.length > 0 && uploader.id == upload_id) {
                                var file = uploader.getFile(id)
                                uploader.removeFile(file)
                            }
                            if (file_name) {
                                var param = {
                                    key: file_name
                                };
                                var url   ='<?=U('admin/Upload/deleteFile')?>';
                                $.post(url, param, function (data) {
                                }, 'json');
                            }
                            obj.remove();
                            check_show_select_btn(max_file_upload, browse_id);
//                            check_show_upload_btn(browse_id);
                        })
                    },
                    //选择文件后触发
                    FilesAdded: function (uploader, files) {
                        show_file_select_preview(browse_id, files[0])
                        check_show_select_btn(max_file_upload, browse_id);
//                        check_show_upload_btn(browse_id);
                        uploader.start();
                    },
                    //当文件从上传队列移除后触发
                    FilesRemoved: function (uploader, files) {

                    },
                    //当队列中的某一个文件上传完成后触发
                    FileUploaded: function (uploader, file, responseObject) {
                        var res = responseObject.response;
                        res = eval('(' + res + ')');
                        if (res.status == 1) {
                            var name = max_file_upload == 1 ? browse_id : browse_id + '[]';
                            var html = '<input type="hidden" name="' + name + '" value="' + res.data.name + '"/>';
                            $('#' + file.id).prepend(html);
                        } else {
                            layer.alert('上传失败')
                        }
                    },
                    //当上传队列中所有文件都上传完成后触发
                    UploadComplete: function (uploader, files) {
                        //console.log(uploader,files);
                    },
                    //绑定文件上传进度事件
                    UploadProgress: function (uploader, file) {
                        $('#' + file.id + ' .progress').css('width', file.percent + '%');//控制进度条
                    },

                    //当发生错误时触发
                    Error: function (uploader, errObject) {
                        if (errObject.status == 500) {
                            $('#' + errObject.file.id).find('.progress').removeClass('progress-bar-success').addClass('progress-bar-danger')
                            layer.msg('上传失败')
                        }
                    }

                }
                /*事件处理结束*/

            };
            //上传组件初始化
            build_upload_box(browse_id);
            var uploader = new plupload.Uploader(config);
            uploader.init();
        })
        function show_file_select_preview(browse_id, file) {
            var add_obj = $('#' + browse_id).parent();
            var id = file.id;
            var html = '<li id="' + id + '" class="instance">';
            html += '<div class="preview"></div>'
            html += '<div class="progress progress-xs progress-bar progress-bar-success progress-bar-striped"></div>'
            html += '<div class="remove">x</div>'
            html += '</li>';
            add_obj.before(html);
            preview_image(file, function (image_source) {
                $('#' + id).find('.preview').html('<image src="' + image_source + '"/>')
            })
        }

        //plupload中为我们提供了mOxie对象
        //有关mOxie的介绍和说明请看：https://github.com/moxiecode/moxie/wiki/API
        //如果你不想了解那么多的话，那就照抄本示例的代码来得到预览的图片吧
        function preview_image(file, callback) {//file为plupload事件监听函数参数中的file对象,callback为预览图片准备完成的回调函数
            if (!file || !/image\//.test(file.type)) {
                callback(file);
            } else {
                if (file.type == 'image/gif') {//gif使用FileReader进行预览,因为mOxie.Image只支持jpg和png
                    var fr = new mOxie.FileReader();
                    fr.onload = function () {
                        callback(fr.result);
                        fr.destroy();
                        fr = null;
                    }
                    fr.readAsDataURL(file.getSource());
                } else {
                    var preloader = new mOxie.Image();
                    preloader.onload = function () {
                        preloader.downsize(300, 300);//先压缩一下要预览的图片,宽300，高300
                        var imgsrc = preloader.type == 'image/jpeg' ? preloader.getAsDataURL('image/jpeg', 80) : preloader.getAsDataURL(); //得到图片src,实质为一个base64编码的数据
                        callback && callback(imgsrc); //callback传入的参数为预览图片的url
                        preloader.destroy();
                        preloader = null;
                    };
                    preloader.load(file.getSource());
                }
            }
        }

        function check_show_select_btn(max_file_upload, browse_id) {
            if ($('#' + browse_id).parents('.upload-box ul').find('li.instance').length >= max_file_upload) {
                $('#' + browse_id).parent().hide(0);
            } else {
                $('#' + browse_id).parent().show(0);
            }
        }

        function check_show_upload_btn(browse_id) {
            var obj = $('#' + browse_id).parents('.upload-box');
            if (obj.find('ul li.instance').length >= 1) {
                if (!obj.find('.upload-btn').length) {
                    var html = '<a class="btn btn-success upload-btn" >开始上传</a>';
                    obj.append(html)
                }
            } else {
                obj.find('.upload-btn').remove();
            }
        }

        function build_upload_box(browse_id) {
            var obj = $('input[name=' + browse_id + ']');
            var html = '<div class="upload-box"><ul></ul></div>';
            obj.parent().append(html);
            obj.parent().find('.upload-box ul').append('<li><div class="add" id="' + browse_id + '">+</div></li>');
            if (!obj.val()) {//没有初始化数据
                obj.remove();
            } else {//有初始化数据
//                $('.upload-box ul li.instance').last().after('<li><div class="add" id="' + browse_id + '">+</div></li>');
                obj.parent().find('.upload-box ul').prepend(obj)
                obj.wrap('<li class="instance"></li>');
                obj.parent('.instance').append('<div class="preview"></div><div class="remove">x</div>');
                $.each($('.upload-box ul li.instance'), function (i, n) {
                    var file_url = '/upload/'+$(this).find('input[type=hidden]').val();
                    preview_image(file_url, function (url) {
                        $('.upload-box ul li.instance').eq(i).find('.preview').html('<image src="' + url + '"/>')
                    })
                })
            }
            check_show_select_btn(max_file_upload, browse_id);
        }
    }
</script>
<script>
    //插件实例化
    $(function () {
        var field = <?=$field?>;
        init_upload(field)
    })
</script>
<!--plupload上传插件结束-->
