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
use oneborn\economyapi\EconomyAPI;
class Main extends PluginBase implements Listener{
    ###########################################################################
    public function onEnable(){
		$this->economy = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
		if (!$this->economy) {
	        $this->getLogger()->info("Add EconomyAPI to LevelUP");
		}
        $this->getLogger()->info("ZERO Is Babbyg(!)");
        $this->db = new \SQLite3($this->getDataFolder() . "Level.db");
		$this->db->exec("CREATE TABLE IF NOT EXISTS master (player TEXT PRIMARY KEY COLLATE NOCASE, lvl TEXT);");
		
        $this->db = new \SQLite3($this->getDataFolder() . "Level.db");
        $this->db->exec("CREATE TABLE IF NOT EXISTS master (player TEXT PRIMARY KEY COLLATE NOCASE, lvl TEXT);");
  $this->prefs = new Config($this->getDataFolder() . "Prefs.yml", CONFIG::YAML, array(
			"Starting Level" => 1,
			"Cost To Level Up" => 1,
		));
		$this->prefs = new Config($this->getDataFolder() . "Form.yml", CONFIG::YAML, array(
			"Levelup Title" => "Level Title",
			"Levelup Label" => "Zero",
			"Levelup Close button" => "CLOSE",
			"Levelup Levelup button" => "LevelUp",
		));
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
$lt = $this->plugin->prefs->get("Levelup Title");
$ll = $this->plugin->prefs->get("Levelup Label");
$lc = $this->plugin->prefs->get("Levelup Close button");
$lc = $this->plugin->prefs->get("Levelup Levelup button");
        $form->setTitle("$lt");
        $form->setContent("$ll");
		$form->addButton("$lc");
        $form->addButton("$llu");
        $form->sendToPlayer($player);
        return $form;
			}
    ###########################################################################
	$sl = $this->plugin->prefs->get("Starting Level");
	public function getLevel($player){
		$stmt = $this->db->query("SELECT * FROM master WHERE `player` LIKE '$player';");
		$array = $stmt->fetchArray(SQLITE3_ASSOC);
		if(!$array){
			$this->setlvl($player, $sl, 0);
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