<?php
# FloatingTexts
# A new production from Ad5001 generated using ImagicalPlugCreator by Ad5001 (C) 2017

namespace Ad5001\FloatingTexts;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\entity\Entity;



class Main extends PluginBase implements \pocketmine\event\Listener {

		/*
		Called when the plugin enables
		*/
		public function onEnable() {
			$this->getServer()->getPluginManager()->registerEvents($this, $this);
			$this->sessions = [];
			$this->getServer()->getScheduler()->scheduleRepeatingTask(new SetNameTagVisibleTask($this),10);
		}

		/*
		Called when one of the defined commands of the plugin has been called
		@param     $sender        \pocketmine\command\CommandSender
		@param     $cmd           \pocketmine\command\Command
		@param     $label         mixed
		@param     $args          array
		return bool
		*/
		public function onCommand(\pocketmine\command\CommandSender $sender, \pocketmine\command\Command $cmd, $label, array $args): bool {
			switch($cmd->getName()) {
				case "createfloat":
				if($sender instanceof Player) {
					if(isset($args[0])) {
						$text = implode(" ", $args);
						$text = str_ireplace("\\n", "\n", $text);
						$this->sessions[$sender->getName()] = $text;
						$sender->sendMessage("Tap an entity !");
					}
				}
				return true;
				break;
			}
		}

		/*
		WHen a player hits an entity with a session, set his nametag
		@param     $event    \pocketmine\event\entity\EntityDamageEvent
		*/
		public function onEntityDamage(\pocketmine\event\entity\EntityDamageEvent $event) {
			if($event instanceof \pocketmine\event\entity\EntityDamageByEntityEvent) {
				if($event->getDamager() instanceof Player &&
				isset($this->sessions[$event->getDamager()->getName()])) {
					$event->getEntity()->addEffect(\pocketmine\entity\Effect::getEffectByName("invisibility")->setAmbient(true)->setVisible(false));
					$event->getEntity()->setNameTag($this->sessions[$event->getDamager()->getName()]);
                	$event->getEntity()->setNameTagAlwaysVisible(true);
            		$event->getEntity()->setNameTagVisible(true);
            		$event->getEntity()->setImmobile(true);
					$event->getEntity()->namedtag->isUsedToFloat = new \pocketmine\nbt\tag\StringTag("isUsedToFloat", "true");
					$event->getEntity()->setNameTag($this->sessions[$event->getDamager()->getName()]);
					$event->setCancelled();
					unset($this->sessions[$event->getDamager()->getName()]);
				} elseif(isset($event->getEntity()->namedtag->isUsedToFloat)) {
					if(!($event->getDamager() instanceof Player && $event->getDamager()->isOp())) $event->setCancelled();
				}
			} elseif(isset($event->getEntity()->namedtag->isUsedToFloat)) {
				$event->setCancelled();
			}
		}


		/*
		Checks when a level loads with floats to regive them the flags and effects.
		@param     $event    \pocketmine\event\level\LevelLoadEvent
		*/
		public function onLevelLoad(\pocketmine\event\level\LevelLoadEvent $event) {
			foreach ($event->getLevel()->getEntities() as $et) {
				if(isset($et->namedtag->isUsedToFloat)) {
					$et->addEffect(\pocketmine\entity\Effect::getEffectByName("invisibility")->setDuration(99999)->setVisible(false));
                	$et->setNameTagAlwaysVisible(true);
            		$et->setNameTagVisible(true);
            		$et->setImmobile(true);
				}
			}
		}
}