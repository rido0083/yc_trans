<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

##############################################################
#
# 그누보드5.4을 위한 확장 빌드 BUILD SRD
#
##############################################################

/**
 * PR_THEME Ver 0.1 testver
 * 제작자 : Rido
 * 제작자 메일 : rido0083@gmail.com
 * GITHUB :
 *
 *
 *본테마는 그누보드의 확장성과 기타 SRD 프로젝트의 플러그인들을 사용하기 위해 제작되었습니다.
 *일단 기본족인 child의 개념의 워드프레스의 그것과 비슷한 개념을 가지고 있습니다.
 *자세한 사항은 theme폴더의 README.txt에 기재하겠습니다.
 *
 */

//개발용 에러코드 지정
//error_reporting(E_ALL);
 ini_set("display_errors", 1);

//php버전확인
$pr_phpver = substr(phpversion(),3);

//기본설정 함수설정
define('PR_THEME_CHILD', G5_THEME_PATH.'/child');           //기본폴더지정
define('PR_TEMPLATE_URL', G5_THEME_URL.'/template');        //템플릿을 사용할경우 템플릿의 경로
define('PR_PAGE_PATH', PR_THEME_CHILD.'/page');             //페이지 기능폴더 (숏코더로 대체할 예정)
define('PR_CONTENT_PATH', PR_THEME_CHILD.'/content');             //페이지 기능폴더 (숏코더로 대체할 예정)
define('PR_LIB_PATH', PR_THEME_CHILD.'/lib');               //child의 라이브러리 폴더
define('PR_ADM_URL', PR_TEMPLATE_URL.'/adm');            //adm을 대체할 폴더
define('PR_ADM_PATH', PR_THEME_CHILD.'/adm');            //adm을 대체할 폴더
define('PR_BBS_URL', PR_TEMPLATE_URL.'/bbs');            //
define('PR_BBS_PATH', PR_THEME_CHILD.'/bbs');            //
define('PR_CHILD_URL', G5_THEME_URL.'/child');              //child의 url
define('PR_PLUGIN_URL', PR_CHILD_URL.'/plugin');            //사용하는 플러그인 url
define('PR_PLUGIN_PATH', PR_THEME_CHILD.'/plugin');         //사용하는 플러그인 path
define('PR_AJAX_URL', PR_CHILD_URL.'/ajax');         //사용하는 플러그인 path
define('PR_AJAX_JS', PR_CHILD_URL.'/js');         //사용하는 플러그인 path


//기본설정 변수설정
$pr = array();
$pr['ver'] = '0.1';		                                     	// 차후 버전업이라던가 버전 확인에 사용함
$pr['theme_ch'] = 'true';                                   //해당 테마기능을 사용할 것인지 false일 경우 기능사용을 중지

//필요한 테이블이름을 정의
/* 확장 프로그램 제작을 위해서 필요해 보이는 테이블을 확장해둠 */
define('PR_CONFIG_EXP', 'prexp_'.G5_TABLE_PREFIX.'config');    //기본 옵션저장이나 필요한 확장을 담당합니다.. varchar 타입을 저장합니다.
define('PR_NEW_EXP', 'prexp_'.G5_TABLE_PREFIX.'board_new');          //새글을 확장한다.
define('PR_MEMBER_EXP', 'prexp_'.G5_TABLE_PREFIX.'member');    //회원테이블을 확장한다.
define('PR_WRITE_EXP', 'prexp_'.G5_TABLE_PREFIX.'write');      //게시판을 확장한다.
define('PR_CONTENT_EXP', 'prexp_'.G5_TABLE_PREFIX.'content');  //컨텐츠를 확장한다.
define('PR_MEMO_EXP', 'prexp_'.G5_TABLE_PREFIX.'memo');        //메모를 확장한다. (알림을 제작한다. / 메모에 첨부(파일)등을 제작예정)
define('PR_SCRAP_EXP', 'prexp_'.G5_TABLE_PREFIX.'scrap');      //스크렙을 확장한다. (검색등을 활용)

//현재 디렉토리와 파일명을 반환 합니다.
$pr_return_uri = $_SERVER['PHP_SELF'];
$pr_php_self = explode("/", $_SERVER['PHP_SELF']);
$pr_file_cnt = count($pr_php_self) - 1;
$pr_dir_cnt = count($pr_php_self) - 2;
$pr_this_page = $pr_php_self[$pr_file_cnt];
$pr_this_dir = $pr_php_self[$pr_dir_cnt];

$pr_path = '/';

//srd theme의 기본이 되는 common파일
if ($pr['theme_ch'] == 'true') {
  include_once(PR_LIB_PATH . '/pr_common_lib.php');         //기본이 되는 함수입니다.    
  // include_once(PR_LIB_PATH . '/pr_bell_lib.php');        //알림에 관련된 함수 입니다.  
  // include_once(PR_LIB_PATH . '/pr_gnu_lib.php');         //그누함수를 대체할 목적으로 만들어져 있습니다.
  // include_once(PR_LIB_PATH . '/m3cron.extend.php');             //
  // include_once(PR_LIB_PATH . '/syntaxHightlight.php');             //
}

?>
