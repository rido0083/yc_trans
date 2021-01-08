<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

##############################################################
#
# 그누보드5.4을 위한 확장 빌드 BUILD SRD
#
##############################################################

//해당기능은 차후 속도개선을 위해 m3cron을 활용해서 back으로 실행하게 수정예정입니다.


//알림문구를 배열로 정리
$pr['bell'] = array(
		'at_comment' => '[bo_title]에 댓글[comment]에 회원님을 언급하셨습니다.'  ,
		'at_write' => '[bo_title]에 글[wr_subject]에 회원님을 언급하셨습니다.'  ,
		'memo' => '[send_mb_id]의 쪽지가 도착했습니다.'  ,
		'comment' => '[bo_title]에 [comment]댓글을 남기셨습니다.'  ,
		'good' => '[bo_title]의 [wr_subject]을 추천하셨습니다.'  ,
		'friend' => '[friend]님이 [bo_title]에 글[wr_subject]를 작성하셨습니다.'
);
//		'bed' => '[bo_title]의 [bell_subject]을 비추천하셨습니다.'  ,


//마지막 삭제일을 구한다.
$par_config['bell_delday'] = '';
$del_sql = "select exp_value from ".PR_CONFIG_EXP." where exp_key = 'bell_delday' ";
$delday = sql_fetch($del_sql);
if ($delday['exp_value']) {
	$par_config['bell_delday'] = $delday['exp_value'];
} else {
	$del_sql = "insert into ".PR_CONFIG_EXP." set exp_key = 'bell_delday' , exp_value = '".G5_TIME_YMD."' , wdate = '".G5_TIME_YMDHIS."' ";
	@sql_query($del_sql);
}


//해당일수 이상의 알림은 자동삭제
function pr_bell_del ($belldel_day) {
	global $pr;
	global $pr_conig;
	if ($belldel_day != 0) {
		$del_time =  date("Y-m-d", strtotime("-{$belldel_day}day")).' 00:00:00';
		$sql = "
			delete from ".PR_MEMO_EXP." where msg_wdate < '{$del_time}' and msg_check != 'd'
		";
		@sql_query($sql);
		//삭제후 삭제한 날짜를 기록한다.
		$del_sql = "update ".PR_CONFIG_EXP." set exp_value = '".G5_TIME_YMD."' , udate = '".G5_TIME_YMDHIS."' where exp_key = 'bell_delday' ";
		@sql_query($del_sql);
	}
} // srd_pushmsg_del


//설정한 값이상인 알림을 지운다.
if ($par_config['bell_delday'] < G5_TIME_YMD) {
	pr_bell_del($pr_config['bell_days']);
}


//알림 카운트
function rd_bell_count(){
	global $member;
	$sql = " select count(*) as cnt from ".PR_MEMO_EXP." where me_recv_mb_id = '{$member['mb_id']}' and me_key = 'pr_bell' and  me_read_datetime = '0000-00-00 00:00:00' ";
	$result = sql_fetch($sql);
	return $result['cnt'];
}

//알림을 입력
function rd_bell_insert ($me_send_mb_id , $me_recv_mb_id , $board , $pr_get_write , $bell_type ) {
	global $pr;
	global $pr_config;
	$me_insert['msg'] = $pr['bell'][$bell_type];
	//function utf8_strcut( $str, $size, $suffix='...' )
	$me_insert['wr_name'] = $board['wr_name'];
	$me_insert['bo_table'] = $board['bo_table'];
	$me_insert['bo_subj'] = $board['bo_subject'];
	$me_insert['wr_id'] = $pr_get_write['wr_id'];
	$me_insert['subj'] = $pr_get_write['wr_subject'];
	$me_insert['comment'] = $pr_get_write['comment'];
	$me_insert['comment_id'] = $pr_get_write['comment_id'];
	$me_insert['href'] = $pr_get_write['href'];
	$me_value = pr_json_encode($me_insert);
	//해당 문자열을 치환한다.
	//G5_TIME_YMDHIS;
	$sql_insert = "
		insert into ".PR_MEMO_EXP."
		set me_id=''
		, me_recv_mb_id='{$me_recv_mb_id}'
		, me_send_mb_id='{$me_send_mb_id}'
		, me_key='pr_bell'
		, me_value='{$me_value}'
		, me_send_datetime='".G5_TIME_YMDHIS."'
	";
	@sql_query($sql_insert);
}


//알림 읽음 처리
function pr_bell_readbell ($pme_id) {
	global $member;
}


//알림 개별 삭제
function pr_bell_eadel ($pme_id) {
	global $member;
}

//해당회원의 알림 수
function pr_beel_count () {
	global $member;
}


//댓글작성시
//스크랩글 댓글이 달릴때
//atjs
/*
/bbs/write_comment_update.php
run_event('comment_update_after', $board, $wr_id, $w, $qstr, $redirect_url);
*/
add_event('comment_update_after', 'pr_bell_comment', 10 , 5);
function pr_bell_comment($board, $wr_id, $w, $qstr, $redirect_url){
  global $is_admin;
	global $member;
	global $pr;
	global $wr_name;
	global $wr_content;
	global $comment_id;

	//해당 게시물의 정보를 반환
	$pr_get_write = pr_get_write($board['bo_table'] , $wr_id);
	$pr_get_write['comment_id'] = $comment_id;
	$pr_get_write['comment'] = $wr_content;
	//본인의 글이 아니라면 알림생성
	if ($member['mb_id'] != $pr_get_write['mb_id']) {
		//댓글
	  rd_bell_insert("{$member['mb_id']}","{$pr_get_write['mb_id']}", $board , $pr_get_write ,"comment");
	}

	//대댓글 at.js로 처리
};

//쪽지발송시
/*
/bbs/memo_from_update.php
run_event('memo_form_update_after', $member_list, $str_nick_list, $redirect_url);
*/
add_event('memo_form_update_after','pr_bell_memo',10,3);
function pr_bell_memo($member_list, $str_nick_list, $redirect_url) {

}


//추천/비추천시 (hook이 존재하지 않음 child커스텀을 사용)
//해당 hook은 차기버전에서 지원예정
/*
add_event('memo_form_update_after','pr_memo_update',10,3);
function pr_memo_update () {

}
*/


//친구의 글작성시
/*
/bbs/write_update.php
run_event('write_update_before', $board, $wr_id, $w, $qstr);
run_event('write_update_file_insert', $bo_table, $wr_id, $upload[$i], $w);
run_event('write_update_after', $board, $wr_id, $w, $qstr, $redirect_url);
*/
add_event('write_update_after','pr_bell_write',10,5);
function pr_bell_write($board, $wr_id, $w, $qstr, $redirect_url) {

}
?>
