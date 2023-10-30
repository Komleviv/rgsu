<?php
require_once __DIR__ . '\database.php';

class File
{
    public function xmlParseAndQuery(string $xmlFile) : bool
    {
        $xml = simplexml_load_file($xmlFile);

        $db = new DataBase();
        $ownerId = $db->lastIdQuery('owners');
        $breedId = $db->lastIdQuery('types_breeds');
        $petId = $db->lastIdQuery('pets');
        $rewardId = $db->lastIdQuery('rewards');

        foreach ($xml->User as $xmlUser) {
           $ownerName = $xmlUser->attributes()->Name;

            $db->dbQuery("INSERT INTO `owners` (`id`, `name`) VALUES ('$ownerId', '$ownerName[0]')");

           foreach ($xmlUser->Pets->Pet as $xmlPet) {
               $petCode = $xmlPet->attributes()->Code;
               $petType = $xmlPet->attributes()->Type;
               $petGender = $xmlPet->attributes()->Gender;
               $petAge = $xmlPet->attributes()->Age;
               $petNickname = $xmlPet->Nickname->attributes()->Value;
               $petBreed = $xmlPet->Breed->attributes()->Name;

               $gender = $this->genderSet($petGender[0]);

               $db->dbQuery("INSERT INTO types_breeds (id, type, breed_name) VALUES ('$breedId', '$petType[0]', '$petBreed[0]')");
               $db->dbQuery("INSERT INTO pets (id, code, gender, age, nickname, breed_name_id) VALUES ('$petId', '$petCode[0]', '$gender', '$petAge[0]', '$petNickname[0]', '$breedId')");
               $db->dbQuery("INSERT INTO owners_pets (owner_id, pet_id) VALUES ('$ownerId', '$petId')");

               if (isset($xmlPet->Rewards->Reward)) {
                   foreach ($xmlPet->Rewards->Reward as $xmlReward) {
                       $rewardName = $xmlReward->attributes()->Name;

                       $db->dbQuery("INSERT INTO rewards (id, name) VALUES ('$rewardId', '$rewardName[0]')");
                       $db->dbQuery("INSERT INTO pets_rewards (pet_id, reward_id) VALUES ('$petId', '$rewardId')");
                       $rewardId++;
                   }
               }

               if (isset($xmlPet->Parents->Parent)) {
                   foreach ($xmlPet->Parents->Parent as $xmlParent) {
                       $parentCode = $xmlParent->attributes()->Code;

                       if (!isset($parentQuery)) {
                           $parentQuery = "INSERT INTO pets_relatives (parent_code, children_code) VALUES ('$parentCode[0]', '$petCode[0]')";
                       } else {
                           $parentQuery .= ", ('$parentCode[0]', '$petCode[0]')";
                       }
                   }
               }

               $breedId++;
               $petId++;
           }
           $ownerId++;
        }
        $db->dbQuery($parentQuery);

        return true;
    }

    private function genderSet(SimpleXMLElement $petGender) : int
    {
        if ($petGender == 'm' || $petGender == 'м') {
            $gender = 1;
        } elseif ($petGender == 'w' || $petGender == 'ж') {
            $gender = 0;
        }

        return $gender;
    }
}