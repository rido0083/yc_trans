<style>
#prbell {
  width: 100%;
  background-color: #fff;
  padding: 20px 40px 20px 20px;
}
table.bell_list {
    border-collapse: separate;
    border-spacing: 1px;
    text-align: center;
    line-height: 1.5;
    border-top: 1px solid #ccc;
    margin : 20px 10px;
    width: 100%;
}
table.bell_list th {
    padding: 10px;
    text-align: left;
    font-weight: bold;
    vertical-align: top;
    border-bottom: 1px solid #ccc;
}
table.bell_list td {
    padding: 10px;
    text-align: left;
    vertical-align: top;
    border-bottom: 1px solid #ccc;
}
.bell_delx {
  color: #f63e54;
  font-size: 20px;
}
.btn_top2 li {
  display: inline-block;
}
.confirm_n{
  text-align: center;
  width: 100%;
  color: #ea283f;
}
.confirm_y{
  text-align: center;
  width: 100%;
  color: #2aba8a;
}
</style>

<?php
$sql = " select count(*) as cnt from ".PR_MEMO_EXP." where me_key = 'pr_bell' and me_recv_mb_id = '{$member['mb_id']}'  ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];
$page_rows = '20';

$bell_list = array();
$i = 0;

if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)

$total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
$from_record = ($page - 1) * $page_rows; // 시작 열을 구함

$write_pages = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, get_pretty_url('content', 'bell_list', $qstr.'&amp;page='));

$sql = " select * from ".PR_MEMO_EXP." where me_key = 'pr_bell' and me_recv_mb_id = '{$member['mb_id']}' order by pme_id desc limit {$from_record} , {$page_rows} ";
$result = sql_query($sql);
?>

<div id="prbell">
  <div>
    총  <strong><?php echo number_format($total_count)?></strong> 건,
    알림 보관 기간은 <strong><?php echo $pr_config['bell_days']?></strong>일입니다.
  </div>

<form name="fboardlist" id="fboardlist" action="" onsubmit="return fboardlist_submit(this);" method="post">
  <table class="bell_list">
    <?php
    $confirm = '';
    $confirm_class = '';
    $i=0;
    while ($row = sql_fetch_array($result)) {
      $get_value['msg'] = '';
      $list_member = get_member($row['me_send_mb_id']);
      $bell_list['name'] = get_sideview($row['me_send_mb_id'], $list_member['mb_nick'], $list_member['mb_email'], $list_member['mb_homepage']);
      $get_value = pr_json_decode($row['me_value']);
      $bell_link = get_pretty_url($get_value['bo_table'], $get_value['wr_id'], '').'#c_'.$get_value['comment_id'];
      //[bo_title]에 [comment]댓글을 남기셨습니다.
      $get_value['msg'] = str_replace('[bo_title]' , '<strong>['.utf8_strcut($get_value['bo_subj'],$pr_config['bell_bo_title']).']</strong>' , $get_value['msg']);
      $get_value['msg'] = str_replace('[comment]' , '<strong>['.utf8_strcut($get_value['comment'],$pr_config['bell_subj']).']</strong>' , $get_value['msg']);
      // $bell_list['msg'] = "<a href='{$bell_link}'>{$get_value['msg']}</a>";
      $bell_list['msg'] = "<span class='bell_link' data-href='{$bell_link}' data-pmeid='{$row['pme_id']}'>{$get_value['msg']}</span>";
      if ($row['me_read_datetime'] == '0000-00-00 00:00:00') {
        $confirm = '미확인';
        $confirm_class = 'confirm_n';
      } else {
        $confirm = '확인';
        $confirm_class = 'confirm_y';
      }
    ?>
      <tr>
        <td scope="row">
          <!-- <input name="chk_wr_id" type="checkbox" id="chk_wr_id_<?php echo $i ?>"> -->
          <input type="checkbox" class="chk_wr_id" name="chk_wr_id[]" id="chk_wr_id_<?php echo $i?>" value="<?php echo $row['pme_id'] ?>" id="chk_wr_id_<?php echo $i ?>" class="">
        </td>
          <td scope="row">
            <div class="wri cnt_left bo_info">
              <span class="sound_only">작성자</span><span class="bo_guest"><span class="sv_wrap">
                <span class="profile_img">
                  <?php echo $bell_list['name']?>
                </span>
              </span>
            </div>
          </td>
          <td>
            <?php echo pr_date_return($row['me_send_datetime']);?>
          </td>
          <td>
            <div class="<?php echo $confirm_class?>" ><strong><?php echo $confirm ?></strong></div>
          </td>
          <td>
            <?php echo $bell_list['msg']?>
          </td>
          <td>
            <span class="bell_delx" data-pmeid='<?php echo $row['pme_id'] ?>'> <i class="fas fa-minus-circle"></i> </span>
          </td>
      </tr>
    <?php
      $i++;
    }

    if ($i == 0) {
      echo '
        <tr>
          <td> 알림이 없습니다. </td>
        </tr>
      ';
    }

    ?>
  </table>

<ul class="btn_top2">
  <input type="checkbox"  class="selec_chk">
  <li><a id="chkall" onclick="all_checked();" class="btn_b02">전체선택</a></li>
  <li><button type="button" onclick="fboardlist_submit('read')" class="btn_b01">확인표시</button></li>
	<li><buttn type="button" onclick="fboardlist_submit('del')" class="btn_admin">선택삭제</button></li>
</ul>

</form>

  <!-- 페이지 -->
  <div>
    <?php echo $write_pages; ?>
  </dv>
</div>

<script>
function all_checked(sw) {
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]")
          var checked_this = f.elements[i].checked;
          if (checked_this == true) {
            f.elements[i].checked = '';
          } else {
            f.elements[i].checked = 'checked';
          }
    }
}

function fboardlist_submit(f) {
  var chk_count = 0;
  var is_checked = '';
  var pmeid = new Array();
  $(".chk_wr_id").each(function(){
    is_checked = $(this).is(':checked');
    if(is_checked == true) {
      pmeid.push($(this).val());
    }
  });

  if (pmeid.length == 0) {
    alert('알림을 하나이상 선택하세요');
    return false;
  }

  if (f == 'del') {
    if (!confirm("선택한 알림을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다"))
      return false;
  }

  $.post(
    "<?php echo PR_AJAX_URL?>/ajax_bell_update.php",
  {
    type : f
    , pmeid : pmeid
  },
    function(data) {
      if(data == 'true') {
        //페이지 새로고침
        location.reload();
      }
    }
  );
}

$(".bell_delx").click(function(){
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
