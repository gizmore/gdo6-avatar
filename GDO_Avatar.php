<?php
namespace GDO\Avatar;

use GDO\User\User;
use GDO\DB\GDO_ObjectSelect;

final class GDO_Avatar extends GDO_ObjectSelect
{
	/**
	 * @var User
	 */
	public $user;
	public function currentUser()
	{
		return $this->user(User::current());
	}
	public function user(User $user)
	{
		$this->user = $user;
		$this->var = Avatar::forUser($user)->getID();
		$this->var = $this->var > 0 ? $this->var : null;
		$this->emptyLabel = t('choice_no_avatar_please');
		return $this->label('avatar');
	}
	
	public function getValue()
	{
		return Avatar::getById($this->getVar());
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
		$query = Avatar::table()->select();
		$result = $query->joinObject('avatar_file_id')->select('gwf_file.*')->where("avatar_public OR avatar_created_by={$this->user->getID()}")->exec();
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
