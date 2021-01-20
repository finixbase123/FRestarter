<?php

namespace finixbase123\command;

use pocketmine\command\{
    Command, CommandSender
};
use pocketmine\Server;
use finixbase123\{
    Restarter, task\CountDownTask
};

class RestarterCommand extends Command
{
    public $owner;
    
    public function __construct(Restarter $owner)
    {
        parent::__construct('재부팅', '재부팅', '/재부팅', ['restarter']);
        $this->setPermission('op');
        $this->owner = $owner;
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$sender->isOp()) return;
            $this->owner->getScheduler()->scheduleRepeatingTask(new CountDownTask($this->owner), 20);
            Server::getInstance()->broadcastMessage($this->owner->db['prefix'] . str_replace('(n)', "\n", $this->owner->db['notice']));
    }
}