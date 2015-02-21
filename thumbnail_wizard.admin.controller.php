<?php
class thumbnail_wizardAdminController extends thumbnail_wizard
{
	/**
	 * Initialization
	 *
	 * @return void
	 */
	function init()
	{
	}

	/**
	 * Save Settings
	 *
	 * @return mixed
	 */
	function procThumbnail_wizardAdminInsertConfig() {
		// Get the basic information
		//$args = Context::getRequestVars();

		//$oModuleModel = &getModel('module');
		//$thumbnail_wizard_config = $oModuleModel->getModuleConfig('thumbnail_wizard');
		$oModuleController = &getController('module');

		$thumbnail_wizard_config->use = Context::get('use');
		$thumbnail_wizard_config->priority = Context::get('priority');
		//$thumbnail_wizard_config->is_save = Context::get('is_save');
		//$thumbnail_wizard_config->ratio = Context::get('ratio');
		$thumbnail_wizard_config->ratio_val = Context::get('ratio_val');
		$thumbnail_wizard_config->ratio_module_srls = Context::get('ratio_module_srls');		
		$thumbnail_wizard_config->save_module_srls =  $this->filter_module_srls(Context::get('save_module_srls'));
		$thumbnail_wizard_config->url_module_srls =  $this->filter_module_srls(Context::get('url_module_srls'));


		//$thumbnail_wizard_config->user = Context::get('user');

		//$thumbnail_wizard_config->mid = Context::get('mid');

		$oModuleController->insertModuleConfig('thumbnail_wizard', $thumbnail_wizard_config);
		$this->setMessage('success_updated');	
		//return output;
	}

	// board 모듈 srls만 리턴
	function filter_module_srls($module_srls) {

		$args = new stdClass();
		$args->module_srls = $module_srls;// implode(',',$module_srls);

		// board 모듈만 걸러짐
		$output = executeQueryArray('thumbnail_wizard.getModuleLists', $args);
		$module_infos_odered = array();

		foreach(explode(',', $module_srls) as $module_srl) {
			foreach($output->data as $module_info) {
				if($module_info->module_srl == $module_srl) {
					$module_infos_odered[] = $module_srl;
					break;
				}
			}
		}
		return implode(',',$module_infos_odered);
	}


	function procThumbnail_wizardAdminRemoveThumbnails() {
		$logged_info = Context::get('logged_info');
		if($logged_info->is_admin != 'Y') return new Object(-1, '잘못된 접근');
		



		try {
			$dir = _XE_PATH_ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'thumbnails';


			if (!file_exists($dir)) {
				return new Object(-1, "썸네일 폴더가 존재하지 않습니다."."\r\n"."($dir)");
			}


			$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
			$files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

			foreach($files as $file) {
				if ($file->isDir()){
					rmdir($file->getRealPath());
				} else {
					unlink($file->getRealPath());
				}
			}
			rmdir($dir);
		} catch(Exception $e) {
			$message = $e->getMessage(); 
			return new Object(-1, $message);
		}
		return new Object(0, '성공');
	}

}
/* End of file star_rating_config.admin.controller.php */
/* Location: ./modules/star_rating_config/star_rating_config.admin.controller.php */
