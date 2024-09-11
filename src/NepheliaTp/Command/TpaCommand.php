<?php

namespace NepheliaTp\Command;

use NepheliaTp\Main;
use NepheliaTp\Utils\CooldownTrait;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class TpaCommand extends Command {
    use CooldownTrait;

    /** @var array */
    private static array $tpaRequests = [];

    public function __construct() {
        parent::__construct(Main::$commands->getNested("tpa.name"), Main::$commands->getNested("tpa.description"), Main::$commands->getNested("tpa.usage"));
        $this->setPermission("nepheliashop.permissions.command.tpa");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if ($sender instanceof Player) {
            if (isset($args[0])) {
                $player = Server::getInstance()->getPlayerByPrefix((string)$args[0]);
                if ($player instanceof Player) {
                    if ($this->isOnCooldown($sender->getName())) {
                        $cooldown = $this->getCooldown($sender->getName());
                        $sender->sendMessage(str_replace("{time}",$cooldown,Main::$messages->getNested("tpa.cooldown")));
                        return;
                    }
                    $this->setCooldown($sender->getName(), Main::getInstance()->getConfig()->getNested('tpSystem.tpa.cooldown', 15));
                    $player->sendMessage(str_replace("{player}", $sender->getName(), Main::$messages->getNested("tpa.recu")));
                    $sender->sendMessage(str_replace("{player}", $player->getName(), Main::$messages->getNested("tpa.send")));
                    self::$tpaRequests[$player->getName()] = $sender->getName();
                } else {
                    $sender->sendMessage(Main::$messages->getNested("tpa.not-online"));
                }
            } else {
                $sender->sendMessage(Main::$messages->getNested("tpa.not-found"));
            }
        }
    }

    public static function getTpaRequests(): array {
        return self::$tpaRequests;
    }

    public static function removeTpaRequest(string $receiver): void {
        unset(self::$tpaRequests[$receiver]);
    }
}