<?php
namespace GDO\Avatar;

use GDO\Core\GDO;
use GDO\DB\GDT_AutoInc;
use GDO\DB\GDT_CreatedBy;
use GDO\DB\GDT_Checkbox;
use GDO\User\GDO_User;
use GDO\File\GDT_ImageFile;
/**
 * An avatar image file.
 * @author gizmore
 */
class GDO_Avatar extends GDO
{
	public function gdoCached() { return false; }
	public function gdoColumns()
	{
		return array(
			GDT_AutoInc::make('avatar_id'),
			GDT_ImageFile::make('avatar_file_id')->notNull()->scaledVersion('icon', 96, 96)->scaledVersion('thumb', 375, 812),
			GDT_CreatedBy::make('avatar_created_by')->notNull(),
			GDT_Checkbox::make('avatar_public')->initial('0'),
		);
	}
	
	public function getID() { return $this->getVar('avatar_id'); }
	public function getFileID() { return $this->getVar('avatar_file_id'); }
	
	public static function defaultAvatar(GDO_User $user)
	{
		return self::table()->blank(array(
			'avatar_id'=>'0',
			'avatar_file_id' => self::getBestDefaultAvatar($user),
		));
	}
	
	public static function getBestDefaultAvatar(GDO_User $user)
	{
		$keys = ['avatar_image_guest']; # Last resort
		if ($user->isMember())
		{
			$keys[] = 'avatar_image_member';
			switch ($user->getGender())
			{
				case 'male': $keys[] = 'avatar_image_male'; break;
				case 'female': $keys[] = 'avatar_image_female'; break;
			}
		}
		
		$module = Module_Avatar::instance();
		foreach (array_reverse($keys) as $key)
		{
			if ($file = $module->getConfigValue($key))
			{
				return $file->getID();
			}
		}
		
		return '0';
	}
	
	/**
	 * @param GDO_User $user
	 * @return self
	 */
	public static function forUser(GDO_User $user)
	{
		if (!$user->isPersisted())
		{
			return self::defaultAvatar($user);
		}
		
		if (!($avatar = $user->tempGet('gdo_avatar')))
		{
			$avatarTable = self::table();
			
			$query = GDO_UserAvatar::table()->select();
			$query->joinObject('avt_avatar_id')->select('*, gdo_file.*');
			$query->join('JOIN gdo_file ON file_id = avatar_file_id');
			$query->where('avt_user_id='.$user->getID())->first();
			if (!($avatar = $query->exec()->fetchAs($avatarTable)))
			{
				$avatar = self::defaultAvatar($user);
			}
			$user->tempSet('gdo_avatar', $avatar);
// 			$user->recache();
		}
		return $avatar;
	}
	
	/**
	 * @param GDO_User $user
	 * @return GDT_Avatar
	 */
	public function getGDOAvatar(GDO_User $user)
	{
		static $gdoType;
		if (!$gdoType) $gdoType = GDT_Avatar::make();
		return $gdoType->user($user)->gdo($this);
	}
	
	public static function renderAvatar(GDO_User $user, $avatarSize=32)
	{
		return self::forUser($user)->getGDOAvatar($user)->avatarSize($avatarSize)->renderCell();
	}
	
}
