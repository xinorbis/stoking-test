<?php

namespace app\websocket;

use app\models\Connection;
use app\token\Jwt;
use consik\yii2websocket\events\WSClientErrorEvent;
use consik\yii2websocket\events\WSClientEvent;
use consik\yii2websocket\WebSocketServer as WSServer;
use Ratchet\ConnectionInterface;

class WebSocketServer extends WSServer
{
    public function onOpen(ConnectionInterface $conn): void
    {
        $jwt = new Jwt($conn);

        if (!$jwt->isValid()) {
            $this->disconnect($conn);
        }

        // TODO: refactor
        $userAgent = '';
        $userAgentHeader = $conn->WebSocket->request->getHeader('User-Agent');

        if ($userAgentHeader) {
            $userAgent = $userAgentHeader->toArray()[0];
        }

        try {
            $connection = new Connection();
            $connection->token = $jwt->getToken();
            $connection->userId = $jwt->getUserId();
            $connection->userAgent = $userAgent;
            $connection->openedAt = date("Y-m-d H:i:s");
            $connection->save();
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }

        $this->trigger(self::EVENT_CLIENT_CONNECTED, new WSClientEvent([
            'client' => $conn
        ]));

        $this->clients->attach($conn);
    }

    function onClose(ConnectionInterface $conn)
    {
        $jwt = new Jwt($conn);
        $userId = $jwt->getUserId();

        $connection = Connection::find()->where(['userId' => $userId])
            ->andWhere(['closedAt' => null])
            ->one();

        $connection->closedAt = date("Y-m-d H:i:s");

        try {
            $connection->save();
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }

        $this->trigger(self::EVENT_CLIENT_DISCONNECTED, new WSClientEvent([
            'client' => $conn
        ]));

        $this->clients->detach($conn);
    }

    private function disconnect(ConnectionInterface $conn): void
    {
        $this->trigger(self::EVENT_CLIENT_ERROR, new WSClientErrorEvent([
            'client' => $conn,
            'exception' => new \Exception('Invalid API key'),
        ]));

        if ($this->closeConnectionOnError) {
            $conn->close();
        }
    }
}
