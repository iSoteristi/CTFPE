<?php
namespace UHC;

use pocketmine\scheduler\PluginTask;
use pocketmine\plugin\Plugin;
use pocketmine\Player;
class worldTask extends PluginTask{
	
	
public function onRun($currentTick){
	$this->getOwner()->getServer()->unloadLevel($this->getOwner()->getServer()->getLevelByName($this->getOwner()->world));
			
			}
}
