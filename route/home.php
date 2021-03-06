<?php
/*
 * Copyright (C) www.wellcms.cn
*/
!defined('DEBUG') and exit('Access Denied.');

// 检查是否登录
user_login_check();

$action = param(1, 'article');

// hook home_start.php

// 从全局拉取$user
$header['mobile_title'] = '';
$header['mobile_linke'] = '';
list($member_navs, $member_menus) = nav_member();

switch ($action) {
    // hook home_case_start.php
    case 'article':
        $page = param(2, 1);
        $pagesize = $conf['pagesize'];
        $extra = array(); // 插件预留

        // hook home_article_start.php

        // 从默认的地方读取主题列表
        $thread_list_from_default = 1;

        // hook home_article_before.php

        if (1 == $thread_list_from_default) {
            // hook home_article_find_before.php
            $threadlist = well_thread_find_by_uid($uid, $page, $pagesize);
            // hook home_article_find_after.php
        }

        // hook home_article_center.php

        $allowdelete = group_access($gid, 'allowdelete') || group_access($gid, 'allowuserdelete') || 1 == $gid;

        $page_url = url('home-article-{page}', $extra);
        $num = $user['articles'];

        // hook home_article_pagination_after.php

        $pagination = pagination($page_url, $num, $page, $pagesize);

        $header['title'] = lang('my_index_page');

        // hook home_article_end.php

        include _include(theme_load('home_article'));
        break;
    case 'comment':
        // hook home_comment_start.php

        if ('GET' == $method) {

            $page = param(2, 1);
            $pagesize = 25;
            $extra = array(); // 插件预留

            // hook home_comment_before.php

            // 从默认的地方读取主题列表
            $post_list_from_default = 1;

            // hook home_comment_post_default_before.php

            if (1 == $post_list_from_default) {
                // hook home_comment_post_find_before.php
                $postlist = comment_pid_find_by_uid($uid, $page, $pagesize);
                // hook home_comment_post_find_after.php
            }

            // hook home_comment_post_default_after.php

            if (!empty($postlist)) {
                $pids = array();
                $tids = array();
                foreach ($postlist as &$_pid) {
                    $pids[] = $_pid['pid'];
                    $tids[] = $_pid['tid'];
                }

                // hook home_comment_center.php

                $threadlist = well_thread_find($tids);

                $commentlist = comment_find_by_pid($pids, $pagesize);

                foreach ($commentlist as &$val) {
                    data_format($val);
                    comment_filter($val);
                    $val['subject'] = $threadlist[$val['tid']]['subject'];
                    $val['url'] = $threadlist[$val['tid']]['url'];
                    $val['allowdelete'] = (group_access($gid, 'allowuserdelete') and $uid == $val['uid']) || forum_access_mod($val['fid'], $gid, 'allowdelete');
                }
            }

            // hook home_comment_middle.php

            $allowdelete = group_access($gid, 'allowdelete') || group_access($gid, 'allowuserdelete') || 1 == $gid;

            $page_url = url('home-comment-{page}', $extra);
            $num = $user['comments'];

            // hook home_comment_pagination_before.php

            $pagination = pagination($page_url, $num, $page, $pagesize);

            $safe_token = well_token_set($uid);

            $header['title'] = lang('my') . lang('comment');

            // hook home_comment_after.php

            include _include(theme_load('home_comment'));
        }

        // hook home_comment_end.php
        break;
    // hook home_case_end.php
}

// hook home_end.php

?>