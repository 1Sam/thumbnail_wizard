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

		// 썸네일 삭제 XmlJSFilter  
        Context::addJsFilter($this->module_path.'tpl/filter', 'remove_thumbnails.xml');
		 // 콜백 함수를 처리하는 javascript 
        Context::addJsFile($this->module_path.'tpl/js/thumbnail_wizard.js');


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
		
		// 모듈에 따라 썸네일을 저장과 직접 url 리턴으로 구분함
		//Context::set('save_module_infos', $this->sort_module_srls($this->config->save_module_srls));
		//Context::set('url_module_infos', $this->sort_module_srls($this->config->url_module_srls));

		// display
		$this->setTemplateFile('index');
	}

	// 모듈 srls 을 저장순으로 재배열하여 리턴
	function sort_module_srls($module_srls) {

		$args = new stdClass();
		$args->module_srls = $module_srls;// implode(',',$module_srls);

		// board 모듈만 걸러짐
		$output = executeQueryArray('thumbnail_wizard.getModuleLists', $args);
		$module_infos_odered = array();

		foreach(explode(',', $module_srls) as $module_srl) {
			foreach($output->data as $module_info) {
				if($module_info->module_srl == $module_srl) {
					$module_infos_odered[] = $module_info;
					break;
				}
			}
		}
		return $module_infos_odered;
	}

}
/* End of file thumbnail_wizard.admin.view.php */
/* Location: ./modules/thumbnail_wizard/thumbnail_wizard.admin.view.php */
