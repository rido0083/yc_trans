<?php
include_once('./_common.php');

/*
알림을 읽을표시를 하거나 삭제한다.
*/

 if ($_POST) {
   if ($_POST['type'] == 'read') {
     foreach ($_POST['pmeid'] as $key => $value) {
       $sql = " update ".PR_MEMO_EXP." set me_read_datetime = '".G5_TIME_YMDHIS."' where pme_id = '{$value}' ";
       $result = sql_query($sql);
     }
  } else if ($_POST['type'] == 'del') {
    foreach ($_POST['pmeid'] as $key => $value) {
      $sql = " delete from ".PR_MEMO_EXP." where pme_id = '{$value}' ";
      $result = sql_query($sql);
    }
  } else if ($_POST['type'] == 'delx') {
      $sql = " delete from ".PR_MEMO_EXP." where pme_id = '{$_POST['pmeid']}' ";
      $result = sql_query($sql);
  } else if ($_POST['type'] == 'link') {      
      $sql = " update ".PR_MEMO_EXP." set me_read_datetime = '".G5_TIME_YMDHIS."' where pme_id = '{$_POST['pmeid']}' ";
      $result = sql_query($sql);
  }


  if ($result) {
    echo 'true';
  } else {
    echo 'flase';
  }
}
?>
