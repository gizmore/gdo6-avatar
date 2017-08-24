<?php
namespace GDO\Avatar\Method;

use GDO\Avatar\Module_Avatar;
use GDO\Core\Method;
use GDO\GWF\Module_GWF;
use GDO\Util\Common;

final class Image extends Method
{
	public function execute()
	{
		if (Common::getRequestInt('file') == 0)
		{
			header('Content-Type: image/jpeg');
			die(Module_Avatar::instance()->templateFile('img/default.jpeg'));
		}
		return Module_GWF::instance()->getMethod('GetFile')->execute();
	}
}
