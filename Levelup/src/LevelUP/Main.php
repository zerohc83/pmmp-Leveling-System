<?php

namespace LevelUP;

use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\utils\TextFormat as C;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
class Main extends PluginBase implements Listener{
    ###########################################################################
    public function onEnable(){
		
        $this->getLogger()->info("ZERO Is Babbyg(!)");
        $this->db = new \SQLite3($this->getDataFolder() . "Level.db");
		$this->db->exec("CREATE TABLE IF NOT EXISTS master (player TEXT PRIMARY KEY COLLATE NOCASE, lvl TEXT);");
		
        $this->db = new \SQLite3($this->getDataFolder() . "Level.db");
        $this->db->exec("CREATE TABLE IF NOT EXISTS master (player TEXT PRIMARY KEY COLLATE NOCASE, lvl TEXT);");
  
    }
    public function onCommand(CommandSender $sender, Command $command, $label, array $args) : bool{
        switch (strtolower($command->getName())) {
            case "lvlup":
            $this->lvl($sender);
            break;
           
        }
        return true;
    }
    ###########################################################################            

    ###########################################################################
public function lvl($player)
    {
        $form = new SimpleForm(function (Player $player, int $data = null) {

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
				
                    break;
	            case 1:
				
                        if ($this->getLevel($player) == 100) {
                            $player->sendMessage("You are max Lvl alr silly");
                        }
						$this->lvlup($player);							
                    break;


            }				
                    
        });

        $form->setTitle("§b§lLevels");
        $form->setContent("§b===========================\n§a" . $this->getLevel($player) ."§b\n===========================");
		$form->addButton("Close");
        $form->addButton("LevelUP");
        $form->sendToPlayer($player);
        return $form;
			}
    ###########################################################################
	public function getLevel($player){
		$stmt = $this->db->query("SELECT * FROM master WHERE `player` LIKE '$player';");
		$array = $stmt->fetchArray(SQLITE3_ASSOC);
		if(!$array){
			$this->setlvl($player, 0, 0);
			$this->getLevel($player);
		}
		return $array["lvl"];
	}
public function setlvl($player, int $lvls){
		$stmt = $this->db->prepare("INSERT OR REPLACE INTO master (player, lvl) VALUES (:player, :lvl);");
		$stmt->bindValue(":player", $player);
		$stmt->bindValue(":lvl", $lvls);
		return $stmt->execute();
	}
    ###########################################################################
   public function lvlup($player){
		return $this->setlvl($player, $this->getLevel($player) + 1);
	}
	
   
}