<?php
	require('../../global.php');
	require('inc/setting.inc.php');
	
	//验证是否登入
	if(!checkLogin()){
		header('Location: ../error.php?code='.$errorcode['noLogin']);
	}
	if(file_exists('dofiles/member/'.$_SESSION['member']['id'].'/avatar/120.jpg')){
		$avatar = '../../dofiles/member/'.$_SESSION['member']['id'].'/avatar/120.jpg';
	}else{
		$avatar = '../../img/ui/avatar_120.jpg';
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>设置头像</title>
<?php include('sysapp/global_css.php'); ?>
<link rel="stylesheet" href="../../img/ui/sys.css">
<script> 
function avatar_success(){
	window.parent.HROS.navbar.getAvatar();
	alert('头像保存成功');
	location.reload();
}
</script>
</head>

<body>
<div style="width:530px;margin:0 auto">
	<embed src="../../libs/avatar_face/face.swf" quality="high" wmode="opaque" FlashVars="defaultImg=<?php echo $avatar; ?>?id=<?php echo getRandStr(10); ?>" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="530" height="480"></embed>
</div>
<?php include('sysapp/global_js.php'); ?>
</body>
</html>