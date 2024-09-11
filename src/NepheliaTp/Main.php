<?php

namespace NepheliaTp;

use NepheliaTp\Command\TpacceptCommand;
use NepheliaTp\Command\TpaCommand;
use NepheliaTp\Command\TpahereCommand;
use NepheliaTp\Command\TpdenyCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase
{
    private static self $this;

    public static Config $messages;
    public static Config $commands;

    protected function onLoad(): void
    {
        $this->saveDefaultConfig();
    }
    protected function onEnable(): void
    {
        $this->saveResource("messages.yml");
        $this->saveResource("commandes.yml");
        self::$messages = new Config($this->getDataFolder() . 'messages.yml', Config::YAML);
        self::$commands = new Config($this->getDataFolder() . 'commandes.yml', Config::YAML);
        self::$this = $this;
        $this->getServer()->getCommandMap()->register("nephelia",new TpaCommand());
        $this->getServer()->getCommandMap()->register("nephelia",new TpahereCommand());
        $this->getServer()->getCommandMap()->register("nephelia",new TpacceptCommand());
        $this->getServer()->getCommandMap()->register("nephelia",new TpdenyCommand());
    }

    public static function getInstance(): self
    {
        return self::$this;
    }
}