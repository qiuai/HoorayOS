<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
</head>

<body>
<div id="flashContainer"></div>
<button id="upload" style="display:none">up</button>
<script src="../../js/jquery-1.8.2.min.js"></script>
<script src="tangram.js"></script>
<script type="text/javascript">
	/*-=-=-=-=-=-=-=全局变量模块-=-=-=-=-=-=-*/
	var imageUrls = [],        //从服务器返回的上传成功图片数组
		imageCount = 0;        //预览框中的图片数量，初始为0
	//flash初始化参数
	var flashOptions ={
		createOptions:{
			id:'flashContainer',
			url:'imageUploader.swf',
			width:'720',//容器宽度
			height:'272',//容器高度
			errorMessage:'Flash插件初始化错误，请更新您的flashplayer版本！',
			wmode:'window',
			ver:'10.0.0',
			// 初始化的参数就是这些，
			vars:{
				width:580,                                                                           // width是flash的宽
				height:270,                                                                          // height是flash的高
				gridWidth:115,                                                                       // gridWidth是每一个预览图片所占的宽度，应该为width的整除
				gridHeight:120,                                                                      // gridHeight是每一个预览图片所占的高度，应该为height的整除
				picWidth:100,                                                                        // 单张预览图片的宽度
				picHeight:100,                                                                       // 单张预览图片的高度
				uploadDataFieldName:'picdata',                                                       // POST请求中图片数据的key
				picDescFieldName:'pictitle',                                                         // POST请求中图片描述的key
				maxSize:2,                                                                           // 文件的最大体积,单位M
				compressSize:1,                                                                      // 上传前如果图片体积超过该值，会先压缩,单位M
				maxNum:20,                                                                           // 最大上传多少个文件
				backgroundUrl:'',                                                                    // 背景图片,留空默认
				listBackgroundUrl:'',                                                                // 预览图背景，留空默认
				buttonUrl:'',                                                                        // 按钮背景，留空默认
				compressSide:0,                                                                      // 等比压缩的基准，0为按照最长边，1为按照宽度，2为按照高度
				compressLength:700,                                                                  // 能接受的最大边长，超过该值Flash会自动等比压缩
				url:'demo.upload.php',                                                               // 上传处理页面的url地址
				ext:'{"param1":"参数值1", "param2":"参数值2"}',                                       // 可向服务器提交的自定义参数列表
				fileType:'{"description":"图片", "extension":"*.gif;*.jpeg;*.png;*.jpg;*.bmp"}'      // 上传文件格式限制
			},
			container: 'flashContainer'//flash容器id
		},
		// 选择文件的回调
		selectFileCallback: function(selectFiles){
			imageCount += selectFiles.length;
			if(imageCount){
				$('#upload').show();
			}
		},
		// 删除文件的回调
		deleteFileCallback: function(delFiles){
			imageCount -= delFiles.length;
			if(!imageCount){
				$('#upload').hide();
			}
		},
		// 文件超出限制的最大体积时的回调
		// exceedFileCallback: 'exceedFileCallback',
		// 开始上传某个文件时的回调
		// startUploadCallback: 'startUploadCallback',
		// 某个文件上传完成的回调
		uploadCompleteCallback: function(data){
			try{
				var info = eval("(" + data.info + ")");
				info && imageUrls.push(info);
				imageCount--;
			}catch(e){}
		},
		// 某个文件上传失败的回调
		uploadErrorCallback: function(data){
			if(!data.info){
				alert(lang.netError);
			}
		},
		// 全部上传完成时的回调
		allCompleteCallback: function(){
			console.log(imageUrls);
		}
		// 改变Flash的高度，mode==1的时候才有用
		// changeFlashHeight: 'changeFlashHeight'
		  
	}
	var flashObj = new baidu.flash.imageUploader(flashOptions);	
	
	$(function(){
		$('#upload').click(function(){
			flashObj.upload();
			$(this).hide();
		});
	});	
</script>
</body>
</html>