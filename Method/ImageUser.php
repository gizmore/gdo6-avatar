<?php
namespace GDO\Avatar\Method;

use GDO\Core\Method;
use GDO\Util\Common;
use GDO\Avatar\GDO_Avatar;
use GDO\User\GDO_User;

final class ImageUser extends Method
{
	public function execute()
	{
		if ($id = Common::getRequestInt('id'))
		{
			$user = GDO_User::findById($id);
			$avatar = GDO_Avatar::forUser($user);
			if ($file = $avatar->getFileID())
			{
				# Patch Image::execute
				$_REQUEST['file'] = $file;
			}
		}
		# Default avatar or patched
		return Image::make()->execute();
	}
}
