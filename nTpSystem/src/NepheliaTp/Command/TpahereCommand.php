<?php

namespace NepheliaTp\Command;

use NepheliaTp\Main;
use NepheliaTp\Utils\CooldownTrait;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class TpahereCommand extends Command {
    use CooldownTrait;

    /** @var array */
    private static $tpahereRequests = [];

    public function __construct() {
        parent::__construct(Main::$commands->getNested("tpahere.name"), Main::$commands->getNested("tpahere.description"), Main::$commands->getNested("tpahere.usage"));
        $this->setPermission("nepheliashop.permissions.command.tpahere");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if ($sender instanceof Player) {
            if (isset($args[0])) {
                $player = Server::getInstance()->getPlayerByPrefix((string)$args[0]);
                if ($player instanceof Player) {
                    if ($this->isOnCooldown($sender->getName())) {
                        $cooldown = $this->getCooldown($sender->getName());
                        $sender->sendMessage(str_replace("{time}",$cooldown,Main::$messages->getNested("tpahere.cooldown")));
                        return;
                    }
                    $this->setCooldown($sender->getName(), Main::getInstance()->getConfig()->getNested('tpSystem.tpahere.cooldown', 15));
                    $player->sendMessage(str_replace("{player}", $sender->getName(), Main::$messages->getNested("tpahere.recu")));
                    $sender->sendMessage(str_replace("{player}", $player->getName(), Main::$messages->getNested("tpahere.send")));
                    self::$tpahereRequests[$player->getName()] = $sender->getName();
                } else {
                    $sender->sendMessage(Main::$messages->getNested("tpahere.not-online"));
                }
            } else {
                $sender->sendMessage(Main::$messages->getNested("tpahere.not-found"));
            }
        }
    }

    public static function getTpahereRequests(): array {
        return self::$tpahereRequests;
    }

    public static function removeTpahereRequest(string $receiver): void {
        unset(self::$tpahereRequests[$receiver]);
    }
}