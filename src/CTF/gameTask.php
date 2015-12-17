<?php

namespace CTF;

use pocketmine\scheduler\PluginTask;

use CTF\Main;

class GameTask extends PluginTask {
        
        private $plugin;
        
        
        private $status = self::QUEUE;
        
        const QUEUE = 0;
        const PLAYING = 1;

        public function __construct(Main $plugin) {
                parent::__construct($plugin);
                $this->setHandler($plugin->getServer()->getScheduler()->scheduleRepeatingTask($this, 20));
                $this->plugin = $plugin;
				$this->gameTime = $this->getOwner()->config->get("playTime");
               
				$this->max = 10;
				 }
        
        public function onRun($tick) {
                $this->checkPlayers();
				 $this->players = $this->plugin->gamePlayers;
                if($this->status === self::QUEUE and count($this->plugin->getServer()->getOnlinePlayers()) < 4) {
                        foreach($this->plugin->getServer()->getOnlinePlayers() as $p) {
                                $p->sendTip("Waiting for more players![".count($this->players)."/10] 4 or more are needed!!");
                        }
						
                } else {
                        $this->status = self::PLAYING;
                }
				
                if($this->status === self::PLAYING) {
                        $this->gameTime--;
                        foreach($this->plugin->getServer()->getOnlinePlayers() as $p) {
                                $p->sendTip("Game will end in " . $this->plugin->seconds2string($this->gameTime));
                        }
                } else {
                       //stupid idea jack $this->plugin->reset();
                }
        }
        
        public function checkPlayers() {
                foreach($this->players as $key => $p) {
                        if(!$p instanceof Player) unset($this->players[$key]);
                }
        }
        
}
