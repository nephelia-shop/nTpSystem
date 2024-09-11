<?php

namespace NepheliaTp\Command;

use NepheliaTp\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class TpacceptCommand extends Command {

    public function __construct() {
        parent::__construct(Main::$commands->getNested("tpaccept.name"), Main::$commands->getNested("tpaccept.description"), Main::$commands->getNested("tpaccept.usage"));
        $this->setPermission("nepheliashop.permissions.command.tpaccept");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if ($sender instanceof Player) {
            $playerName = $sender->getName();
            $tpaRequests = TpaCommand::getTpaRequests();
            $tpahereRequests = TpahereCommand::getTpahereRequests();

            if (isset($tpaRequests[$playerName])) {
                $requesterName = $tpaRequests[$playerName];
                $requester = Server::getInstance()->getPlayerByPrefix($requesterName);

                if ($requester instanceof Player) {
                    $requester->teleport($sender->getPosition());
                    $sender->sendMessage(Main::$messages->getNested("tpaccept.success"));
                    $requester->sendMessage(Main::$messages->getNested("tpaccept.accepted"));

                    TpaCommand::removeTpaRequest($playerName);
                } else {
                    $sender->sendMessage(Main::$messages->getNested("tpaccept.requester-offline"));
                }
            } elseif (isset($tpahereRequests[$playerName])) {
                $requesterName = $tpahereRequests[$playerName];
                $requester = Server::getInstance()->getPlayerByPrefix($requesterName);

                if ($requester instanceof Player) {
                    $sender->teleport($requester->getPosition());
                    $sender->sendMessage(Main::$messages->getNested("tpaccept.success"));
                    $requester->sendMessage(Main::$messages->getNested("tpaccept.accepted"));

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