<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$page = $_GET['page'];
$page_name = $page.'.php';
if ($page != '') {
  //project PR
  //child의 헤더는 항상 모바일헤더를 불러주셔야 합니다.
  include_once(G5_THEME_PATH.'/head_child.php');

  if(file_exists(PR_PAGE_PATH.'/'.$page_name)) {
  	include_once(PR_PAGE_PATH.'/'.$page_name);
  } else {
    //page not 404라도 띄울까 -_-;;;
    include_once(PR_PAGE_PATH.'/404.php');
  }

  include_once(G5_THEME_PATH.'/tail.php');

  //project PR
  //마지막은 항상 닫아주셔야 합니다.
  exit();
}
?>
