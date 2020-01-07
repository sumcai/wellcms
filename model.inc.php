<?php
!defined('DEBUG') AND exit('Forbidden');

// 合并成一个文件，加快速度
// merge to one file.

// hook model_inc_start.php

$include_model_files = array(
    APP_PATH . 'model/runtime.func.php',
    APP_PATH . 'model/group.func.php',
    APP_PATH . 'model/user.func.php',
    APP_PATH . 'model/kv.func.php',
    APP_PATH . 'model/cron.func.php',
    APP_PATH . 'model/form.func.php',
    APP_PATH . 'model/misc.func.php',
    APP_PATH . 'model/session.func.php',
    APP_PATH . 'model/check.func.php',
    APP_PATH . 'model/forum.func.php',
    APP_PATH . 'model/forum_access.func.php',
    APP_PATH . 'model/attach.func.php',
    APP_PATH . 'model/thread.func.php',
    APP_PATH . 'model/thread_tid.func.php',
    APP_PATH . 'model/sticky.func.php',
    APP_PATH . 'model/data.func.php',
    APP_PATH . 'model/comment.func.php',
    APP_PATH . 'model/comment_pid.func.php',
    APP_PATH . 'model/flag.func.php',
    APP_PATH . 'model/flag_thread.func.php',
    APP_PATH . 'model/tag.func.php',
    APP_PATH . 'model/tag_thread.func.php',
    APP_PATH . 'model/page.func.php',
    APP_PATH . 'model/link.func.php',
    APP_PATH . 'model/misc_portal.func.php',
    APP_PATH . 'model/misc_category.func.php',
    APP_PATH . 'model/misc_array.func.php',
    APP_PATH . 'model/image.func.php',
    APP_PATH . 'model/theme.func.php',

    // hook model_inc_file.php

);

// hook model_inc_include_before.php

if (DEBUG) {
    foreach ($include_model_files as $model_files) {
        include _include($model_files);
    }
} else {

    $model_min_file = $conf['tmp_path'] . 'model.min.php';
    $isfile = is_file($model_min_file);
    if (empty($isfile)) {
        $s = '';
        foreach ($include_model_files as $model_files) {

            // 压缩后不利于调试，有时候碰到未结束的 php 标签，会暴 500 错误
            //$s .= php_strip_whitespace(_include($model_files));

            $t = file_get_contents(_include($model_files));
            $t = trim($t);
            $t = ltrim($t, '<?php');
            $t = rtrim($t, '?>');
            $s .= "<?php\r\n" . $t . "\r\n?>";

        }

        $r = file_put_contents($model_min_file, $s);
        unset($s);
    }
    include $model_min_file;
}

// hook model_inc_end.php

/*
function xn_php_strip_whitespace($file) {
	$s = php_strip_whitespace($file);
	if(substr($s, 0, 5) == '<?php') {
		$s = substr($s, 5);
	}
	if(substr($s, -2) == '?>') {
		$s = substr($s, 0, -2);
	}
	return $s;
}*/

?>