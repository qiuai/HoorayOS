<?php
	require('../../global.php');
	require('inc/setting.inc.php');
	
	//验证是否登入
	if(!checkLogin()){
		header('Location: ../error.php?code='.$errorcode['noLogin']);
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>我的日历</title>
<?php include('sysapp/global_css.php'); ?>
<link rel="stylesheet" href="../../img/ui/sys.css">
<link rel="stylesheet" href="../../js/fullcalendar-1.5.4/fullcalendar/fullcalendar.css">
</head>

<body>
<div id="editbox" style="display:none">
	<form action="index.ajax.php" method="post" name="form" id="form">
	<input type="hidden" name="ac" value="edit">
	<input type="hidden" name="id">
	<div class="creatbox">
		<div class="middle">
			<p class="detile-title">编辑日程</p>
			<div class="input-label">
				<label class="label-text">日程标题：</label>
				<div class="label-box form-inline">
					<input type="text" class="text" name="val_title" style="width:335px">
				</div>
			</div>
			<div class="input-label">
				<label class="label-text">日期：</label>
				<div class="label-box form-inline">
					<input type="text" class="text" name="val_startdt" style="width:150px"><span class="help-inline" style="padding-right:5px">到</span><input type="text" class="text" name="val_enddt" style="width:150px">
				</div>
			</div>
			<div class="input-label">
				<label class="label-text">全天活动：</label>
				<div class="label-box form-inline">
					<label class="radio"><input type="radio" name="val_isallday" value="1">开启</label>
					<label class="radio" style="margin-left:10px"><input type="radio" name="val_isallday" value="0">关闭</label>
				</div>
			</div>
			<div class="input-label">
				<label class="label-text">链接：</label>
				<div class="label-box form-inline">
					<input type="text" class="text" name="val_url" style="width:335px">
				</div>
			</div>
			<div class="input-label">
				<label class="label-text">内容：</label>
				<div class="label-box form-inline">
					<textarea style="width:335px" rows="5" name="val_content"></textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="bottom-bar">
		<div class="con">
			<a class="btn btn-large btn-primary fr" menu="submit" href="javascript:;"><i class="icon-white icon-ok"></i> 确定</a>
			<a class="btn btn-large" menu="back" href="javascript:;"><i class="icon-arrow-left"></i> 返回</a>
		</div>
	</div>
	</form>
</div>
<div id="calendar" style="margin:30px"></div>
<?php include('sysapp/global_js.php'); ?>
<script src="../../js/fullcalendar-1.5.4/fullcalendar/fullcalendar.min.js"></script>
<script src="../../js/fullcalendar-1.5.4/jquery/jquery-ui-1.8.23.custom.min.js"></script>
<script src="../../js/sugar-1.3.5/sugar.min.js"></script>
<script src="../../js/My97DatePicker/WdatePicker.js"></script>
<script>
$(function(){
	$('input[name="val_startdt"], input[name="val_enddt"]').click(function(){
		WdatePicker({dateFmt:'yyyy-M-d H:m:s'});
	});
	//初始化ajaxForm
	var options = {
		beforeSubmit : showRequest,
		success : showResponse,
		type : 'POST'
	};
	$('#form').ajaxForm(options);
	//提交
	$('#editbox a[menu=submit]').click(function(){
		$('#form').submit();
	});
	$('#editbox a[menu="back"]').click(function(){
		$('#calendar').show();
		$('#editbox').hide();
	});
	var calendar = $('#calendar').fullCalendar({
		firstDay: 1,
		monthNames: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
		monthNamesShort: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
		dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
		dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],
		buttonText: {
			prev: '&nbsp;&#9668;&nbsp;',
			next: '&nbsp;&#9658;&nbsp;',
			prevYear: '&nbsp;&lt;&lt;&nbsp;',
			nextYear: '&nbsp;&gt;&gt;&nbsp;',
			today: '&nbsp;今天&nbsp;',
			month: '&nbsp;月&nbsp;',
			week: '&nbsp;周&nbsp;',
			day: '&nbsp;日&nbsp;'
		},
		titleFormat: {
			month: 'yyyy年MMMM',
			week: "yyyy年 MMMd日[ yyyy]{'-'[ MMM]d'日'}",
			day: 'yyyy年MMMd日 dddd'
		},
		columnFormat: {
			month: 'ddd',
			week: 'M月d日 ddd',
			day: 'M月d日 dddd'
		},
		timeFormat: {
			'': 'H:mm - '
		},
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,basicWeek,basicDay'
		},
		selectable: true,
		selectHelper: true,
		select: function(start, end, allDay){
			//如果对话框存在，则先关闭
			if($.dialog.list['selectDialog'] != null){
				$.dialog.list['selectDialog'].close();
			}
			//创建对话框
			$.dialog({
				id: 'selectDialog',
				lock: false,
				title: '创建日程',
				content: '<table><tr><td style="width:50px;height:30px;vertical-align:middle">时间：</td><td style="vertical-align:middle">' + String(start.getMonth() + 1) + '月' + String(start.getDate()) + '日 ' + getMyDay(start.getDay()) + ((end.getMonth() == start.getMonth() && end.getDate() == start.getDate()) ? '' : '&nbsp;&nbsp;–&nbsp;&nbsp;' + String(end.getMonth() + 1) + '月' + String(end.getDate()) + '日 ' + getMyDay(end.getDay())) + '</td></tr><tr><td style="height:30px;vertical-align:middle">标题：</td><td style="vertical-align:middle"><input type="text" id="title" style="margin-bottom:0"></td></tr><tr><td></td><td style="height:30px;vertical-align:middle">例如：下午 4 点在 星巴克 喝下午茶</td></tr></table>',
				button: [
					{
						name: '创建',
						callback: function(){
							$.ajax({
								type: 'POST',
								url: 'index.ajax.php',
								data: 'ac=quick&do=add&title=' + document.getElementById('title').value + '&start=' + Date.create(start).format('{yyyy}-{MM}-{dd} {H}:{m}:{s}') + '&end=' + Date.create(end).format('{yyyy}-{MM}-{dd} {H}:{m}:{s}'),
								success: function(){
									//添加成功后刷新日历
									calendar.fullCalendar('refetchEvents');
								}
							});
						},
						focus: true
					},
					{
						name: '编辑',
						callback: function(){
							$('#calendar').hide();
							$('#editbox').show();
							//清空表单
							clearEditForm();
							//初始化表单
							$('#editbox input[name="val_title"]').val(document.getElementById('title').value);
							$('#editbox input[name="val_startdt"]').val(Date.create(start).format('{yyyy}-{MM}-{dd} {H}:{m}:{s}'));
							$('#editbox input[name="val_enddt"]').val(Date.create(end).format('{yyyy}-{MM}-{dd} {H}:{m}:{s}'));
						}
					}
				]
			});
			calendar.fullCalendar('unselect');
		},
		editable: true,
		events: 'index.ajax.php?ac=getCalendar',
		eventClick: function(event){
			var start = new Date(event._start), end = new Date(event._end), startText = '', endText = '';
			startText += String(start.getMonth() + 1) + '月' + String(start.getDate()) + '日 ';
			startText += getMyDay(start.getDay()) + ' ';
			if(!event.allDay){
				startText += getMyHours(start.getHours());
				startText += String(start.getHours()) + ':' + String(start.getMinutes() < 10 ? '0' + start.getMinutes() : start.getMinutes());
			}
			endText += String(end.getMonth() + 1) + '月' + String(end.getDate()) + '日 ';
			endText += getMyDay(end.getDay()) + ' ';
			if(!event.allDay){
				endText += getMyHours(end.getHours());
				endText += String(end.getHours()) + ':' + String(end.getMinutes() < 10 ? '0' + end.getMinutes() : end.getMinutes());
			}
			$.dialog({
				title: event.title,
				content: startText + '&nbsp;&nbsp;–&nbsp;&nbsp;' + endText,
				width: 350,
				button: [
					{
						name: '跳转',
						callback: function(){
							window.open(event.url, '_blank');
						},
						disabled: typeof event.url == 'undefined' ? true : false
					},
					{
						name: '删除',
						callback: function(){
							ZENG.msgbox.show('正在更新中，请稍后...', 6, 100000);
							$.ajax({
								type: 'POST',
								url: 'index.ajax.php',
								data: 'ac=quick&do=del&id=' + event._id,
								success: function(){
									ZENG.msgbox._hide();
									calendar.fullCalendar('removeEvents', event._id);
								}
							});
						}
					},
					{
						name: '编辑',
						callback: function(){
							ZENG.msgbox.show('正在读取中，请勿操作', 6, 100000);
							$.ajax({
								type: 'POST',
								url: 'index.ajax.php',
								data: 'ac=getDate&id=' + event._id,
								dataType: 'json',
								success: function(msg){
									ZENG.msgbox._hide();
									$('#calendar').hide();
									$('#editbox').show();
									//清空表单
									clearEditForm();
									//初始化表单
									$('#editbox input[name="id"]').val(msg['tbid']);
									$('#editbox input[name="val_title"]').val(msg['title']);
									$('#editbox input[name="val_startdt"]').val(msg['startdt']);
									$('#editbox input[name="val_enddt"]').val(msg['enddt']);
									$('#editbox input[name="val_url"]').val(msg['url']);
									$('#editbox textarea[name="val_content"]').val(msg['content']);
									if(msg['isallday'] == '1'){
										$('#editbox input[name="val_isallday"]:eq(0)').attr('checked', true);
										$('#editbox input[name="val_isallday"]:eq(1)').attr('checked', false);
									}else{
										$('#editbox input[name="val_isallday"]:eq(0)').attr('checked', false);
										$('#editbox input[name="val_isallday"]:eq(1)').attr('checked', true);
									}
								}
							});
						},
						focus: true
					}
				]
			});
			return false;
		},
		eventDrop: function(event, dayDelta){
			ZENG.msgbox.show('正在更新中，请稍后...', 6, 100000);
			$.ajax({
				type: 'POST',
				url: 'index.ajax.php',
				data: 'ac=quick&do=drop&id=' + event._id + '&dayDelta=' + dayDelta,
				success: function(){
					ZENG.msgbox._hide();
				}
			});
		},
		eventResize: function(event, dayDelta){
			ZENG.msgbox.show('正在更新中，请稍后...', 6, 100000);
			$.ajax({
				type: 'POST',
				url: 'index.ajax.php',
				data: 'ac=quick&do=resize&id=' + event._id + '&dayDelta=' + dayDelta,
				success: function(){
					ZENG.msgbox._hide();
				}
			});
		},
		loading: function(bool){
			if(bool){
				ZENG.msgbox.show('正在加载中，请稍后...', 6, 100000);
			}else{
				ZENG.msgbox._hide();
			}
		}
	});
});
function showRequest(formData, jqForm, options){
	//alert('About to submit: \n\n' + $.param(formData));
	return true;
}
function showResponse(responseText, statusText, xhr, $form){
	//alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + '\n\nThe output div should have already been updated with the responseText.');
	$('#calendar').show();
	$('#editbox').hide();
	$('#calendar').fullCalendar('refetchEvents');
}
function getMyDay(day){
	var text = '周';
	switch(day){
		case 0: text += '日'; break;
		case 1: text += '一'; break;
		case 2: text += '二'; break;
		case 3: text += '三'; break;
		case 4: text += '四'; break;
		case 5: text += '五'; break;
		case 6: text += '六'; break;
	}
	return text;
}
function getMyHours(hours){
	var text = '';
	if(hours >= 0 && hours < 6){ text += '凌晨'; }
	else if(hours >= 6 && hours < 12){ text += '上午'; }
	else if(hours >= 12 && hours < 13){ text += '中午'; }
	else if(hours >= 13 && hours < 18){ text += '下午'; }
	else if(hours >= 18 && hours < 24){ text += '晚上'; }
	return text;
}
function clearEditForm(){
	$('#editbox input[name="id"], #editbox input[name="val_title"], #editbox input[name="val_startdt"], #editbox input[name="val_enddt"], #editbox input[name="val_url"]').val('');
	$('#editbox input[name="val_isallday"]:eq(0)').attr('checked', true);
	$('#editbox input[name="val_isallday"]:eq(1)').attr('checked', false);
	$('#editbox textarea[name="val_content"]').val('');
}
</script>
</body>
</html>