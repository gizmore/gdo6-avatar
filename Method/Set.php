<?php
namespace GDO\Avatar\Method;

use GDO\Avatar\GDO_Avatar;
use GDO\Avatar\UserAvatar;
use GDO\Core\GDO_Hook;
use GDO\Form\GDO_AntiCSRF;
use GDO\Form\GDO_Form;
use GDO\Form\GDO_Submit;
use GDO\Form\MethodForm;
use GDO\UI\GDO_Button;
use GDO\User\User;
/**
 * Set an avatar picture out of possible choices.
 * @author gizmore
 */
final class Set extends MethodForm
{
	public function isUserRequired() { return true; }
	
	public function createForm(GDO_Form $form)
	{
		$form->addField(GDO_Avatar::make('avt_avatar_id')->currentUser());
		$form->addField(GDO_Submit::make());
		$form->addField(GDO_Button::make('btn_upload')->href(href('Avatar', 'Upload')));
		$form->addField(GDO_AntiCSRF::make());
	}
	
	public function formValidated(GDO_Form $form)
	{
	    UserAvatar::updateAvatar(User::current(), $form->getFormVar('avt_avatar_id'));
		return $this->message('msg_avatar_set')->add($this->renderPage());
	}
	
	public function afterExecute()
	{
		if ($this->getForm()->validated)
		{
			GDO_Hook::call('AvatarSet', User::current());
		}
	}
}
