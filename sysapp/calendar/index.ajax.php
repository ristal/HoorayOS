<?php
	require('../../global.php');
	require('inc/setting.inc.php');
		
	switch($ac){
		case 'getCalendar':
			$rs = $db->select(0, 0, 'tb_calendar', '*', 'and member_id = '.$_SESSION['member']['id']);
			foreach($rs as $v){
				$tmp['id'] = $v['tbid'];
				$tmp['title'] = $v['title'];
				$tmp['start'] = $v['startdt'];
				$tmp['end'] = $v['enddt'];
				if($v['url'] != ''){
					$tmp['url'] = $v['url'];
				}
				$tmp['allDay'] = $v['isallday'] == 1 ? true : false;
				$arr[] = $tmp;
			}
			echo json_encode($arr);
			break;
		case 'getDate':
			$rs = $db->select(0, 1, 'tb_calendar', '*', 'and tbid = '.$id.' and member_id = '.$_SESSION['member']['id']);
			if($rs != NULL){
				echo json_encode($rs);
			}
			break;
		case 'quick':
			switch($do){
				case 'add':
					$db->insert(0, 0, 'tb_calendar', array(
						"title = '$title'",
						"startdt = '$start'",
						"enddt = '$end'",
						"member_id = ".$_SESSION['member']['id']
					));
					break;
				case 'drop':
					$rs = $db->select(0, 1, 'tb_calendar', 'startdt, enddt', 'and tbid = '.$id.' and member_id = '.$_SESSION['member']['id']);
					if($rs != NULL){
						$startdt = date('Y-m-d H:i:s', strtotime($rs['startdt']) + ($dayDelta*24*60*60));
						$enddt = date('Y-m-d H:i:s', strtotime($rs['enddt']) + ($dayDelta*24*60*60));
						$db->update(0, 0, 'tb_calendar', 'startdt = "'.$startdt.'", enddt = "'.$enddt.'"', 'and tbid = '.$id.' and member_id = '.$_SESSION['member']['id']);
					}
					break;
				case 'resize':
					$rs = $db->select(0, 1, 'tb_calendar', 'enddt', 'and tbid = '.$id.' and member_id = '.$_SESSION['member']['id']);
					if($rs != NULL){
						$enddt = date('Y-m-d H:i:s', strtotime($rs['enddt']) + ($dayDelta*24*60*60));
						$db->update(0, 0, 'tb_calendar', 'enddt = "'.$enddt.'"', 'and tbid = '.$id.' and member_id = '.$_SESSION['member']['id']);
					}
					break;
				case 'del':
					$db->delete(0, 0, 'tb_calendar', 'and tbid = '.$id.' and member_id = '.$_SESSION['member']['id']);
					break;
			}
			break;
		case 'edit':
			$set = array(
				"title = '$val_title'",
				"startdt = '$val_startdt'",
				"enddt = '$val_enddt'",
				"url = '$val_url'",
				"content = '$val_content'",
				"isallday = $val_isallday",
				"member_id = ".$_SESSION['member']['id']
			);
			if($id == ''){
				$db->insert(0, 0, 'tb_calendar', $set);
			}else{
				$db->update(0, 0, 'tb_calendar', $set, "and tbid = $id");
			}
			break;
	}
?>