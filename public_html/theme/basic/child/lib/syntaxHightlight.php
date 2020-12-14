<?php
    function kh_tail_sub()
    {
        global $wr_id;

        // 게시판에서 view.php 호출할때 적용하도록
        if($_SERVER['SCRIPT_NAME'] != '/bbs/board.php' || !$wr_id) return;
    ?>
<div id="re_div">test page</div>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.6/styles/a11y-dark.min.css">
<!-- <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.6/styles/default.min.css"> -->
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.6/highlight.min.js"></script>

<!-- 자동줄바꿈 css -->
<style>
  .hljs {
    white-space: pre-wrap;
    word-wrap: pre;
  }
</style>
<script>
$(document).ready(function(){
  function sh(id,type,eqno) {
      var $id = '';
      if (type=='id') {
        $id = $("#" + id);
      } else if (type=='class') {
        $id = $("." + id).eq(eqno);
      }
      if ($id.length > 0) {
          var html = $id.html();
          html = html.split("[code]").join("<pre><code>");
          html = html.split("[/code]").join("</pre></code>");

          $id.html(html);
          $id.find("pre>code").each(function() {
              var text = $.trim($(this).text());
              $(this).text(text);
          });
      }
  }
  $(function() {
      sh("bo_v_con",'id','');
      //sh("cmt_contents",'class'); // 댓글에 추가
      $('.cmt_contents').each(function(index) {
          sh("cmt_contents",'class',index);
      });

      hljs.initHighlighting();
  });
});
</script>
<?php
    }
    add_event('tail_sub', 'kh_tail_sub', G5_HOOK_DEFAULT_PRIORITY);
?>
