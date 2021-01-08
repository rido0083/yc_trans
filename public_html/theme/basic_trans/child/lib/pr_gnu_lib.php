<?php
/*
# 그누보드에서 사용되는 함수지만 추가적인 기능이 필요하거나 변경이 필요한 경우 복사됨
*/
//그누보드에서 복사된 함수
function pr_member_profile_img($mb_id=''){		// 이미지 url만을 치환받기위해 복사됨
    global $member;
    static $no_profile_cache = '';
    static $member_cache = array();
    $src = '';
    if( $mb_id ){
        if( isset($member_cache[$mb_id]) ){
            $src = $member_cache[$mb_id];
        } else {
            $member_img = G5_DATA_PATH.'/member_image/'.substr($mb_id,0,2).'/'.$mb_id.'.gif';
            if (is_file($member_img)) {
                $member_cache[$mb_id] = $src = str_replace(G5_DATA_PATH, G5_DATA_URL, $member_img);
            }
        }
    }
    if( !$src ){
        if( !empty($no_profile_cache) ){
            $src = $no_profile_cache;
        } else {
            // 프로필 이미지가 없을때 기본 이미지
            $no_profile_img = (defined('G5_THEME_NO_PROFILE_IMG') && G5_THEME_NO_PROFILE_IMG) ? G5_THEME_NO_PROFILE_IMG : G5_NO_PROFILE_IMG;
            $tmp = array();
            preg_match( '/src="([^"]*)"/i', $foo, $tmp );
            $no_profile_cache = $src = isset($tmp[1]) ? $tmp[1] : G5_IMG_URL.'/no_profile.gif';
        }
    }
    return $src;
}
//그누보드에서 복사된 함수 end
?>
