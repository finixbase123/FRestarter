<?php

namespace finixbase123\task;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use finixbase123\{
    Restarter, task\CountDownTask
};

class RestarterTask extends Task
{
    
    private $owner;
    
    public function __construct(Restarter $owner)
    {
        $this->owner = $owner;
    }
    
    public function onRun(int $currentTick)
    {
        $this->owner->getScheduler()->scheduleRepeatingTask(new CountDownTask($this->owner), 20);
        Server::getInstance()->broadcastMessage($this->owner->db['prefix'] . str_replace('(n)', "\n", $this->owner->db['notice']));
    }
}