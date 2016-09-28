<?php
namespace Cloud\Backend\Controllers;
use Phalcon\Mvc\View;

class CaptchaController extends ControllerBase {
	public function indexAction()
	{
		$code = new \ValidateCode(100, 32, 4);
		$code->showImage();
		$this->session->set('authcode',$code->getCheckCode());
		$this->view->disable();
	}
}
