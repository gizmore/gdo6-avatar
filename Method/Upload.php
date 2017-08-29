<?php
namespace GDO\Avatar\Method;

use GDO\Avatar\Avatar;
use GDO\Avatar\UserAvatar;
use GDO\Core\Website;
use GDO\File\GDT_File;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\UI\GDT_Button;
use GDO\User\User;

final class Upload extends MethodForm
{
	public function createForm(GDT_Form $form)
	{
		$form->addField(GDT_File::make('avatar_image')->minfiles(1)->imageFile()->action($this->href()));
		$form->addField(GDT_Submit::make()->label('btn_upload'));
		$form->addField(GDT_AntiCSRF::make());
		$form->addField(GDT_Button::make('btn_set_avatar')->href(href('Avatar', 'Set')));
	}
	
	public function formValidated(GDT_Form $form)
	{
	    $file = $form->getFormValue('avatar_image');
		$avatar = Avatar::blank(['avatar_file_id'=>$form->getFormVar('avatar_image')])->insert();
		UserAvatar::updateAvatar(User::current(), $avatar->getID());
		return $this->message('msg_avatar_uploaded')->add(Website::redirectMessage(href('Avatar', 'Set')));
	}
}
