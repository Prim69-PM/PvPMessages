<?php

namespace Prim\RandomDeathMessages;

use pocketmine\{Player, Server};
use pocketmine\event\{Entity, Listener};

use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;

class Main extends Pluginbase implements Listener{

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveResource("config.yml");
		$this->config = new Config($this->getDataFolder()."config.yml", Config::YAML);
	}

	public function onKill(PlayerDeathEvent $event){
		$player = $event->getPlayer();
		$cause = $player->getLastDamageCause();
		$name = $player->getName();
		$msgs = $this->getConfig()->get("ArrayMessage");
		$variable = $this->getConfig()->get("DeathMessage");
		$color = $this->getConfig()->get("Color");
		if($cause instanceof EntityDamageByEntityEvent) {
			$d = $cause->getDamager();
			if ($d instanceof Player) {
			  if($this->getConfig()->get("HealOnKill") === true){
				$d->setHealth(20);
			  }
				$hp = round($d->getHealth(), 2);
				$dname = $d->getName();
	$variable = str_replace(["%killer%", "%victim%", "%randommsg%"], [$dname, $name, $msgs[array_rand($msgs)]], $variable);
				if($this->getConfig()->get("Health") === true) {
					$event->setDeathMessage($variable . " §7[§c$hp" . "§7]");
					if(isset($color)){
						$event->setDeathMessage($color . $variable . " §7[§c$hp" . "§7]");
					}
				} else {
					if(isset($color)){
						$event->setDeathMessage($color . $variable);
					} else {
						$event->setDeathMessage($variable);
					}
				}
			}
			}
		}
}
