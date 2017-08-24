<?php
namespace GDO\Avatar\Websocket;

use GDO\Avatar\Avatar;
use GDO\Websocket\Server\GWS_CommandForm;
use GDO\Websocket\Server\GWS_Commands;
use GDO\Websocket\Server\GWS_Global;
use GDO\Websocket\Server\GWS_Message;

final class GWS_AvatarSet extends GWS_CommandForm
{
	public function getMethod() { return method('Avatar', 'Set'); }
	
	public function hookAvatarSet(string $userId)
	{
		$user = GWS_Global::recacheUser($userId);
		$payload = GWS_Message::payload(0x0401);
		$payload .= GWS_Message::wr32($user->getID());
		$payload .= GWS_Message::wr32(Avatar::forUser($user)->getFileID());
		GWS_Global::broadcastBinary($payload);
	}
}

GWS_Commands::register(0x0401, new GWS_AvatarSet());
