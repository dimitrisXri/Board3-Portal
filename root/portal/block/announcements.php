<?php
/*
*
* @package - Board3portal
* @version $Id$
* @copyright (c) kevin / saint ( http://www.board3.de/ ), (c) Ice, (c) nickvergessen ( http://mods.flying-bits.org/ ), (c) redbull254 ( http://www.digitalfotografie-foren.de )
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
if (!defined('IN_PHPBB'))
{
   exit;
}

if (!defined('IN_PORTAL'))
{
   exit;
}

$template->assign_vars(array(
	'NEWEST_POST_IMG'			=> $user->img('icon_topic_newest', 'VIEW_NEWEST_POST'),
	'READ_POST_IMG'				=> $user->img('icon_topic_latest', 'VIEW_NEWEST_POST'),
	'S_DISPLAY_ANNOUNCEMENTS'	=> true,
));

#if (!isset($HTTP_GET_VARS['article']))
$news = request_var('announcement', -1);
if($news < 0)
{
	$fetch_news = phpbb_fetch_posts('', $portal_config['portal_number_of_announcements'], $portal_config['portal_announcements_length'], $portal_config['portal_announcements_day'], 'announcements');

	if (count($fetch_news) == 0)
	{
		$template->assign_block_vars('announcements_row', array(
			'S_NO_TOPICS'	=> true,
			'S_NOT_LAST'	=> false
		));
	}
	else
	{
		for ($i = 0; $i < count($fetch_news); $i++)
		{
			if( isset($fetch_news[$i]['striped']) && $fetch_news[$i]['striped'] == true )
			{
				$open_bracket = '[ ';
				$close_bracket = ' ]';
				$read_full = $user->lang['READ_FULL'];
				
			}
			else
			{
				$open_bracket = '';
				$close_bracket = '';
				$read_full = '';
			}
			// unread?
			$forum_id = $fetch_news[$i]['forum_id'];
			$topic_id = $fetch_news[$i]['topic_id'];
			$topic_tracking_info = get_complete_topic_tracking($forum_id, $topic_id, $global_announce_list = false);
			$unread_topic = (isset($topic_tracking_info[$topic_id]) && $fetch_news[$i]['topic_last_post_time'] > $topic_tracking_info[$topic_id]) ? true : false;

			$template->assign_block_vars('announcements_row', array(
				'ATTACH_ICON_IMG'	=> ($fetch_news[$i]['attachment']) ? $user->img('icon_topic_attach', $user->lang['TOTAL_ATTACHMENTS']) : '',
				'TITLE'				=> $fetch_news[$i]['topic_title'],
				'POSTER'			=> $fetch_news[$i]['username'],
				'U_USER_PROFILE'	=> (($fetch_news[$i]['user_type'] == USER_NORMAL || $fetch_news[$i]['user_type'] == USER_FOUNDER) && $fetch_news[$i]['user_id'] != ANONYMOUS) ? append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u=' . $fetch_news[$i]['user_id']) : '',
				'TIME'				=> $fetch_news[$i]['topic_time'],
				'TEXT'				=> $fetch_news[$i]['post_text'],
				'REPLIES'			=> $fetch_news[$i]['topic_replies'],
				'TOPIC_VIEWS'		=> $fetch_news[$i]['topic_views'],
				'U_LAST_COMMENTS'	=> append_sid("{$phpbb_root_path}viewtopic.$phpEx", (($fetch_news[$i]['forum_id']) ? 'f=' . $fetch_news[$i]['forum_id'] . '&amp;' : '') . 'p=' . $fetch_news[$i]['topic_last_post_id'] . '#p' . $fetch_news[$i]['topic_last_post_id']),
				'U_VIEW_COMMENTS'	=> append_sid("{$phpbb_root_path}viewtopic.$phpEx", (($fetch_news[$i]['forum_id']) ? 'f=' . $fetch_news[$i]['forum_id'] . '&amp;' : '') . 't=' . $fetch_news[$i]['topic_id']),
				'U_POST_COMMENT'	=> append_sid("{$phpbb_root_path}posting.$phpEx", 'mode=reply&amp;' . (($fetch_news[$i]['forum_id']) ? 'f=' . $fetch_news[$i]['forum_id'] . '&amp;' : '') . 't=' . $fetch_news[$i]['topic_id']),
				'U_READ_FULL'		=> append_sid("{$phpbb_root_path}portal.$phpEx", 'announcement=' . $i),
				'L_READ_FULL'		=> $read_full,
				'OPEN'				=> $open_bracket,
				'CLOSE'				=> $close_bracket,
				'S_NOT_LAST'		=> ($i < count($fetch_news) - 1) ? true : false,
				'S_POLL'			=> $fetch_news[$i]['poll'],
//				'MINI_POST_IMG'		=> $user->img('icon_post_target', 'POST'),
				'S_UNREAD_INFO'		=> $unread_topic,
			));
		}
	}
}
else
{
	#$fetch_news = phpbb_fetch_posts($portal_config['portal_news_forum'], $portal_config['portal_number_of_news'], 0, 0, ($portal_config['portal_show_all_news']) ? 'news_all' : 'news');
	$fetch_news = $fetch_news = phpbb_fetch_posts('', $portal_config['portal_number_of_announcements'], 0, 0, 'announcements');

	$i = $news;
	$forum_id = $fetch_news[$i]['forum_id'];
	$topic_id = $fetch_news[$i]['topic_id'];
	$topic_tracking_info = get_complete_topic_tracking($forum_id, $topic_id, $global_announce_list = false);
	$unread_topic = (isset($topic_tracking_info[$topic_id]) && $fetch_news[$i]['topic_last_post_time'] > $topic_tracking_info[$topic_id]) ? true : false;

	$template->assign_block_vars('announcements_row', array(
		'ATTACH_ICON_IMG'	=> ($fetch_news[$i]['attachment']) ? $user->img('icon_topic_attach', $user->lang['TOTAL_ATTACHMENTS']) : '',
		'TITLE'				=> $fetch_news[$i]['topic_title'],
		'POSTER'			=> $fetch_news[$i]['username'],
		'TIME'				=> $fetch_news[$i]['topic_time'],
		'TEXT'				=> $fetch_news[$i]['post_text'],
		'REPLIES'			=> $fetch_news[$i]['topic_replies'],
		'TOPIC_VIEWS'		=> $fetch_news[$i]['topic_views'],
		'U_LAST_COMMENTS'	=> append_sid("{$phpbb_root_path}viewtopic.$phpEx", (($fetch_news[$i]['forum_id']) ? 'f=' . $fetch_news[$i]['forum_id'] . '&amp;' : '') . 'p=' . $fetch_news[$i]['topic_last_post_id'] . '#p' . $fetch_news[$i]['topic_last_post_id']),
		'U_VIEW_COMMENTS'	=> append_sid("{$phpbb_root_path}viewtopic.$phpEx", (($fetch_news[$i]['forum_id']) ? 'f=' . $fetch_news[$i]['forum_id'] . '&amp;' : '') . 't=' . $fetch_news[$i]['topic_id']),
		'U_POST_COMMENT'	=> append_sid("{$phpbb_root_path}posting.$phpEx", 'mode=reply&amp;' . (($fetch_news[$i]['forum_id']) ? 'f=' . $fetch_news[$i]['forum_id'] . '&amp;' : '') . 't=' . $fetch_news[$i]['topic_id']),
		'S_POLL'			=> $fetch_news[$i]['poll'],
		'S_UNREAD_INFO'		=> $unread_topic,
	));
}

?>