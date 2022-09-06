<?php

/**
 * NotifyOnMembersActions.php
 *
 * @package Notify On Members Actions
 * @link
 * @author reslava <reslava@gmail.com>
 * @copyright 2022 reslava
 * @license https://opensource.org/licenses/MIT The MIT License
 *
 * @version 0.2.2
 */
 
namespace reslava;

if (!defined('SMF'))
	die('Hacking attempt...');

/**
 * Generated by SimpleModMaker
 */
final class NotifyOnMembersActions
{
	public function hooks()
	{
		// add_integration_function('integrate_hook_name', __CLASS__ . '::methodName#', false, __FILE__);
		add_integration_function('integrate_after_create_post', __CLASS__ . '::afterCreatePost#', false, __FILE__);
		add_integration_function('integrate_login', __CLASS__ . '::login#', false, __FILE__);
		add_integration_function('integrate_credits', __CLASS__ . '::credits#', false, __FILE__);
		add_integration_function('integrate_modify_modifications', __CLASS__ . '::modifyModifications#', false, __FILE__);
		add_integration_function('integrate_admin_areas', __CLASS__ . '::adminAreas#', false, __FILE__);
		add_integration_function('integrate_admin_search', __CLASS__ . '::adminSearch#', false, __FILE__);
	}

   	/**
	 * @hook integrate_login
	 */
	public function login($member_name, $pwd, $cookieTime)
	{
		global $context, $modSettings, $user_settings, $txt, $sourcedir;					                
		
		if(!$modSettings['notify_ma_on_login'])
			return;	       
		
		loadLanguage('NotifyOnMembersActions');
		
		//$login_alert_array = explode(',', $modSettings['notify_ma_id_member']); 		
		//if(in_array($user_settings['id_member'], $login_alert_array)) {
		if($user_settings['member_name'] == $modSettings['notify_ma_member_name']) {
			$email_from = $modSettings['notify_ma_email_from']; 			
			$email_to = $modSettings['notify_ma_email_to']; 						
			
			//$headers = 'MIME-Version: 1.0' . "\r\n";
			//$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			//$headers .= 'From: ' . $email_from;	
			
			$subject = $txt['notify_ma_forum_alert'] . " " . $context['forum_name_html_safe'] . ": " . $txt['notify_ma_user'] . " " . $member_name . " " . $txt['notify_ma_logged_in']; 
			
			$body = "<strong>" . $txt['notify_ma_forum_alert'] . " " . $context['forum_name_html_safe'] . "</strong>: " . $txt['notify_ma_user'] . " <strong>" . $member_name . "</strong> " . $txt['notify_ma_logged_in'];
			
			//$send = mail($email_to, $subject, $body, $headers);		
            require_once($sourcedir . '/Subs-Post.php');
            sendmail ($email_to, $subject, $body, $email_from, $send_html = true, $priority = 4);
		}	
	}

	/**
	 * @hook integrate_after_create_post
	 */
	public function afterCreatePost($msgOptions, $topicOptions, $posterOptions, $message_columns, $message_parameters)	
	{
		global $context, $modSettings, $txt, $sourcedir;									        
		
		if(!$modSettings['notify_ma_on_new_post'])
			return;			        
		
		loadLanguage('NotifyOnMembersActions');
		
		//$login_alert_array = explode(',', $modSettings['notify_ma_id_member']); 		
		//if(in_array($posterOptions['id'], $login_alert_array)) {
		if($posterOptions['name'] == $modSettings['notify_ma_member_name']) {
			$email_from = $modSettings['notify_ma_email_from']; 			
			$email_to = $modSettings['notify_ma_email_to']; 						
			
			//$headers = 'MIME-Version: 1.0' . "\r\n";
			//$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			//$headers .= 'From: ' . $email_from;			
			
			$subject = $txt['notify_ma_forum_alert'] . " " . $context['forum_name_html_safe'] . ": " . $txt['notify_ma_user'] . " " . $posterOptions['name'] . " " . $txt['notify_ma_written_new_post']; 
			$body = "<strong>" . $txt['notify_ma_forum_alert'] . " " . $context['forum_name_html_safe'] . "</strong>: " . $txt['notify_ma_user'] . " <strong>" . $posterOptions['name'] . "</strong> " . $txt['notify_ma_written_new_post'] . "<br>"
			."<strong>" . $txt['notify_ma_subject'] . "</strong>: " . $msgOptions['subject'] . "<br>" 
			."<strong>" . $txt['notify_ma_body'] . "</strong>:<br> " . $msgOptions['body'] . "<br>"; 			
			
			//$send = mail($email_to, $subject, $body, $headers);		
            require_once($sourcedir . '/Subs-Post.php');
            sendmail ($email_to, $subject, $body, $email_from, $send_html = true, $priority = 4);
		}	
	}

