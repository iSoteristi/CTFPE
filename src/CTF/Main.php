<?php

namespace CTF;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerJoinEvent;

use CTF\GameTask;
use CTF\kickTask;

class Main extends PluginBase implements Listener {
        
        private $config = [];
        
        private $tasks = [];
        
        public $redPlayers = [];
        
        public $bluePlayers = [];
    
        public function onEnable() {
                $this->saveResource("config.yml", false);
                $this->config = (new Config($this->getDataFolder() . "config.yml", Config::YAML)))->getAll();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
                $this->startGame();
                $this->getServer()->getLogger()->info("Capture the flag has been enabled!");
        }
        
        public function startGame() {
                if($this->task instanceof GameTask) return;
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

}