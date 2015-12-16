<?php

namespace CTF;

use pocketmine\scheduler\PluginTask;

use CTF\Main;

class GameTask extends PluginTask {
        
        private $plugin;
        
        private $players = $this->getOwner()->gamePlayers;
        
        private $status = self::WAITING;
        
        private $gameTime = $this->getOwner()->get("playTime");
        
        const QUEUE = 0;
        const PLAYING = 1;

        public function __construct(Main $plugin) {
                parent::__construct($plugin);
                $this->setHandler($plugin->getServer()->getScheduler()->scheduleRepeatingTask($this, 20));
                $this->plugin = $plugin;
        }
        
        public function onRun($tick) {
                $this->checkPlayers();
                if($this->status === self::QUEUE and count($this->players) < 10) {
                        foreach($this->players as $p) {
                                $p->sendTip("Waiting for players (" . count($this->players) . "/10");
                        }
                } else {
                        $this->status = self::PLAYING;
                }
                if($this->status === self::PLAYING and count($this->players) >= 4) {
                        $this->gameTime--;
                        foreach($this->players as $p) {
                                $p->sendTip("Game will end in " . $this->plugin->seconds2string($this->gameTime));
                        }
                } else {
                        $this->plugin->reset();
                }
        }
        
        public function checkPlayers() {
                foreach($this->players as $key => $p) {
                        if(!$p instanceof Player) unset($this->players[$key]);
                }
        }
        
}
