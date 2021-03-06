<?php

/*
 *               _ _
 *         /\   | | |
 *        /  \  | | |_ __ _ _   _
 *       / /\ \ | | __/ _` | | | |
 *      / ____ \| | || (_| | |_| |
 *     /_/    \_|_|\__\__,_|\__, |
 *                           __/ |
 *                          |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author TuranicTeam
 * @link https://github.com/TuranicTeam/Altay
 *
 */

declare(strict_types=1);

namespace pocketmine\inventory\transaction\action;

use pocketmine\block\Block;
use pocketmine\inventory\AnvilInventory;
use pocketmine\item\Item;
use pocketmine\Player;

class AnvilResultAction extends InventoryAction{

    /** @var AnvilInventory */
    public $inventory;

    public function __construct(AnvilInventory $inventory, Item $sourceItem, Item $targetItem){
        parent::__construct($sourceItem, $targetItem);
        $this->inventory = $inventory;
    }

    public function isValid(Player $source): bool{
        return true;
    }

    public function execute(Player $source): bool{
        return $this->inventory->setItem(1, Item::get(Block::AIR), false);
    }

    public function onExecuteSuccess(Player $source) : void{
    }

    public function onExecuteFail(Player $source) : void{
    }

    public function getInventory(): AnvilInventory{
        return $this->inventory;
    }
}