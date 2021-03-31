<?php

namespace Prim\RandomDeathMessages;

use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\plugin\PluginBase;

class Main extends Pluginbase implements Listener{

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveResource("config.yml");
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
				if($this->getConfig()->get("HealOnKill")) $d->setHealth(20);
				
				$hp = round($d->getHealth(), 2);
				$dname = $d->getName();
				$variable = str_replace(["%killer%", "%victim%", "%randommsg%"], [$dname, $name, $msgs[array_rand($msgs)]], $variable);
				
				if($this->getConfig()->get("Health")) {
					$event->setDeathMessage("$variable §7[§c$hp" . "§7]");
					if(isset($color)) $event->setDeathMessage($color . "$variable §7[§c$hp" . "§7]");
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
