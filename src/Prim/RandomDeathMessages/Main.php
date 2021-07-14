<?php

namespace Prim\RandomDeathMessages;

use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\plugin\PluginBase;

class Main extends Pluginbase implements Listener{
	
	public $messages = [];
	public $deathMessage = '';
	public $color = '';
	public $healOnKill = false;
	public $health = false;

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveResource("config.yml");
		$config = $this->getConfig();
		$this->messages = $config->get("ArrayMessage");
		$this->deathMessage = $config->get("DeathMessage");
		$this->color = $config->get("Color");
		$this->healOnKill = $config->get("HealOnKill");
		$this->health = $config->get("Health");
	}

	public function onDeath(PlayerDeathEvent $event){
		$player = $event->getPlayer();
		$cause = $player->getLastDamageCause();
		$name = $player->getName();
		if($cause instanceof EntityDamageByEntityEvent) {
			$d = $cause->getDamager();
			if ($d instanceof Player) {
				if($this->healOnKill) $d->setHealth(20);

				$hp = round($d->getHealth(), 2);
				$dname = $d->getName();
				$variable = str_replace(["%killer%", "%victim%", "%randommsg%"], [$dname, $name, $this->messages[array_rand($this->messages)]], $this->deathMessage);

				if($this->health) {
					$event->setDeathMessage("$variable §7[§c$hp" . "§7]");
					if(isset($color)) $event->setDeathMessage($this->color . "$variable §7[§c$hp" . "§7]");
					return;
				}
				
				if(isset($color)){
					$event->setDeathMessage($this->color . $variable);
					return;
				}
				$event->setDeathMessage($variable);
			}
		}
	}

}
