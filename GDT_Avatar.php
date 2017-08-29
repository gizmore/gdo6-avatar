<?php
namespace GDO\Avatar;

use GDO\User\GDO_User;
use GDO\DB\GDT_ObjectSelect;

final class GDT_Avatar extends GDT_ObjectSelect
{
	/**
	 * @var User
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
		$this->emptyLabel = t('choice_no_avatar_please');
		return $this->label('avatar');
	}
	
	public function getValue()
	{
	    return GDO_Avatar::getById($this->getVar());
	}
	
	public function validate($value)
	{
	    if (!$this->choices)
		{
			$this->choices($this->avatarChoices());
		}
		return parent::validate($value);
	}
	
	public function avatarChoices()
	{
	    $query = GDO_Avatar::table()->select();
		$result = $query->joinObject('avatar_file_id')->select('gdo_file.*')->where("avatar_public OR avatar_created_by={$this->user->getID()}")->exec();
		$choices = array();
		while ($gwfAvatar = $result->fetchObject())
		{
			$choices[$gwfAvatar->getID()] = $gwfAvatar;
		}
		return $choices;
	}
	
	public function renderForm()
	{
		if (!$this->choices)
		{
			$this->choices($this->avatarChoices());
		}
		return Module_Avatar::instance()->templatePHP('form/avatar.php', ['field'=>$this]);
	}
	
	public function renderCell()
	{
		return Module_Avatar::instance()->templatePHP('cell/avatar.php', ['field'=>$this]);
	}
}
