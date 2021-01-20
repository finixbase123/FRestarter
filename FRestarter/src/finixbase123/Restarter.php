<?php

namespace finixbase123;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\{
    Config, Internet
};
use pocketmine\Server;
use finixbase123\{
    task\RestarterTask, command\RestarterCommand
};

class Restarter extends PluginBase
{
    /**
     * @var Config
     */
    public $database, $db;
    
    public function onEnable() : void
    {
		@mkdir($this->getDataFolder());
		$this->saveResource("setting.yml");
        $this->database = new Config($this->getDataFolder() . "setting.yml", Config::YAML);
        Server::getInstance()->getCommandMap()->register('restarter', new RestarterCommand($this));
        $this->db = $this->database->getAll();
        $this->getScheduler()->scheduleDelayedTask(new RestarterTask($this), ($this->db['reboot-time'] * 60 * 20));
    }
    
    public function onDisable() : void
    {
        $this->database->setAll($this->db);
        $this->database->save();
    }
    
    public function restart() : bool
    {
        static $closed = false;
        if($closed) {
            return false;
        }
        $ip = is_null($this->db['address']) ? $this->getOwnerIp() : $this->db['address'];
        $port = $this->db['port'];
        $message = str_replace('(n)', "\n", $this->db['message']);
        foreach(Server::getInstance()->getLevels() as $levels) {
            $levels->save();
        }
        foreach(Server::getInstance()->getOnlinePlayers() as $players) {
            $players->save();
            $players->transfer($ip, $port, $message);
        }
        Server::getInstance()->shutdown();
        $closed = true;
        return true;
    }
    
    public function getOwnerIp() : string
    {
        $ip = Internet::getIp();
        $inip = Internet::getInternalIP();
        if($ip !== false)
        {
            return $ip;
        }else{
            return $inip;
        }
    }
}