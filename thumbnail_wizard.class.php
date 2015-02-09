<?php
/* Copyright (C) NAVER <http://www.navercorp.com> */

/**
 * High class of thumbnail_wizard module
 *
 * @author NAVER (developers@xpressengine.com)
 */
class thumbnail_wizard extends ModuleObject
{

	/**
	 * Implement if additional tasks are necessary when installing
	 * @return Object
	 */
	function moduleInstall()
	{
		$oModuleController = &getController('module');

		//썸네일 주소 리턴
		$oModuleController->insertTrigger('document.getThumbnail', 'thumbnail_wizard', 'controller', 'triggerGetThumbnail', 'before');

		return new Object();
	}

	/**
	 * method if successfully installed
	 *
	 * @return bool
	 */
	function checkUpdate()
	{
		$oModuleModel = &getModel('module');
		//썸네일 주소 리턴
		if(!$oModuleModel->getTrigger('document.getThumbnail', 'thumbnail_wizard', 'controller', 'triggerGetThumbnail', 'before')) return true;

		return FALSE;
	}

	/**
	 * Module update
	 *
	 * @return Object
	 */
	function moduleUpdate()
	{
		$oModuleModel = &getModel('module');
		$oModuleController = &getController('module');
		//썸네일 주소 리턴
		if(!$oModuleModel->getTrigger('document.getThumbnail', 'thumbnail_wizard', 'controller', 'triggerGetThumbnail', 'before'))
		{
			$oModuleController->insertTrigger('document.getThumbnail', 'thumbnail_wizard', 'controller', 'triggerGetThumbnail', 'before');
		}

		return new Object(0, 'success_updated');
	}

	/**
	 * re-generate the cache file
	 *
	 * @return Object
	 */
	function recompileCache()
	{
		
	}

}
/* End of file thumbnail_wizard.class.php */
/* Location: ./modules/thumbnail_wizard/thumbnail_wizard.class.php */
