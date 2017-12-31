<?php $this->layout('Layout/admin'); ?>

<!-- bootstrap 4.x is supported. You can also use the bootstrap css 3.3.x versions -->
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">-->
<link href="/static/admin/js/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet"
      type="text/css"/>
<!-- if using RTL (Right-To-Left) orientation, load the RTL CSS file after fileinput.css by uncommenting below -->
<!-- link href="/static/admin/js/bootstrap-fileinput-master/css/fileinput-rtl.min.css" media="all" rel="stylesheet" type="text/css" /-->
<!-- optionally uncomment line below if using a theme or icon set like font awesome (note that default icons used are glyphicons and `fa` theme can override it) -->
<!-- link https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css media="all" rel="stylesheet" type="text/css" /-->
<!--<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>-->
<!-- piexif.min.js is only needed for restoring exif data in resized images and when you
    wish to resize images before upload. This must be loaded before fileinput.min.js -->
<script src="/static/admin/js/bootstrap-fileinput-master/js/plugins/piexif.min.js" type="text/javascript"></script>
<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview.
    This must be loaded before fileinput.min.js -->
<script src="/static/admin/js/bootstrap-fileinput-master/js/plugins/sortable.min.js" type="text/javascript"></script>
<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for
    HTML files. This must be loaded before fileinput.min.js -->
<script src="/static/admin/js/bootstrap-fileinput-master/js/plugins/purify.min.js" type="text/javascript"></script>
<!-- popper.min.js below is needed if you use bootstrap 4.x. You can also use the bootstrap js
   3.3.x versions without popper.min.js. -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>-->
<!-- bootstrap.min.js below is needed if you wish to zoom and preview file content in a detail modal
    dialog. bootstrap 4.x is supported. You can also use the bootstrap js 3.3.x versions. -->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" type="text/javascript"></script>-->
<!-- the main fileinput plugin file -->
<script src="/static/admin/js/bootstrap-fileinput-master/js/fileinput.min.js"></script>
<!-- optionally uncomment line below for loading your theme assets for a theme like Font Awesome (`fa`) -->
<!-- script src="/static/admin/js/bootstrap-fileinput-master/themes/fa/theme.min.js"></script -->
<!-- optionally if you need translation for your language then include  locale file as mentioned below -->
<script src="/static/admin/js/bootstrap-fileinput-master/js/locales/zh.js"></script>
<script src="/static/admin/js/bootstrap-fileinput-master/themes/fa/theme.js"></script>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-12">
            <form action="" name="autoform" id="autoform" method="post" class="form form-horizontal"><input
                    type="hidden" name="id" id="id"/>

                <div class="form-group">
                    <label class="control-label col-sm-2"> 上传广告图</label>

                    <div class="col-sm-8">
                        <input type="hidden" name="ad_image" id="ad_image"/>
                        <input id="upload_file" type="file"  class="file" data-preview-file-type="text" data-preview=""/>
                    </div>
                    <label class="col-sm-2"> </label>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">操作</label>

                    <div class="col-sm-10">
                        <button class="btn btn-success " data-power="admin/System/addConfig">保存</button>
                        <button type="reset" class="btn btn-danger ml10">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var config = {
        showUpload: false,
        previewFileType: 'any',
        //语言
        language: 'zh',
        //异步上传(默认true)
        uploadAsync: true,
        //多文件上传，控制最大文件选择数量
        maxFileCount: 10,
        //ajax上传，服务器端url
        uploadUrl: '<?=U('admin/Upload/index')?>',
        ////服务端删除操作链接
        deleteUrl: '<?=U('admin/Upload/deleteFile')?>',
        //boolean whether to enable a drag and drop zone for dragging and dropping files to
        dropZoneEnabled: false,
        previewSettings:{
            image: {width: "auto", height: "auto", 'max-width': "250px", 'max-height': "100%"},
            html: {width: "213px", height: "160px"},
            text: {width: "213px", height: "160px"},
            office: {width: "213px", height: "160px"},
            video: {width: "213px", height: "160px"},
            audio: {width: "100%", height: "30px"},
            flash: {width: "213px", height: "160px"},
            object: {width: "213px", height: "160px"},
            pdf: {width: "213px", height: "160px"},
            other: {width: "213px", height: "160px"}
        },
        theme:'fa',
        //boolean, whether file selection is mandatory before upload (for ajax) or submit of the form (for non-ajax).
        required: false,
        //boolean, whether to orient the widget in Right-To-Left (RTL) mode
        rtl: false,
        //boolean, whether to hide the preview content (image, pdf content, text content, etc.) within the thumbnail.
        hiddenThumbnailContent: false,
        //boolean, whether to display the file caption. Defaults to true.
        showCaption: false,
        //boolean, whether to display the file preview. Defaults to true.
        showPreview: true,
        //boolean, whether to display the file remove/clear button. Defaults to true.
        showRemove: false,
        //boolean, whether to display the file upload button. Defaults to true.
        showUpload: false,
        //boolean, whether to display the file upload cancel button. Defaults to true.
        showCancel: false,
        //boolean, whether to display the close icon in the preview. Defaults to true.
        showClose: true,
        //boolean, whether to persist display of the uploaded file thumbnails in the preview window (for ajax uploads) until the remove/clear button is pressed.
        showUploadedThumbs: true,
        //boolean, whether to display the file browse button. Defaults to true.
        showBrowse: true,
        //boolean, whether to enable file browse/select on clicking of the preview zone. Defaults to false.
        browseOnZoneClick: false,
        //boolean, whether to automatically replace the files in the preview after the maxFileCount limit is reached and a new set of file(s) is/are selected. This will only work if a valid maxFileCount is set. Defaults to false.
        autoReplace: false,
        //boolean, whether to automatically orient the image for display based on EXIF orientation tag (i.e. rotate or mirror flip horizontally/vertically).
        autoOrientImage: true,
        //string, any additional CSS class to append to the caption container.
        captionClass: '',
        //string, any additional CSS class to append to the preview container.
        previewClass: '',
        // string, any additional CSS class to append to the main plugin container that will render the caption and the browse, remove, and upload buttons. Defaults to file-caption-main.
        mainClass: '',
        //string, the CSS class to be additionally applied to each file thumbnail frame. Defaults to krajee-default.
        frameClass: '',
        //boolean, whether to purify HTML content displayed for HTML content type in preview. Defaults to true.
        purifyHtml: true,
        //boolean, the callback to generate the human friendly filesize based on the bytes
        fileSizeGetter:function (bytes) {
            var i = Math.floor(Math.log(bytes) / Math.log(1024)),
                sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
            return (bytes / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + sizes[i];
        },
        //array the list of allowed file types for upload.
        allowedFileTypes:['image', 'html', 'text', 'video', 'audio', 'flash', 'object'],
        //array the list of allowed file extensions for upload
        allowedFileExtensions:['jpg', 'gif', 'png', 'txt'],
        //array the list of allowed preview types for your widget.
        allowedPreviewTypes:['image', 'html', 'text', 'video', 'audio', 'flash', 'object'],
        //object | function, the extra data that will be passed as data to the url/AJAX server call via POST
        uploadExtraData:{upload_by:'fileinput'}
    };
    $('#upload_file').fileinput(config);
</script>