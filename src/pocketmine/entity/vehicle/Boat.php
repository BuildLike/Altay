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

namespace pocketmine\entity\vehicle;

use pocketmine\entity\EntityIds;
use pocketmine\entity\Vehicle;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\SetEntityLinkPacket;
use pocketmine\network\mcpe\protocol\types\EntityLink;
use pocketmine\Player;

class Boat extends Vehicle{

    public const NETWORK_ID = EntityIds::BOAT;

    public const TAG_VARIANT = "Variant";

    public $height = 0.455;

    protected function initEntity(){
        $this->setGenericFlag(self::DATA_FLAG_STACKABLE, true);
        $this->setGenericFlag(self::DATA_FLAG_NO_AI, false);

        $this->setBoatType($this->namedtag->getInt(self::TAG_VARIANT, 0));

        parent::initEntity();
    }

    public function onInteract(Player $player, Item $item, Vector3 $clickVector, array $actions = []) : bool{
        $player->setGenericFlag(self::DATA_FLAG_RIDING);
        $player->vehicleEid = $this->getId();

        $metadata = [
            self::DATA_RIDER_SEAT_POSITION => [self::DATA_TYPE_VECTOR3F, new Vector3(0, 1.02001, 0)],
            self::DATA_RIDER_ROTATION_LOCKED => [self::DATA_TYPE_BYTE, 1],
            self::DATA_RIDER_MAX_ROTATION => [self::DATA_TYPE_FLOAT, 90],
            self::DATA_RIDER_MIN_ROTATION => [self::DATA_TYPE_FLOAT, -90]
        ];

        $player->sendData($player->getViewers(), $metadata);

        $pk = new SetEntityLinkPacket();
        $pk->link = new EntityLink($this->getId(), $player->getId(), EntityLink::TYPE_RIDE, false);
        $player->dataPacket($pk);

        return true;
    }

    public function getDrops(): array{
        return [
            ItemFactory::get(Item::BOAT, $this->getBoatType())
        ];
    }

    public function getBoatType() : int{
        return $this->propertyManager->getInt(self::DATA_VARIANT);
    }

    public function setBoatType(int $boatType) : void{
        $this->propertyManager->setInt(self::DATA_VARIANT, $boatType);
    }

    public function saveNBT(){
        parent::saveNBT();

        $this->namedtag->setInt(self::TAG_VARIANT, $this->getBoatType());
    }
}