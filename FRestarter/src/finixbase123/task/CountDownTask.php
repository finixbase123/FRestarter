<?php

namespace finixbase123\task;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use finixbase123\Restarter;

class CountDownTask extends Task
{
    
    private $owner;
    private $count;
    
    public function __construct(Restarter $owner)
    {
        $this->owner = $owner;
        $this->count = $this->owner->db['reboot-delay-time'];
    }
    
    public function onRun(int $currentTick)
    {
        $this->count--;
        foreach(Server::getInstance()->getOnlinePlayers() as $players) {
            $players->sendTitle('§6' . $this->count);
        }
        if($this->count <= 0) {
            $this->owner->restart();
        }
    }
}