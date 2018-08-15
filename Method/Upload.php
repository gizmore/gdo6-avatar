<?php
namespace GDO\Avatar\Method;

use GDO\Avatar\GDO_Avatar;
use GDO\Avatar\GDO_UserAvatar;
use GDO\Core\Website;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\UI\GDT_Button;
use GDO\User\GDO_User;
use GDO\File\GDT_ImageFile;
use GDO\Core\Application;
use GDO\Avatar\Module_Avatar;

final class Upload extends MethodForm
{
	public function isUserRequired() { return true; }
	public function isGuestAllowed() { return Module_Avatar::instance()->cfgGuestAvatars(); }
	
	public function createForm(GDT_Form $form)
	{
		$form->addField(GDT_ImageFile::make('avatar_image')->minfiles(1)->action($this->href()));
		$form->addField(GDT_Submit::make()->label('btn_upload'));
		$form->addField(GDT_AntiCSRF::make());
		$form->addField(GDT_Button::make('btn_set_avatar')->href(href('Avatar', 'Set')));
	}
	
	public function formValidated(GDT_Form $form)
	{
		$avatar = GDO_Avatar::blank(['avatar_file_id'=>$form->getFormVar('avatar_image')])->insert();
		GDO_UserAvatar::updateAvatar(GDO_User::current(), $avatar->getID());
		if (Application::instance()->isAjax())
		{
			return $this->message('msg_avatar_uploaded');
		}
		return $this->message('msg_avatar_uploaded')->add(Website::redirectMessage(href('Avatar', 'Set')));
	}
}
