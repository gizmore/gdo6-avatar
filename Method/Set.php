<?php
namespace GDO\Avatar\Method;

use GDO\Avatar\GDT_Avatar;
use GDO\Avatar\UserAvatar;
use GDO\Core\GDT_Hook;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\UI\GDT_Button;
use GDO\User\User;
/**
 * Set an avatar picture out of possible choices.
 * @author gizmore
 */
final class Set extends MethodForm
{
	public function isUserRequired() { return true; }
	
	public function createForm(GDT_Form $form)
	{
		$form->addField(GDT_Avatar::make('avt_avatar_id')->currentUser());
		$form->addField(GDT_Submit::make());
		$form->addField(GDT_Button::make('btn_upload')->href(href('Avatar', 'Upload')));
		$form->addField(GDT_AntiCSRF::make());
	}
	
	public function formValidated(GDT_Form $form)
	{
	    UserAvatar::updateAvatar(User::current(), $form->getFormVar('avt_avatar_id'));
		return $this->message('msg_avatar_set')->add($this->renderPage());
	}
	
	public function afterExecute()
	{
		if ($this->getForm()->validated)
		{
			GDT_Hook::call('AvatarSet', User::current());
		}
	}
}
