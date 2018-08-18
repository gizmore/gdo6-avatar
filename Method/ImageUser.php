<?php
namespace GDO\Avatar\Method;

use GDO\Core\Method;
use GDO\File\Method\GetFile;
use GDO\Util\Common;
use GDO\Avatar\GDO_Avatar;
use GDO\User\GDO_User;

final class ImageUser extends Method
{
	public function saveLastUrl() { return false; }
	
	public function execute()
	{
		$user = GDO_User::findById(Common::getRequestString('id'));
		$avatar = GDO_Avatar::forUser($user);
		
		$_REQUEST['file'] = $avatar->getFileID();
		
		GetFile::make()->execute();
	}
}
