<?php
namespace GDO\Avatar;

use GDO\User\GDO_User;
use GDO\DB\GDT_ObjectSelect;

final class GDT_Avatar extends GDT_ObjectSelect
{
	public function __construct()
	{
	    $this->icon = 'image';
		$this->table(GDO_Avatar::table());
	}
	
	/**
	 * @var GDO_User
	 */
	public $user;
	public function currentUser()
	{
		return $this->user(GDO_User::current());
	}
	
	public function user(GDO_User $user)
	{
		$this->user = $user;
		$this->var = GDO_Avatar::forUser($user)->getID();
		$this->var = $this->var > 0 ? $this->var : null;
		$this->emptyLabel = 'choice_no_avatar';
		return $this->label('avatar');
	}
	
	public $avatarSize = 38;
	public function avatarSize($avatarSize)
	{
	    $this->avatarSize = $avatarSize;
	    return $this;
	}
	
	public function initChoices()
	{
		if (!$this->choices)
		{
			$this->choices($this->avatarChoices());
		}
	}
	public function avatarChoices()
	{
		$query = GDO_Avatar::table()->select();
		$result = $query->joinObject('avatar_file_id')->select('gdo_avatar.*, gdo_file.*')->where("avatar_public OR avatar_created_by={$this->user->getID()}")->exec();
		$choices = array();
		while ($gwfAvatar = $result->fetchObject())
		{
			$choices[$gwfAvatar->getID()] = $gwfAvatar;
		}
		return $choices;
	}
	
	public function renderChoice($avatar)
	{
		$gdo = $this->gdo;
		$html = Module_Avatar::instance()->templatePHP('choice/avatar.php', ['field'=>$this->gdo($avatar)]);
		$this->gdo = $gdo;
		return $html;
	}

	public function renderCell()
	{
		return Module_Avatar::instance()->templatePHP('cell/avatar.php', ['field'=>$this]);
	}
}
