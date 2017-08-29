<?php
namespace GDO\Avatar;

use GDO\Core\GDO_Module;
use GDO\User\GDO_User;
use GDO\UI\GDT_Link;
use GDO\Template\GDT_Bar;

final class Module_Avatar extends GDO_Module
{
	##############
	### Module ###
	##############
	public function onLoadLanguage() { return $this->loadLanguage('lang/avatar'); }
	public function getClasses() { return ['GDO\Avatar\GDO_Avatar','GDO\Avatar\GDO_UserAvatar']; }
	
	##############
	### Navbar ###
	##############
	public function hookRightBar(GDT_Bar $navbar)
	{
	    $user = GDO_User::current();
	    if (!$user->isGhost())
	    {
	        $icon = GDT_Avatar::make('avatar')->user($user)->gdo(GDO_Avatar::forUser($user))->renderCell();
			$navbar->addField(GDT_Link::make('btn_avatar')->rawIcon($icon)->href($this->getMethodHREF('Set')));
		}
	}
}
