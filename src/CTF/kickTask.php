<?php
namespace CTF;

use pocketmine\scheduler\PluginTask;
use pocketmine\Player;

use CTF\Main;

class kickTask extends PluginTask {
        
        private $plugin;
        
        private $player;
        
	public function __construct(Main $plugin, Player $player){
		parent::__construct($plugin);
                $this->setHandler($plugin->getServer()->getScheduler()->scheduleDelayedTask($this, 20 * 2));
                $this->plugin = $plugin;
		$this->player = $player;
	}
	
	public function onRun($tick){
                if($this->player instanceof Player) {
                        $this->player->kick("All teams were full!");
                }
                $this->plugin->getServer()->getScheduler()->cancelTask($this->getTaskId());
	}
}