	/**
	 * @hook integrate_credits
	 */
	public function credits()
	{
		global $context;

		$context['copyrights']['mods'][] = 'Notify On Members Actions by reslava &copy; 2022';
	}

	/**
	 * @hook integrate_modify_modifications
	 */
	public function modifyModifications(&$subActions)
	{
		$subActions['notify_on_members_actions'] = array($this, 'settings');		
	}
	
	public function adminAreas(array &$admin_areas)
	{
		global $txt;
		loadLanguage('NotifyOnMembersActions');

		$admin_areas['config']['areas']['modsettings']['subsections']['notify_on_members_actions'] = [$txt['notify_ma_title']];
	}

	public function adminSearch(array &$language_files, array &$include_files, array &$settings_search)
	{
		loadLanguage('NotifyOnMembersActions');
		$settings_search[] = [[$this, 'settings'], 'area=modsettings;sa=notify_on_members_actions'];
	}

	public function settings()
	{
		
		global $context, $txt, $scripturl, $modSettings, $smcFunc;						

		loadLanguage('NotifyOnMembersActions');

		$context['page_title'] = $context['settings_title'] = $txt['notify_ma_title'];
		$context['page_title_html_safe'] = $smcFunc['htmlspecialchars'](un_htmlspecialchars($context['page_title']));
		$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=notify_on_members_actions';				

		$context['linktree'][] = array(
			'url' => $scripturl. '?action=admin;area=modsettings;sa=notify_on_members_actions',
			'name' => $txt['notify_ma_title'],
		);		

		$addSettings = [];
		if (! isset($modSettings['notify_ma_on_login']))
			$addSettings['notify_ma_on_login'] = true;
		if (! isset($modSettings['notify_ma_on_new_post']))
			$addSettings['notify_ma_on_new_post'] = true;
		if (! empty($addSettings))
			updateSettings($addSettings);

		//$context['html_headers'] .= "<script type='text/javascript' src='Themes/default/scripts/suggest.js?fin20'></script>";
		loadJavaScriptFile('suggest.js', array('defer' => false, 'minimize' => true), 'smf_suggest');
        				
		$config_vars = array(			
            //array('text', 'notify_ma_id_member'),
         array('text', 'notify_ma_member_name', 'postinput' => '<script>
                             var oAddMemberSuggest = new smc_AutoSuggest({
                             sSelf: \'oAddMemberSuggest\',
                             sSessionId: \'' . $context['session_id'] . '\',
                             sSessionVar: \'' . $context['session_var'] . '\',
                             sControlId: \'notify_ma_member_name\',
                             sSearchType: \'member\',
                             bItemList: false
                         });
                         </script>',
                         'help' => $txt['notify_ma_member_name_h']),	
         array('email', 'notify_ma_email_to', 'help' => $txt['notify_ma_email_to_h']),
         array('email', 'notify_ma_email_from', 'help' => $txt['notify_ma_email_from_h']),
         array('check', 'notify_ma_on_login', 'help' => $txt['notify_ma_on_login_h']),
         array('check', 'notify_ma_on_new_post', 'help' => $txt['notify_ma_on_new_post_h']),			
        );						
	
		$context[$context['admin_menu_name']]['tab_data']['description'] = $txt['notify_ma_description'];

		// Saving?
		if (isset($_GET['save'])) {
			checkSession();

			$save_vars = $config_vars;
			saveDBSettings($save_vars);

			redirectexit('action=admin;area=modsettings;sa=notify_on_members_actions');
		}

		prepareDBSettingContext($config_vars);				
	}
}