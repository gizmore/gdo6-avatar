<?php
namespace GDO\Avatar;

use GDO\Core\GDO;
use GDO\DB\GDT_EditedAt;
use GDO\DB\GDT_Object;
use GDO\File\GDO_File;
use GDO\User\GDT_User;
use GDO\User\GDO_User;

final class GDO_UserAvatar extends GDO
{
	public function gdoCached() { return false; }
	
	public function gdoColumns()
	{
		return array(
			GDT_User::make('avt_user_id')->primary(),
		    GDT_Object::make('avt_avatar_id')->table(GDO_Avatar::table())->notNull(),
			GDT_EditedAt::make('avt_edited_at'),
		);
	}
	
	public static function updateAvatar(GDO_User $user, $avatarId)
	{
		$user->tempUnset('gdo_avatar');
		if ($avatarId > 0)
		{
		    GDO_UserAvatar::blank(['avt_user_id'=>$user->getID(), 'avt_avatar_id'=>$avatarId])->replace();
		}
		else
		{
		    GDO_UserAvatar::table()->deleteWhere('avt_user_id='.$user->getID())->exec();
		}
		$user->recache();
		return true;
	}
	
	public static function createAvatarFromString(GDO_User $user, $filename, $contents)
	{
		$file = GDO_File::fromString($filename, $contents)->copy();
		$avatar = GDO_Avatar::blank(['avatar_file_id' => $file->getID()])->insert();
		return self::updateAvatar($user, $avatar->getID());
	}
}
