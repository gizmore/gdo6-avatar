<?php
namespace GDO\Avatar\Method;

use GDO\Account\Module_Account;
use GDO\Account\Method\Settings;
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
		$form->addField(GDO_Avatar::table()->gdoColumn('avatar_file_id')->action($this->href()));
		$form->addField(GDT_Submit::make()->label('btn_upload'));
		$form->addField(GDT_AntiCSRF::make());
		$form->addField(GDT_Button::make('btn_set_avatar')->href(href('Avatar', 'Set')));
	}
	
	public function formValidated(GDT_Form $form)
	{
		$user = GDO_User::current();
		$avatar = GDO_Avatar::blank(['avatar_file_id'=>$form->getFormVar('avatar_file_id')])->insert();
		GDO_UserAvatar::updateAvatar($user, $avatar->getID());
		$user->recache();
		if (Application::instance()->isAjax())
		{
			return $this->message('msg_avatar_uploaded');
		}
		return $this->message('msg_avatar_uploaded')->add(Website::redirectMessage(href('Avatar', 'Set')));
	}
	
	public function execute()
	{
		$tabs = Module_Account::instance()->renderAccountTabs();
		$nav = Settings::make()->navModules();
		return $tabs->add($nav)->add(parent::execute());
	}
	
}
