<?php
namespace GDO\Avatar;

use GDO\Core\GDO_Module;
use GDO\User\GDO_User;
use GDO\DB\GDT_Checkbox;
use GDO\File\GDT_ImageFile;
use GDO\File\GDO_File;
use GDO\UI\GDT_Page;
use GDO\UI\GDT_Link;

/**
 * Avatar module.
 * Features default avatar.
 * 
 * @author gizmore
 * @version 6.10.1
 * @since 6.2.0
 * 
 * @see GDT_ImageFile
 */
final class Module_Avatar extends GDO_Module
{
	##############
	### Module ###
	##############
	public function getDependencies() { return ['File']; }
	public function onLoadLanguage() { return $this->loadLanguage('lang/avatar'); }
	public function getClasses() { return ['GDO\Avatar\GDO_Avatar','GDO\Avatar\GDO_UserAvatar']; }
	public function onIncludeScripts() { $this->addCSS('css/gdo-avatar.css'); }
	public function getUserSettingsURL() { return href('Avatar', 'Set'); }

	##############
	### Config ###
	##############
	public function getConfig()
	{
		return [
		    GDT_Checkbox::make('avatar_guests')->initial('1'),
		    GDT_Checkbox::make('avatar_sidebar')->initial('0'),
		    GDT_ImageFile::make('avatar_image_guest')->previewHREF(href('Avatar', 'Image', '&file={id}'))->scaledVersion('icon', 96, 96)->scaledVersion('thumb', 375, 375),
		    GDT_ImageFile::make('avatar_image_member')->previewHREF(href('Avatar', 'Image', '&file={id}'))->scaledVersion('icon', 96, 96)->scaledVersion('thumb', 375, 375),
		    GDT_ImageFile::make('avatar_image_male')->previewHREF(href('Avatar', 'Image', '&file={id}'))->scaledVersion('icon', 96, 96)->scaledVersion('thumb', 375, 375),
		    GDT_ImageFile::make('avatar_image_female')->previewHREF(href('Avatar', 'Image', '&file={id}'))->scaledVersion('icon', 96, 96)->scaledVersion('thumb', 375, 375),
		];
	}
	public function cfgGuestAvatars() { return $this->getConfigValue('avatar_guests'); }
	public function cfgAvatarSidebar() { return $this->getConfigValue('avatar_sidebar'); }
	
	/**
	 * @return GDT_ImageFile
	 */
	public function cfgColAvatarGuest() { return $this->getConfigColumn('avatar_image_guest'); }
	
	############
	### Init ###
	############
	public function onInitSidebar()
	{
	    if ($this->cfgAvatarSidebar())
	    {
	        GDT_Page::$INSTANCE->rightNav->addField(
	            GDT_Link::make('btn_avatar')->href(
	                href('Avatar', 'Set')));
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
