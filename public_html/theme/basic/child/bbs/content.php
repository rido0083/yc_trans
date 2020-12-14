<?php
if (!$co['co_id'])
    alert('등록된 내용이 없습니다.');

$g5['title'] = $co['co_subject'];
// include_once('./_head.php');

$co_content = $co['co_mobile_content'] ? $co['co_mobile_content'] : $co['co_content'];
$str = conv_content($co_content, $co['co_html'], $co['co_tag_filter_use']);

// pr_child
$sql_pr = " select * from ".PR_CONTENT_EXP." where co_id = '{$co['co_id']}' ";
$row_pr = sql_fetch($sql_pr);
$pr_co_value = pr_json_decode($row_pr['co_value']);

//회원권한 설정
//{"co_filename":"bell_list","access_mblv":"2","co_point":"100","co_view":"Y"}
if ($member['mb_level'] < $pr_co_value['access_mblv']) {
  alert('회원권한이 필요합니다.',G5_URL);
}
//포인트 설정
if ($pr_co_value['co_point'] > $member['mb_point']) {
  alert('포인트가 필요한 서비스 입니다.',G5_URL);
} else {
  //포인트 차감 (포인트는 한번만 차감한다.)
  //function insert_point($mb_id, $point, $content='', $rel_table='', $rel_id='', $rel_action='', $expire=0)
  $po_content = $co['co_subject'].'열람';
  $sql = " select count(*) as cnt from {$g5['point_table']} where mb_id = '{$member['mb_id']}' and po_content = '{$po_content}' ";
  $po_content_cnt = sql_fetch($sql);
  if (!$po_content_cnt['cnt']) {
    insert_point($member['mb_id'], '-'.$pr_co_value['co_point'], $po_content);
  }
}
//var_dump($pr_co_value);
// pr_child

// $src 를 $dst 로 변환
unset($src);
unset($dst);
$src[] = "/{{쇼핑몰명}}|{{홈페이지제목}}/";
$dst[] = $config['cf_title'];
$src[] = "/{{회사명}}|{{상호}}/";
$dst[] = $default['de_admin_company_name'];
$src[] = "/{{대표자명}}/";
$dst[] = $default['de_admin_company_owner'];
$src[] = "/{{사업자등록번호}}/";
$dst[] = $default['de_admin_company_saupja_no'];
$src[] = "/{{대표전화번호}}/";
$dst[] = $default['de_admin_company_tel'];
$src[] = "/{{팩스번호}}/";
$dst[] = $default['de_admin_company_fax'];
$src[] = "/{{통신판매업신고번호}}/";
$dst[] = $default['de_admin_company_tongsin_no'];
$src[] = "/{{사업장우편번호}}/";
$dst[] = $default['de_admin_company_zip'];
$src[] = "/{{사업장주소}}/";
$dst[] = $default['de_admin_company_addr'];
$src[] = "/{{운영자명}}|{{관리자명}}/";
$dst[] = $default['de_admin_name'];
$src[] = "/{{운영자e-mail}}|{{관리자e-mail}}/i";
$dst[] = $default['de_admin_email'];
$src[] = "/{{정보관리책임자명}}/";
$dst[] = $default['de_admin_info_name'];
$src[] = "/{{정보관리책임자e-mail}}|{{정보책임자e-mail}}/i";
$dst[] = $default['de_admin_info_email'];

$str = preg_replace($src, $dst, $str);

// 스킨경로
if(trim($co['co_mobile_skin']) == '')
    $co['co_mobile_skin'] = 'basic';

$content_skin_path = get_skin_path('content', $co['co_mobile_skin']);
$content_skin_url  = get_skin_url('content', $co['co_mobile_skin']);
$skin_file = $content_skin_path.'/content.skin.php';

if($pr_co_value['co_filename']) {
  // add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
  add_stylesheet('<link rel="stylesheet" href="'.$content_skin_url.'/style.css">', 0);
  ?>
  <article id="ctt" class="ctt_<?php echo $co_id; ?>">
    <header>
        <h1><?php echo $g5['title']; ?></h1>
    </header>

    <div id="ctt_con">
        <?php
        if ($pr_co_value['co_view'] == 'Y') {
          echo $str;
        }        
        ?>
        <?php
          $content_file = PR_CONTENT_PATH .'/'. $pr_co_value['co_filename'];
          include_once($content_file.'.php');
        ?>
    </div>

  </article>
  <?php
} else if(is_file($skin_file)) {
// if(is_file($skin_file)) {
    include($skin_file);
} else {
    echo '<p>'.str_replace(G5_PATH.'/', '', $skin_file).'이 존재하지 않습니다.</p>';
}

include_once("./_tail.php");
exit;
?>
