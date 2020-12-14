<?php
/*
	프로그램 : srd_project
	그누보드5의 Rido군의 번역관련 라이브러리
	ver . beta 0.3
	개발자 : rido0083@gmail.com
	그누보드 : rido
	개발일 : 2018-01-18
	- 번역 플러그인과 알림 서비스를 지원함
	- 소스 수정 / 사용은 알아서들 하시고 재배포 및 소스포함시 저작권만 유지해주세요
	- 수정시 수정사항을 메일로 피드백 해주시면 감사하겠습니다.
*/

/*
 * 공통함수 (srd project의 공통사용 함수)
 * */

//해당 플러그인에 필요한 디비가 있는지를 체크후 없다면 디비생성
function srd_exist_table($table_name) {
    $result = @sql_query("SHOW TABLES LIKE '{$table_name}'");
    if ($result->num_rows != 0) {
        return true;
    } else {
        return false;
    }
}

//환경설정을 위한 디비를 생성함
$srd['srd_config'] = G5_TABLE_PREFIX.'srd_config';
$is_config_db = srd_exist_table ($srd['srd_config']);

if ($is_config_db == false) {
    //디비를 생성함
    //기본 언어셋 관리를 위한 DB
    $is_add_config = "
      CREATE TABLE `{$srd['srd_config']}` (
      `id` int(16) NOT NULL NOT NULL AUTO_INCREMENT ,
      `c_name` varchar(255) NOT NULL,
      `c_config` text NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;		
	";
    @sql_query($is_add_config);
    $config_sql = " 
      INSERT INTO `{$srd['srd_config']}` (`c_name`, `c_config`) VALUES ('srd_lang' , 
     ";
    //@sql_query($config_sql);
}

//환경변수 설정

/*
 * 번역플러그인 0.1
 * 아주 단순한 번역 function기능만을 제공
 * */

// 이하 번역플러그인
//변수명 $g5를 쓰던것을 $srd로 변경 (해피정님 의견)
$srd['srd_lang'] = G5_TABLE_PREFIX.'srd_lang';
$is_lang_db = srd_exist_table ($srd['srd_lang']);

if ($is_lang_db == false) {
    //디비를 생성함
    //기본 언어셋 관리를 위한 DB
    $is_add_lang = "
      CREATE TABLE `{$srd['srd_lang']}` (
      `id` int(16) NOT NULL NOT NULL AUTO_INCREMENT ,
      `include` varchar(255) NOT NULL,
      `lang` char(10) NOT NULL,
      `tokey` text NOT NULL,
      `getval` text NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;		
	";
    @sql_query($is_add_lang);
    $input_sql = "
INSERT INTO `{$srd['srd_lang']}` (`id`, `include`, `lang`, `tokey`, `getval`) VALUES
(1, 'tail', 'ja', '개인정보처리방침', '個人情報の処理方針'),
(2, 'head33', 'en', '새글', 'New article'),
(3, 'head33', 'ja', '새글', '新着'),
;
    
    ";
    @sql_query($input_sql);
}


// 언어 설정
$locale = "ko_KR";
if (isset($_GET["locale"]))
    $locale = $_GET["locale"];
else if (isset($_SESSION["locale"]))
    $locale = $_SESSION["locale"];
set_session('locale', $locale);
putenv("LANG={$locale}");
setlocale(LC_ALL, "$locale.UTF-8");

$domain = "gnuboard5";
bindtextdomain($domain, G5_PATH.'/locale');
textdomain($domain);

//언어셋을 선택한다.
/*
function lang_ch ($l) {
    session_start($l);
    $_SESSION['lang'] = $l;
    $locale = $l;
    $_SESSION["locale"] = $l;
    goto_url($_SERVER["PHP_SELF"]);
}
*/
//기본언어를 한국어로 선택
if (!$_SESSION['lang']) {
    $_SESSION['lang'] = 'ko_KR';
    $_SESSION['locale'] = 'ko_KR';
}

$srd_lang = $_SESSION['lang'];
//echo $srd_lang;
//언어분류 (기본 언어는 추가해서 사용가능) 한국어는 기본언어라 생략 아래는 언어셋 이름 예제
/*
    ko
    en_US
    ja_JP
    zh_CN
 */
//사용할 언어셋을 선택 배열로 추가가능 (기본은 한국어 / 영어 / 일본어)
$iu_lnagType = array(
    'en_US','ja_JP','zh_CN'
);
//메뉴 구성을 위한 배열 (기본은 한국어 / 영어 / 일본어)
$iu_lnagMenu = array(
    'ko_KR' => '한국어' ,
    'en_US' => '영어' ,
    'ja_JP' => '일본어' ,
    'zh_CN' => '중국어' ,
);

//언어별 배열을 만든다.
$srd_rlang = array();
$sql = "select * from {$srd['srd_lang']} where lang = '{$srd_lang}'";
$result = sql_query($sql);

for ($i=0 ; $row = sql_fetch_array($result) ; $i++) {
    $srd_rlang[$row['tokey']] = $row['getval'];
}

function _lang ($str) {
    global $srd_rlang;
    global $srd_lang;
    if ($srd_rlang[$str]) {
        return $srd_rlang[$str];
    } else {
        return $str;
    }
}
//echo _lang('테스트 입니다 번역이죠');

?>
