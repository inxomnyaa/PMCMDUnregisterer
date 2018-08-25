<?php

namespace xenialdan\PMCMDUnregisterer;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;

class Loader extends PluginBase
{

    public function onEnable()
    {
        $this->saveDefaultConfig();
        $this->getLogger()->info(join(",", $this->getConfig()->getAll()));

        $this->getScheduler()->scheduleRepeatingTask(new class($this) extends Task
        {
            /** @var Loader */
            private $plugin;

            public function __construct(Loader $plugin)
            {
                $this->plugin = $plugin;
            }

            /**
             * Actions to execute when run
             *
             * @param int $currentTick
             *
             * @return void
             */
            public function onRun(int $currentTick)
            {
                $cmdMap = $this->plugin->getServer()->getCommandMap();
                if (!empty($cmdMap->getCommands())) {
                    foreach ($this->plugin->getConfig()->getAll() as $command) {
                        $this->plugin->getLogger()->info("CMD:" . $command);
                        if ($command === "stop") continue;
                        if (!is_null($cmdMap->getCommand($command))) {
                            try {
                                $this->plugin->getServer()->getCommandMap()->getCommand($command)->unregister($this->plugin->getServer()->getCommandMap());
                                $this->plugin->getServer()->getCommandMap()->unregister($this->plugin->getServer()->getCommandMap()->getCommand($command));
                            } catch (\Error $e) {
                            }
                        } else {
                            $this->plugin->getLogger()->info($command . " was not found");
                        }
                    }
                    $this->plugin->getScheduler()->cancelTask($this->getHandler()->getTaskId());
                }
            }
        }, 1);
    }
}