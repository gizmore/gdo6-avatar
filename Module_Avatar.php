<?php
namespace GDO\Avatar;

use GDO\Core\Module;
use GDO\User\User;
use GDO\UI\GDO_Link;
use GDO\Template\GDO_Bar;

final class Module_Avatar extends Module
{
	##############
	### Module ###
	##############
	public function onLoadLanguage() { return $this->loadLanguage('lang/avatar'); }
	public function getClasses() { return ['GDO\Avatar\Avatar','GDO\Avatar\UserAvatar']; }
	
	##############
	### Navbar ###
	##############
	public function hookRightBar(GDO_Bar $navbar)
	{
	    $user = User::current();
	    if (!$user->isGhost())
	    {
			$icon = GDO_Avatar::make('avatar')->user($user)->gdo(Avatar::forUser($user))->renderCell();
			$navbar->addField(GDO_Link::make('btn_avatar')->rawIcon($icon)->href($this->getMethodHREF('Set')));
		}
	}
}
