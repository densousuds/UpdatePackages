<?php

/**
 * Basic Modal Class
 * @package YetiForce.Modal
 * @license licenses/License.html
 * @author Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */
class Vtiger_BasicModal_View extends Vtiger_IndexAjax_View
{

	public function checkPermission(Vtiger_Request $request)
	{
		$moduleName = $request->getModule();
		if (!Users_Privileges_Model::isPermitted($moduleName, $actionName)) {
			throw new NoPermittedException(vtranslate('LBL_PERMISSION_DENIED'));
		}
	}

	public function preProcess(Vtiger_Request $request)
	{
		$moduleName = $request->getModule();
		$viewName = $request->get('view');
		echo '<div class="modal fade modal'.$moduleName.''.$viewName.'"><div class="modal-dialog"><div class="modal-content">';
		foreach ($this->getModalCss($request) as $style) {
			echo '<link rel="stylesheet" href="'.$style->getHref().'">';
		}
	}

	public function postProcess(Vtiger_Request $request)
	{
		foreach ($this->getModalScripts($request) as $script) {
			echo '<script type="' . $script->getType() . '" src="' . $script->getSrc() . '"></script>';
		}
		echo '</div></div></div>';
	}

	public function process(Vtiger_Request $request)
	{
		$this->preProcess($request);
		//Content
		$this->postProcess($request);
	}

	public function getModalScripts(Vtiger_Request $request)
	{
		$moduleName = $request->getModule();
		$viewName = $request->get('view');

		$scripts = array(
			"modules.$moduleName.resources.$viewName",
			"modules.Vtiger.resources.$viewName"
		);

		$scriptInstances = $this->checkAndConvertJsScripts($scripts);
		return $scriptInstances;
	}
	
	public function getModalCss(Vtiger_Request $request)
	{
		$moduleName = $request->getModule();
		$viewName = $request->get('view');
		$cssFileNames = [
			"modules.$moduleName.$viewName",
			"modules.Vtiger.$viewName",
		];
		$cssInstances = $this->checkAndConvertCssStyles($cssFileNames);
		$headerCssInstances = $cssInstances;
		return $headerCssInstances;
	}
}
