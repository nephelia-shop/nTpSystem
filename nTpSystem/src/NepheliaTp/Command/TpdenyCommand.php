<?php
namespace NepheliaTp\Command;

use NepheliaTp\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class TpdenyCommand extends Command {

    public function __construct()
    {
        parent::__construct(
            (string)Main::$commands->getNested('tpdeny.name', 'tpdeny'),
            (string)Main::$commands->getNested('tpdeny.description', '...'),
            (string)Main::$commands->getNested('tpdeny.usage', '/tpdeny'),
            (array)Main::$commands->getNested('tpdeny.aliases', []),
        );
        $this->setPermission("nepheliashop.permissions.command.tpdeny");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void
    {
        if($sender instanceof Player){
            $playerName = $sender->getName();
            $tpaRequests = TpaCommand::getTpaRequests();
            $tpahereRequests = TpahereCommand::getTpahereRequests();

            if (isset($tpaRequests[$playerName])) {
                $requesterName = $tpaRequests[$playerName];
                $requester = $sender->getServer()->getPlayerByPrefix($requesterName);

                if ($requester instanceof Player) {
                    $sender->sendMessage(Main::$messages->getNested("tpdeny.success"));
                    $requester->sendMessage(Main::$messages->getNested("tpdeny.denied"));
                    TpaCommand::removeTpaRequest($playerName);
                } else {
                    $sender->sendMessage(Main::$messages->getNested("tpaccept.requester-offline"));
                }
            } elseif (isset($tpahereRequests[$playerName])) {
                $requesterName = $tpahereRequests[$playerName];
                $requester = $sender->getServer()->getPlayerByPrefix($requesterName);

                if ($requester instanceof Player) {
                    $sender->sendMessage(Main::$messages->getNested("tpdeny.success"));
                    $requester->sendMessage(Main::$messages->getNested("tpdeny.denied"));
                    TpahereCommand::removeTpahereRequest($playerName);
                } else {
                    $sender->sendMessage(Main::$messages->getNested("tpaccept.requester-offline"));
                }
            } else {
                $sender->sendMessage(Main::$messages->getNested("tpaccept.no-request"));
            }
        }
    }

}