<?php
# FloatingTexts
# A new production from Ad5001 generated using ImagicalPlugCreator by Ad5001 (C) 2017

namespace Ad5001\FloatingTexts;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\entity\Entity;
class SetNameTagVisibleTask extends \pocketmine\scheduler\Task {
	public $owner;
    public function __construct($owner){$this->owner = $owner;}
	
    /*
    RUns when the task runs
    @param     $tick    int
    @return void
    */
    public function onRun($tick) {
        foreach($this->owner->getServer()->getLevels() as $level) {
            foreach ($level->getEntities() as $et) {
				if(isset($et->namedtag->isUsedToFloat)) {
                	$et->setNameTagAlwaysVisible(true);
            		$et->setNameTagVisible(true);
            		$et->setImmobile(true);
				}
			}
        }
    }
}
