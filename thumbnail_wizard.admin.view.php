<?php
/* Copyright (C) NAVER <http://www.navercorp.com> */

/**
 * Admin view class of thumbnail_wizard module
 *
 * @author NAVER (developers@xpressengine.com)
 */
class thumbnail_wizardAdminView extends thumbnail_wizard
{

	/**
	 * Initialization
	 *
	 * @return void
	 */
	function init()
	{
		// Get configurations (using module model object)
		$oModuleModel = getModel('module');
		$this->config = $oModuleModel->getModuleConfig('thumbnail_wizard');
		//Context::set('thumbnail_wizard_config',$this->config);

		// set the template path
		$this->setTemplatePath($this->module_path . 'tpl');
	}

	/**
	 * Admin page 
	 *
	 * @return Object
	 */
	function dispThumbnail_wizardAdminIndex()
	{
		//$oModuleModel = &getModel('module');
		Context::set('module_info',$this->module_info);
		Context::set('thumbnail_wizard',$this->config);

		// display
		$this->setTemplateFile('index');
	}



}
/* End of file thumbnail_wizard.admin.view.php */
/* Location: ./modules/thumbnail_wizard/thumbnail_wizard.admin.view.php */
