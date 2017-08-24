<?php
namespace GDO\Avatar;

use GDO\DB\GDO;
use GDO\DB\GDO_EditedAt;
use GDO\DB\GDO_Object;
use GDO\File\File;
use GDO\User\GDO_User;
use GDO\User\User;

final class UserAvatar extends GDO
{
	public function gdoCached() { return false; }
	
	public function gdoColumns()
	{
		return array(
			GDO_User::make('avt_user_id')->primary(),
			GDO_Object::make('avt_avatar_id')->table(Avatar::table())->notNull(),
			GDO_EditedAt::make('avt_edited_at'),
		);
	}
	
	public static function updateAvatar(User $user, $avatarId)
	{
		$user->tempUnset('gwf_avatar');
		if ($avatarId > 0)
		{
			UserAvatar::blank(['avt_user_id'=>$user->getID(), 'avt_avatar_id'=>$avatarId])->replace();
		}
		else
		{
			UserAvatar::table()->deleteWhere('avt_user_id='.$user->getID())->exec();
		}
		$user->recache();
		return true;
	}
	
	public static function createAvatarFromString(User $user, string $filename, string $contents)
	{
		$file = File::fromString($filename, $contents)->copy();
		$avatar = Avatar::blank(['avatar_file_id' => $file->getID()])->insert();
		return self::updateAvatar($user, $avatar->getID());
	}
}
