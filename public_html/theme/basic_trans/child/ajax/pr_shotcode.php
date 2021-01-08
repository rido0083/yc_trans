<?php
include_once('./_common.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="/js/font-awesome/css/font-awesome.min.css">', 0);
add_stylesheet('<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">', 0);

//많은 숏코드의 사용은 사이트의 속도저하를 가져올 수 있습니다. (아마도요)

if ($_POST['pr_shortcode'] == 'true') {
  $code_name = $_POST['code_name'];
  $code_array = $_POST['code_array'];

  //알림 (최근게시물)을 반환
  if ($code_name == 'bell_latest') {
    $sql = " select * from ".PR_MEMO_EXP." where me_recv_mb_id = '{$member['mb_id']}' and me_key = 'pr_bell' and  me_read_datetime = '0000-00-00 00:00:00' order by pme_id desc limit 0,5 ";
    $result = sql_query($sql);
    echo '<ul>';
    for ($i=0 ; $row=sql_fetch_array($result) ; $i++) {
      $list_member = get_member($row['me_send_mb_id']);
      $get_value = pr_json_decode($row['me_value']);
      $bell_link = get_pretty_url($get_value['bo_table'], $get_value['wr_id'], '').'#c_'.$get_value['comment_id'];
      //[bo_title]에 [comment]댓글을 남기셨습니다.
      $get_value['msg'] = str_replace('[bo_title]' , '<strong>['.utf8_strcut($get_value['bo_subj'],$pr_config['bell_bo_title']).']</strong>' , $get_value['msg']);
      $get_value['msg'] = str_replace('[comment]' , '<strong>['.utf8_strcut($get_value['comment'],$pr_config['bell_subj']).']</strong>' , $get_value['msg']);
      // $get_value['msg'] = "<a href='{$bell_link}'>{$get_value['msg']}</a>";
      $get_value['msg'] = "<span class='bell_link' data-href='{$bell_link}' data-pmeid='{$row['pme_id']}'>{$get_value['msg']}</span>";

      ?>
      <li>
        <?php //var_dump($get_value);
          echo '<div><strong class="me_send_mb_id">'.$list_member['mb_nick'].'</strong> '. pr_date_return($row['me_send_datetime'])
          .' <span class="bell_delx_la" data-pmeid="'.$row['pme_id'].'"> <i class="fas fa-minus"></i> </span> </div> <div> ' . $get_value['msg'] . '</div>';
        ?>
      </li>
      <?php
    } // end for
    if ($i==0) {
      echo '
        <li> 새로운 알림이 없습니다  </li>
      ';
    }
    echo '<li class="bell_total"><a href="'.get_pretty_url('content', 'bell_list', '').'"> <strong>모두보기</strong></a> </li>';
    echo '</ul>';
    ?>
    <script>
      $(".bell_delx_la").click(function(){
        var pmeid = $(this).data('pmeid');
        $.post(
          "<?php echo PR_AJAX_URL?>/ajax_bell_update.php",
        {
          type : 'delx'
          , pmeid : pmeid
        },
          function(data) {
            if(data == 'true') {
              //페이지 새로고침
              location.reload();
            }
          }
        );
      });

      $(".bell_link").click(function(){
        var pmeid = $(this).data('pmeid')
        var bell_link = $(this).data('href');
        $.post(
          "<?php echo PR_AJAX_URL?>/ajax_bell_update.php",
        {
          type : 'link'
          , pmeid : pmeid
        },
          function(data) {
            if(data == 'true') {
              //페이지 새로고침
              location.href=bell_link;
            }

          }
        );
      });
    </script>
    <?php
  }  //if ($code_name == 'bell_latest') {


}  //if ($_POST['pr_shortcode'] == 'true') {
?>
