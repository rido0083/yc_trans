<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

//기본 테이블이 없다면 생성합니다.
$is_config_db = pr_exist_table (PR_CONFIG_EXP);

//디비를 생성함
/*
하나의 테이블 확장으로 작업하려 했으나 몇개의 확장 테이블을 지정해둠
`exp_id`            자동생성 번호 입니다.
`exp_key`           타입을 지정합니다.
`exp_value`         사용할 데이터로 사용합니다 (json 타입으로 저장됩니다.)
`wdate`             작성일
`udate`             수정일
*/

if ($is_config_db == 'false') {
	$is_add_exp = "
		CREATE TABLE IF NOT EXISTS `".PR_CONFIG_EXP."` (
			`exp_id` int(11) NOT NULL auto_increment,
			`exp_key` varchar(255) NOT NULL default '',
			`exp_value` text NOT NULL default '',
      `wdate` datetime NOT NULL default '0000-00-00 00:00:00',
      `udate` datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY  (`exp_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8
	";
	//echo $is_add_exp;
	@sql_query($is_add_exp);

	//기본 환경설정을 지정한다. pr_config
	$pr_insert = array();
	$pr_insert['bell_days'] = 180;											//알림을 자동삭제
	$pr_insert['bell_scrap'] = 'Y';										//친구 추가수
	$pr_insert['block_count'] = 50;											//블럭 추가수
	$pr_insert['follow_count'] = 50;											//

	$insert_value = pr_json_encode($pr_insert);
	$query = "
		 insert into ".PR_CONFIG_EXP." set exp_key = 'pr_config' , exp_value='{$insert_value}' , wdate = '".G5_TIME_YMDHIS."'
	";
	@sql_query($query);
}


//새글확장 다른 용도로 사용가능하게 기타 컬럼을 추가
$is_new_db = pr_exist_table (PR_NEW_EXP);
/*
pbn_id
bn_id
bo_table
w_id
wr_parent
mb_id
bn_key
bn_value
wdate
udate
*/
if ($is_new_db == 'false') {
	$is_add_exp = "
		CREATE TABLE IF NOT EXISTS `".PR_NEW_EXP."` (
			`pbn_id` int(11) NOT NULL auto_increment,
			`bn_id` int(11) NOT NULL ,
			`bo_table` varchar(20) NOT NULL default '',
			`wr_id` int(11) NOT NULL ,
			`wr_parent` int(11) NOT NULL ,
			`mb_id` varchar(20) NOT NULL default '',
			`bn_key` varchar(255) NOT NULL default '',
			`bn_value` text NOT NULL default '',
      `wdate` datetime NOT NULL default '0000-00-00 00:00:00',
      `udate` datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY  (`pbn_id`),
			key `bn_id` (`mb_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8
	";
	//echo '<div style="display:appsolute; top:0; left:0; z-index:9999999">'.$is_add_exp.'</div>';
	sql_query($is_add_exp);
}


$is_member_db = pr_exist_table (PR_MEMBER_EXP);
//회원 확장 다른용도로 가능하게 id nick 컬럼 추가
/*
pmb_no
mb_no
mb_id
mb_nick
mb_key
mb_value
wdate
udate
*/
if ($is_member_db == 'false') {
	$is_add_exp = "
		CREATE TABLE IF NOT EXISTS `".PR_MEMBER_EXP."` (
			`pmb_no` int(11) NOT NULL auto_increment,
			`mb_no` int(11) NOT NULL ,
			`mb_id` varchar(20) NOT NULL default '',
			`mb_nick` varchar(255) NOT NULL default '',
			`mb_key` varchar(255) NOT NULL default '',
			`mb_value` text NOT NULL default '',
      `wdate` datetime NOT NULL default '0000-00-00 00:00:00',
      `udate` datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY  (`pmb_no`),
			key `mb_no`(`mb_nick`,`mb_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8
	";
	//echo $is_add_exp;
	@sql_query($is_add_exp);
}


//게사판 확장
$is_write_db = pr_exist_table (PR_WRITE_EXP);
/*
pwr_id
wr_id
board_table
wr_key
wr_value
wdate
udate
*/
if ($is_write_db == 'false') {
	$is_add_exp = "
		CREATE TABLE IF NOT EXISTS `".PR_WRITE_EXP."` (
			`pwr_id` int(11) NOT NULL auto_increment,
			`wr_id` int(11) NOT NULL ,
			`bo_table` varchar(20) NOT NULL default '',
			`wr_key` varchar(255) NOT NULL default '',
			`wr_value` text NOT NULL default '',
      `wdate` datetime NOT NULL default '0000-00-00 00:00:00',
      `udate` datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY  (`pwr_id`),
			key `bo_table` (`wr_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8
	";
	//echo $is_add_exp;
	@sql_query($is_add_exp);
}


//content 확장
$is_content_db = pr_exist_table (PR_CONTENT_EXP);
/*
pco_id
co_id
co_key
co_value
wdate
udate
*/
if ($is_content_db == 'false') {
	$is_add_exp = "
		CREATE TABLE IF NOT EXISTS `".PR_CONTENT_EXP."` (
			`pco_id` int(11) NOT NULL auto_increment,
			`co_id` varchar(20) NOT NULL ,
			`co_key` varchar(255) NOT NULL default '',
			`co_value` text NOT NULL default '',
      `wdate` datetime NOT NULL default '0000-00-00 00:00:00',
      `udate` datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY  (`pco_id`),
			key (`co_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8
	";
	//echo '<div style="display:appsolute; top:0; left:0; z-index:9999999">'.$is_add_exp.'</div>';
	@sql_query($is_add_exp);
}


//memo 확장
$is_memo_db = pr_exist_table (PR_MEMO_EXP);
/*
pme_id
me_id
me_recv_mb_id
me_send_mb_id
me_send_datetime
me_read_datetime
me_key
me_value
*/
if ($is_memo_db == 'false') {
	$is_add_exp = "
		CREATE TABLE IF NOT EXISTS `".PR_MEMO_EXP."` (
			`pme_id` int(11) NOT NULL auto_increment,
			`me_id` int(11) NOT NULL ,
			`me_recv_mb_id` varchar(255) NOT NULL default '',
			`me_send_mb_id` varchar(255) NOT NULL default '',
			`me_send_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
      `me_read_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
			`me_key` varchar(255) NOT NULL default '',
			`me_value` text NOT NULL default '',
			PRIMARY KEY  (`pme_id`),
			key `me_id` (`me_recv_mb_id`,`me_send_mb_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8
	";
	//echo '<div style="display:appsolute; top:0; left:0; z-index:9999999">'.$is_add_exp.'</div>';
	@sql_query($is_add_exp);
}


//scrap 확장
$is_scrap_db = pr_exist_table (PR_SCRAP_EXP);
/*
pms_id
ms_id
mb_id
bo_table
wr_id
ms_key
ms_value
udate
wdate
*/
if ($is_scrap_db == 'false') {
	$is_add_exp = "
		CREATE TABLE IF NOT EXISTS `".PR_SCRAP_EXP."` (
			`pms_id` int(11) NOT NULL auto_increment,
			`ms_id` int(11) NOT NULL ,
			`mb_id` varchar(20) NOT NULL default '',
			`bo_table` varchar(20) NOT NULL default '',
			`wr_id` int(11) NOT NULL ,
			`ms_key` varchar(255) NOT NULL default '',
			`ms_value` text NOT NULL default '',
      `wdate` datetime NOT NULL default '0000-00-00 00:00:00',
      `udate` datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY  (`pms_id`),
			key `ms_id` (`mb_id`,`bo_table`,`wr_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8
	";
	//echo $is_add_exp;
	@sql_query($is_add_exp);
}
?>
