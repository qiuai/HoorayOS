<?php
	require('../../global.php');
	require('inc/setting.inc.php');
		
	switch($ac){
		case 'getList':
			$orderby = 'tbid desc limit '.$from.','.$to;
			if($search_1 != ''){
				$sqlwhere[] = 'username like "%'.$search_1.'%"';
			}
			if($search_2 != ''){
				$sqlwhere[] = 'type = '.$search_2;
			}
			$rs = $db->select(0, 0, 'tb_member', '*', $sqlwhere, $orderby);
			if($rs == NULL){
				$c = $db->select(0, 2, 'tb_member', 'tbid', $sqlwhere);
				echo $c.'<{|*|}>';
			}else{
				if($reset){
					$c = $db->select(0, 2, 'tb_member', 'tbid', $sqlwhere);
					echo $c.'<{|*|}>';
				}else{
					echo '-1<{|*|}>';
				}	
			}
			foreach($rs as $v){
				$type = $v['type'] == 1 ? '管理员' : '普通会员';
				echo '<tr class="list-bd">';
					echo '<td style="text-align:left;padding-left:15px">'.$v['username'].'</td>';
					echo '<td>'.$type.'</td>';
					echo '<td><a href="detail.php?memberid='.$v['tbid'].'" class="btn btn-mini btn-link">编辑</a><a href="javascript:;" class="btn btn-mini btn-link do-del" memberid="'.$v['tbid'].'">删除</a></td>';
				echo '</tr>';
			}
			break;
		case 'del':
			$db->delete(0, 0, 'tb_member', 'and tbid='.$memberid);
			break;
	}
?>