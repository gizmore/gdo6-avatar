<?php
namespace GDO\Avatar\Method;

use GDO\Avatar\Avatar;
use GDO\Avatar\UserAvatar;
use GDO\Core\Website;
use GDO\File\GDO_File;
use GDO\Form\GDO_AntiCSRF;
use GDO\Form\GDO_Form;
use GDO\Form\GDO_Submit;
use GDO\Form\MethodForm;
use GDO\UI\GDO_Button;
use GDO\User\User;

final class Upload extends MethodForm
{
	public function createForm(GDO_Form $form)
	{
		$form->addField(GDO_File::make('avatar_image')->minfiles(1)->imageFile()->action($this->href()));
		$form->addField(GDO_Submit::make()->label('btn_upload'));
		$form->addField(GDO_AntiCSRF::make());
		$form->addField(GDO_Button::make('btn_set_avatar')->href(href('Avatar', 'Set')));
	}
	
	public function formValidated(GDO_Form $form)
	{
	    $file = $form->getFormValue('avatar_image');
		$avatar = Avatar::blank(['avatar_file_id'=>$form->getFormVar('avatar_image')])->insert();
		UserAvatar::updateAvatar(User::current(), $avatar->getID());
		return $this->message('msg_avatar_uploaded')->add(Website::redirectMessage(href('Avatar', 'Set')));
	}
}
