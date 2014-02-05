<?php
pm_Context::init('example');

if (false !== ($upgrade = array_search('upgrade', $argv))) {
    $upgradeVersion = $argv[$upgrade + 1];
    echo "upgrading from version $upgradeVersion\n";

    if (version_compare($upgradeVersion, '1.2') < 0) {
        pm_Bootstrap::init();
        $id = pm_Bootstrap::getDbAdapter()->fetchOne("select val from misc where param = 'moduleCommunigateCustomButton'");
        pm_Bootstrap::getDbAdapter()->delete('misc', array("param = 'moduleCommunigateCustomButton'"));

        pm_Settings::set('customButtonId', $id);
    }

    echo "done\n";
    exit(0);
}

$iconPath = rtrim(pm_Context::getHtdocsDir(), '/') . '/images/icon_16.gif';
$baseUrl = pm_Context::getBaseUrl();

//  Adding a button to the panel


// $request = <<<APICALL
// <ui>
//   <create-custombutton>
//     <owner>
//       <admin/>
//     </owner>
//     <properties>
//       <public>true</public>
//       <internal>true</internal>
//       <place>navigation</place>
//       <url>/modules/example</url>
//       <text>CommuniGate</text>
//     </properties>
//   </create-custombutton>
// </ui>
// APICALL;

// try {
// $response = pm_ApiRpc::getService()->call($request);

// $result = $response->ui->{"create-custombutton"}->result;
//     if ('ok' == $result->status) {
//         pm_Settings::set('customButtonId', "12345");
//         echo "done\n";
//         exit(0);
//     } else {
//         echo "error $result->errcode: $result->errtext\n";
//         exit(1);
//     }

// } catch(PleskAPIParseException $e) {
//     echo $e->getMessage() . "\n";
//     exit(1);
// }
