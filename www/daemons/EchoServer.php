<?php
namespace app\daemons;

use consik\yii2websocket\events\WSClientEvent;
use consik\yii2websocket\events\WSClientMessageEvent;
use app\websocket\WebSocketServer;

class EchoServer extends WebsocketServer
{
    public function init()
    {
        parent::init();

        $port = $this->port;
        $this->on(self::EVENT_WEBSOCKET_OPEN, function($e) use($port) {
            echo "Server started at port " . $port;
        });

        $this->on(self::EVENT_CLIENT_MESSAGE, function (WSClientMessageEvent $e) {
            $e->client->send($e->message);
        });
    }
}
