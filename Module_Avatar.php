<?php
namespace GDO\Avatar;

use GDO\Core\GDO_Module;
use GDO\User\GDO_User;
use GDO\UI\GDT_Link;
use GDO\UI\GDT_Bar;
use GDO\DB\GDT_Checkbox;
use GDO\File\GDT_ImageFile;
use GDO\File\GDO_File;

final class Module_Avatar extends GDO_Module
{
	##############
	### Module ###
	##############
	public function getDependencies() { return ['File']; }
	public function onLoadLanguage() { return $this->loadLanguage('lang/avatar'); }
	public function getClasses() { return ['GDO\Avatar\GDO_Avatar','GDO\Avatar\GDO_UserAvatar']; }
	public function onIncludeScripts() { $this->addCSS('css/gdo-avatar.css'); }
	
	##############
	### Config ###
	##############
	public function getConfig()
	{
		return array(
			GDT_Checkbox::make('avatar_guests')->initial('0'),
			GDT_ImageFile::make('avatar_image_guest')->previewHREF(href('Avatar', 'Image', '&file='))->scaledVersion('icon', 96, 96)->scaledVersion('thumb', 375, 812),
			GDT_ImageFile::make('avatar_image_member')->previewHREF(href('Avatar', 'Image', '&file='))->scaledVersion('icon', 96, 96)->scaledVersion('thumb', 375, 812),
			GDT_ImageFile::make('avatar_image_male')->previewHREF(href('Avatar', 'Image', '&file='))->scaledVersion('icon', 96, 96)->scaledVersion('thumb', 375, 812),
			GDT_ImageFile::make('avatar_image_female')->previewHREF(href('Avatar', 'Image', '&file='))->scaledVersion('icon', 96, 96)->scaledVersion('thumb', 375, 812),
		);
	}
	public function cfgGuestAvatars() { return $this->getConfigValue('avatar_guests'); }
	
	/**
	 * @return GDT_ImageFile
	 */
	public function cfgColAvatarGuest() { return $this->getConfigColumn('avatar_image_guest'); }
	
	##############
	### Navbar ###
	##############
	public function hookRightBar(GDT_Bar $navbar)
	{
		$user = GDO_User::current();
		if (!$user->isGhost())
		{
			$icon = GDT_Avatar::make('avatar')->user($user)->gdo(GDO_Avatar::forUser($user))->renderCell();
			$navbar->addField(GDT_Link::make('btn_avatar')->addClass('gdo-sidebar-avatar')->rawIcon($icon)->href(href('Avatar', 'Set')));
		}
	}
	
	###############
	### Install ###
	###############
	public function onInstall()
	{
		if (!($image = $this->getConfigValue('avatar_image_guest')))
		{
			$image = GDO_File::fromPath('default.jpeg', $this->filePath('tpl/img/default.jpeg'))->insert()->copy();
			$column = $this->cfgColAvatarGuest();
			$column->createScaledVersions($image);
			$this->saveConfigVar('avatar_image_guest', $image->getID());
		}
	}
	
	############
	### Hook ###
	############
	public function hookAccountChanged(GDO_User $user)
	{
		$user->tempUnset('gdo_avatar');
	}

}
