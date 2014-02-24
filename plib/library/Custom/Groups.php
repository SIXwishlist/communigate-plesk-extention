<?php
/*
*************** 
* This is a helper class for getting the necessary info for groups
***************
*/
class Modules_Communigate_Custom_Groups
{
	
    private $groups;
    private $domain;


    function __construct($domain)
    {
        $this->domain = $domain;
        $this->setGroups();
    }

    /**
     * A set method for groups varliable
     * if there are no groups it is an empty array
     */
    public function setGroups()
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $this->groups = $cli->ListGroups($this->domain);
    }

    /**
     * Method removing a member from certein group
     * @param  int $member the index of the member in the array
     * @param  string $group  the name of the group with domain added
     */
    public function removeMember($member, $group)
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();


        $members = $cli->GetGroup($group);
        $members = $members['Members'];

        unset($members[$member]);
        //Restore the indexes of the array after removing
        $members = array_values($members);
        $settings = $cli->GetGroup($group);
        $settings['Members'] = $members;
        $cli->SetGroup($group, $settings);
    }

    public function getDataForGroupMembers($group)
    {
        
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        // $cli->setDebug(1);
        $members = $cli->GetGroup($group);
        $members = $members['Members'];
        if ($members == 'NO') {
            $members = array();
        }
        return $members;

    }

    public function createGroup($groupName, $groupEmailAdress)
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();

       $settings = array(
            "Expand"=>"YES",
            "Members"=>array(),
            "RejectAutomatic"=>"YES",
            "RemoveAuthor"=>"YES",
            "RemoveToAndCc"=>"YES",
            "SetReplyTo"=>"YES",
            "EmailDisabled"=> "NO",
            "FinalDelivery"=> "NO",
            "SignalDisabled"=>"NO",
            "RealName"=>""
        );

        if ($groupName !== '') {
            $settings['RealName'] = $groupName;
        }
        $cli->CreateGroup("$groupEmailAdress", $settings);
        if ($cli->getWords() !== 'OK') {
            return false;
        } else {
            $cli->Logout();
            return true;
        }
    }



    public function deleteGroup($group)
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        // $cli->setDebug(1);
        $cli->DeleteGroup("$group");
        $cli->Logout();
    }

    public function renameGroup($domain, $oldGroupMail, $newGroupMail)
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        // $cli->setDebug(1);

        $cli->RenameGroup($oldGroupMail, $newGroupMail);
        if ($cli->getWords() !== 'OK') {
            return false;
        } else {
            $cli->Logout();
            return true;
        }
    }

    /**
     * Method for seting email archiving
     */
    public function changeSettingsForGroup($group, $newSettings, $domain, $realName)
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();

        $settings = $cli->GetGroup($group);
        
        foreach ($settings as $setting => $enabled) {
            if (is_array($newSettings) && in_array($setting, $newSettings)) {
                $settings[$setting] = 'YES';
            } else {
                $settings[$setting] = 'NO';
            }
        }

        $settings['RealName'] = $realName;

        $cli->SetGroup($group, $settings);

        $cli->Logout();
    }

    /**
     * Method adding a memeber to the group
     * @param string $group name of the group with domain added
     */
    public function addMember($group , $member)
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        // $cli->setDebug(1);
        $members = $cli->GetGroup($group);
        $members = $members['Members'];
        if ($members == 'NO') {
            $members = array();
        }
        array_push($members, $member);
        $settings = $cli->GetGroup($group);
        $settings['Members'] = $members;
        $cli->SetGroup($group, $settings);

    }


    /**
     * Method for getting the data for the grid view
     * @return array data for the grid view
     */
    public function getData()
    {
        if (!empty($this->groups)) {

            for ($i=0; $i < count($this->groups); $i++) { 
                
                $group = $this->groups[$i] . "@$this->domain";
                $data[$i] = (
                    array('id'=> $i,
                        'pagination'=>array(
                            'pageSize'=>5,),
                        'group'=> $group,
                        ));

            }

            return $data;
        } else {
            return array();
        }
    }
}