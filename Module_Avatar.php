<?php
namespace GDO\Avatar;

use GDO\Core\Module;
use GDO\User\User;
use GDO\UI\GDT_Link;
use GDO\Template\GDT_Bar;

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
	public function hookRightBar(GDT_Bar $navbar)
	{
	    $user = User::current();
	    if (!$user->isGhost())
	    {
			$icon = GDT_Avatar::make('avatar')->user($user)->gdo(Avatar::forUser($user))->renderCell();
			$navbar->addField(GDT_Link::make('btn_avatar')->rawIcon($icon)->href($this->getMethodHREF('Set')));
		}
	}
}
