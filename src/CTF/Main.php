<?php

namespace CTF;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;

use CTF\GameTask;
use CTF\kickTask;

class Main extends PluginBase implements Listener {
        
   //     private $config = [];
        
        private $tasks = [];
        
        public $redPlayers = [];
        
        public $bluePlayers = [];
		
		public $Setter = 0;
		
		public $bluePoints = 0;
		
		public $redPoints = 0;
    
        public function onEnable() {
                //$this->saveResource("config.yml", false);
                $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
                $this->startGame();
                $this->getServer()->getLogger()->info("Capture the flag has been enabled!");
        }
        
        public function startGame() {
                if($this->tasks instanceof GameTask) return;
        }
        
        public function reset() {
                $this->getServer()->shutdown();
        }

        public function addRedPlayer(Player $p) {
                $this->redPlayers[$p->getName()] = $p;
                $p->sendMessage("You have joined the red team!");
        }

        public function addBluePlayer(Player $p) {
                $this->bluePlayers[$p->getName()] = $p;
                $p->sendMessage("You have joined the blue team!");
        }

        public function pickTeam(Player $p) {
			if(!$this->getConfig()->exists("Blue-flag-return")){
				$p->sendMessage("The game isn't set up please set up the match and restart the server!");
				return;
			}
                if(count($this->bluePlayers) < count($this->redPlayers)) {
                        $this->addBluePlayer($p);
                } elseif(count($this->redPlayers) < count($this->bluePlayers)) {
                        $this->addRedPlayer($p);
                } else {
                        $p->sendMessage("All teams are full!\n Removing you from the server in 2 seconds!");
                        new kickTask($this, $p);
                }
        }

        public function onJoin(PlayerJoinEvent $ev) {
                $p = $ev->getPlayer();
                $this->pickTeam($p);
        }
        
        public function seconds2string($int) {
                $m = floor($int / 60);
                $s = floor($int % 60);
                return (($m < 10 ? "0" : "") . $m . ":" . ($s < 10 ? "0" : "") . $s);
        }
		
		public function gameSet(PlayerInteractEvent $ev){
		$p = $ev->getPlayer();
	    $usrname = $p->getName();
		$block = $ev->getBlock();
		$levelname = $p->getLevel()->getName();
		
		if(isset($this->Setter[$usrname])){
			switch($this->Setter[$usrname]){
				
				case 0:
				$this->redFlag=array(
					"x" =>$block->getX(),
					"y" =>$block->getY(),
					"z" =>$block->getZ(),
					"level" =>$levelname);
				    $this->config->set("Red-flag",$this->redFlag);
				    $this->config->save();
					$p->sendMessage("Red flag set, please tap the blue flag!");
					$this->Setter[$usrname]++;
					break;
					
					
                case 1:
				$this->blueFlag=array(
					"x" =>$block->getX(),
					"y" =>$block->getY(),
					"z" =>$block->getZ(),
					"level" =>$levelname);
				    $this->config->set("Blue-flag",$this->blueFlag);
				    $this->config->save();
					$p->sendMessage("Blue flag set, please tap the red flag return!");
					break;
				
				case 2:
				$this->redFlagReturn=array(
					"x" =>$block->getX(),
					"y" =>$block->getY(),
					"z" =>$block->getZ(),
					"level" =>$levelname);
				    $this->config->set("Red-flag-return",$this->redFlagReturn);
				    $this->config->save();
					$p->sendMessage("Red-flag return set, please tap the blue flag return!");
					break;
					
			    case 3:
				$this->blueFlagReturn=array(
					"x" =>$block->getX(),
					"y" =>$block->getY(),
					"z" =>$block->getZ(),
					"level" =>$levelname);
				    $this->config->set("Blue-flag-return",$this->blueFlagReturn);
				    $this->config->save();
					$p->sendMessage("Set up is done, you can now play!");
					unset($this->Setter[$usrname]);
					break;
				
				
				
				
			}
		}
		}

}
