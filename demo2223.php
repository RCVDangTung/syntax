<?php

defined('MOODLE_INTERNAL') || die;

/* _______________________________________________________________________ */
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/locallib.php');
require_once(dirname(__FILE__) . '/XmlLib.php');

class TpeBigBlueButton extends BigBlueButton {

    var $VIEWER = "VIEWER";
    var $MOD = "MODERATOR";
    var $TEACHER = "TEACHER";
    var $STUDENT = "STUDENT";
    var $ASSISTANT = "ASSITANCE_TEACHER";
    var $TpeSettings = Array();
    var $errorMove = false;
    var $flag = "";

    function __construct($SecuritySalt = null, $ServerBBBURL = null) {
        $settings = get_config('local_tpebbb');
        if ($SecuritySalt === null) {
            $SecuritySalt = $settings->SecuritySalt;
        }
        if ($ServerBBBURL === null) {
            $ServerBBBURL = $settings->ServerBBBURL;
        }
        /**
         * TungTB: 10.12.2015
         * Danh sach UserAdobe
         */
        global $USER, $DB;
        $dbman = $DB->get_manager();
        if ($dbman->table_exists('useradobe')) {
            $arrUserAdobe = array();
            $dataUserAdobe = $DB->get_records('useradobe', array('status_yn' => 'Y'));
            foreach ($dataUserAdobe as $key => $user) {
                $arrUserAdobe[] = intval($user->user_id);
            }
            if (in_array(intval($USER->id), $arrUserAdobe)) {
                $ServerBBBURL = "http://210.211.96.110/bigbluebutton/";
            }
        }
        //End TungTB
        $this->_securitySalt = $SecuritySalt;
        $this->_bbbServerBaseUrl = $ServerBBBURL;
        $this->TpeSettings = $settings;
        $this->processConfigValue();
        parent::__construct($SecuritySalt, $ServerBBBURL);
    }

    public function processConfigValue() {
        $listKeyConfig = Array("ListRoomLevel", "ListFromValue",
            "ListClassType", "VcrRole", "ChangerRoomType",
            "TeacherTypeMapping", "AutoMoveMapping", "PriorityPackage",
            "TechRoomGroup", "TechRoomPackageMapping",
            "LangConfigMapping", "VcrFunction", "ListMarkUser", "OtherSysFunction");
        foreach ($listKeyConfig as $name) {
            $settingsString = $this->TpeSettings->$name;
            $semicolonSalt = '@semicolon@';
            $colonSalt = '@colon@';
            $settingsString = str_replace("\;", $semicolonSalt, $settingsString);
            $settingsString = str_replace("\:", $colonSalt, $settingsString);
            $settingsString = preg_replace("/\s+/", " ", $settingsString);
            $settingsString = preg_replace("/[\r\n]+/", ";", $settingsString);
            $settingsString = preg_replace("/[\r\n]*;+/", ";", $settingsString);
            $settingsString = preg_replace("/;\s+/", ";", $settingsString);
            $settingsString = preg_replace("/[;]+/", ";", $settingsString);
            $settingsString = preg_replace("/[\$\^];+/", "", $settingsString);

            $settingData = explode(";", $settingsString);
            $TpeSettings = Array();
            foreach ($settingData as $item) {
                $item = trim($item);
                if (strlen($item) == 0) {
                    continue;
                }
                $itemData = explode(":", $item);
                if (count($itemData) == 1) {
                    $value = trim($itemData[0]);
                } elseif (count($itemData) == 2) {
                    $value = trim($itemData[1]);
                } else {
                    $this->error("invalid data config: local_tpebbb/" . $name);
                }
                $value = str_replace($colonSalt, ":", $value);
                $value = str_replace($semicolonSalt, ";", $value);
                $TpeSettings[trim($itemData[0])] = trim($value);
            }
            $this->TpeSettings->$name = $TpeSettings;
        }
    }

    //TPE define String
    public function getTpeRoomKey($arrayKey = false) {
        $TECH_TEST = "TECHTEST";
        $TECH_TEST_TEACHER = "TECHTESTTEACHER";
        $TECH_SP = "TECHSUPPORT";
        $TECH_SPNB = "TECHSUPPORTNEWBIE";
        if ($arrayKey) {
            return Array($TECH_TEST, $TECH_TEST_TEACHER, $TECH_SP, $TECH_SPNB);
        } else {
            return Array(
                $TECH_TEST => "TechTest Room",
                $TECH_TEST_TEACHER => "TechTestTeacher Room",
                $TECH_SP => "TechSupport Room",
                $TECH_SPNB => "TechSupportNewbie Room"
            );
        }
    }

    public function getTpeFromKey($arrayKey = false) {
        $allFrom = $this->TpeSettings->ListFromValue;
        if ($arrayKey) {
            $allFromKey = array_keys($allFrom);
            return $allFromKey;
        } else {
            return $allFrom;
        }
    }

    public function getTpeClassTypeKey($arrayKey = false) {
        $allFrom = $this->TpeSettings->ListClassType;
        if ($arrayKey) {
            $allFromKey = array_keys($allFrom);
            return $allFromKey;
        } else {
            return $allFrom;
        }
    }

    public function getTpeUserLv($arrayKey = false) {
        $allFrom = $this->TpeSettings->ListRoomLevel;
        if ($arrayKey) {
            $allFromKey = array_keys($allFrom);
            return $allFromKey;
        } else {
            return $allFrom;
        }
    }

    //TPE addition api
    public function customApiUrl($api, $params, $apiFolder = "api") {
        $urlParams = Array();
        if ($this->flag != "") {
            $this->_bbbServerBaseUrl = $this->flag;
        }
        $creationUrl = $this->_bbbServerBaseUrl . $apiFolder . "/" . $api . "?";
        foreach ($params as $key => $value) {
            $urlParams[] = $key . "=" . urlencode($value);
        }
        $paramString = implode("&", $urlParams);
        return ( $creationUrl . $paramString . '&checksum=' . sha1($api . $paramString . $this->_securitySalt) );
    }

    public function customApiRequest($api, $params, $apiFolder = 'api') {
        $url = $this->customApiUrl($api, $params, $apiFolder);
        $data = $this->_processXmlResponse($url);
        return $data;
    }

    public function makeCreatParam($BBBInfo, $CFG = null) {
        global $DB;
        if ($CFG == null) {
            global $CFG;
        }
        $dataReturn = Array();
        $defaultParams = array(
            'meetingName' => 'Meeting Name', //A name for the meeting (or username)
            'meetingId' => '1234', //A unique id for the meeting
            'attendeePw' => 'ap', //Set to 'ap' and use 'ap' to join = no user pass required.
            'moderatorPw' => 'mp', //Set to 'mp' and use 'mp' to join = no user pass required.
            'welcomeMsg' => 'Welcome to Topmito\'s Virtual-Class-Room.', //'' = use default. Change to customize.
            'dialNumber' => '', //The main number to call into. Optional.
            'voiceBridge' => '20512', //5 digit PIN to join voice conference. Required.
            'webVoice' => '', //Alphanumeric to join voice. Optional.
            'logoutUrl' => '', //Default in bigbluebutton.properties. Optional.
            'maxParticipants' => '-1', //Optional. -1 = unlimitted. Not supported in BBB. [number]
            'record' => 'false', //New. 'true' will tell BBB to record the meeting.
            'duration' => '0', //Default = 0 which means no set duration in minutes. [number]
            'meta_category' => '', //Use to pass additional info to BBB server. See API docs to enable.
        );
        foreach ($defaultParams as $key => $value) {
            $dataReturn[$key] = $value;
        }
        //Custom Params
        $dataReturn["meetingName"] = $BBBInfo->name;
        $dataReturn["meetingId"] = $BBBInfo->id;
        $dataReturn["attendeePw"] = $BBBInfo->viewerpass;
        $dataReturn["moderatorPw"] = $BBBInfo->moderatorpass;
        if ($BBBInfo->voicebridge) {
            $dataReturn["voiceBridge"] = $BBBInfo->voicebridge;
        }
        if ($BBBInfo->roomtype === 'ROOM') {
            $timeDuration = intval($this->TpeSettings->DefaultTimeOpen);
            $dataReturn["duration"] = intval($timeDuration * 2) + $this->TpeSettings->DefaultTimeOpenBefore;
        }
        $dataReturn["logoutUrl"] = $CFG->wwwroot;
        $dataReturn["record"] = $BBBInfo->record;
        $dataReturn["custom-roomtype"] = $BBBInfo->roomtype;
        $dataReturn["custom-roomGroupOrigin"] = strtoupper($BBBInfo->techroomgroup);
        if ($BBBInfo->calendar_code) {
            $calendarInfo = $DB->get_record('tpe_calendar_teach', array('calendar_code' => $BBBInfo->calendar_code), '*');
            if ($calendarInfo) {
                $dataReturn["custom-teachertype"] = $calendarInfo->teacher_type;
                $dataReturn["custom-studenttype"] = $calendarInfo->student_type;
            }
        }
        return $dataReturn;
    }

    /**
     *
     * @param type $userId          Id User cần kiểm tra
     * @param type $meetingId       Id lớp học
     * @param type $meetingDetail   Thông tin lấy từ API BBB, nếu null sẽ lấy theo $meetingId
     * @return boolean
     */
    public function checkBookSlot($userId, $meetingId, $meetingDetail = null) {
        if (!$meetingDetail) {
            $meetingDetail = $this->getMeetingDetailInfo($meetingId);
        }
        if ($meetingDetail["meetingExists"] == "SUCCESS") {
            foreach ($meetingDetail['waitingUsers'] as $userItem) {
                if ($userItem["userExternalId"] == $userId) {
                    return true;
                }
            }
            foreach ($meetingDetail['joinedUsers'] as $userItem) {
                if ($userItem["userExternalId"] == $userId) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getClassLevel($meetingId) {
        global $DB;
        $sql = "SELECT level_class "
                . "FROM {tpebbb} as b "
                . "JOIN {tpe_calendar_teach} as t ON t.calendar_code=b.calendar_code "
                . "WHERE b.id = ?";
        $params = Array($meetingId);
        $slideInfo = $DB->get_record_sql($sql, $params);
        if ($slideInfo) {
            return $slideInfo->level_class;
        }
        return "UNKNOW";
    }

    public function getJoinParamWithMeetingId($meetingId, $classType, $joinParam, $courseid, $context, $DB = null, $CFG = null) {

        if ($DB == null || $CFG == null) {
            global $CFG, $DB;
        }
        //datnv
        $classRoomDetail = $DB->get_record('tpebbb', array('id' => $meetingId), '*', MUST_EXIST);
        if ($classRoomDetail->roomtype != 'ROOM')
            $this->_bbbServerBaseUrl = $CFG->NAF2;
        //end datnv

        $TECH_ROOM = $this->getTpeRoomKey(true);
        $LIST_CLASSTYPE = $this->getTpeClassTypeKey(true);
        $LIST_FROM = $this->getTpeFromKey(true);
        list($TECH_TEST, $TECH_TEST_TEACHER, $TECH_SP, $TECH_SPNB) = $TECH_ROOM;
        $BBBInfo = $DB->get_record('tpebbb', array('id' => $meetingId, 'course' => $courseid), '*', MUST_EXIST);
        $roomType = strtoupper($BBBInfo->roomtype);
        $joinParam["userdata-roomTypeOrigin"] = $roomType;
        $joinParam["userdata-roomGroupOrigin"] = strtoupper($BBBInfo->techroomgroup);
        if (!in_array($roomType, $TECH_ROOM)) {
            $roomType = "ROOM";
        } else if ($roomType == $TECH_TEST_TEACHER) {
            $roomType = $TECH_TEST;
        } else if ($roomType == $TECH_SPNB) {
            $roomType = $TECH_SP;
        }
        if ($roomType == "ROOM") {
            $joinParam["userdata-subjectCode"] = $BBBInfo->subject_code;
        }
        $joinParam["userdata-roomType"] = $roomType;
        $joinParam["created"] = false;
        $meetingVcrInfo = $this->getMeetingDetailInfo($meetingId);

        $isCreated = isset($meetingVcrInfo["meetingExists"]) && $meetingVcrInfo["meetingExists"] == 'SUCCESS';
        if (!$isCreated) {

            $paramCreat = $this->makeCreatParam($BBBInfo, $CFG);
            if ((in_array($roomType, $TECH_ROOM) && has_capability("local/tpebbb:techroomcreat", $context)) || //Là mod và có quyền tạo lớp Tech
                    (!in_array($roomType, $TECH_ROOM) && has_capability("local/tpebbb:classcreat", $context)) //Là mod và có quyền tạo lớp học
            ) {
                //Tạo lớp
                $xml = $this->uploadSildeXml($BBBInfo);
                $result = $this->createMeetingWithXmlResponseArray($paramCreat, $xml);
                if ($result["returncode"] == "SUCCESS") {
                    $isCreated = true;
                    $joinParam["created"] = true;
                }
            } else {
                $this->redirect($CFG->wwwroot, get_string('meeting_not_creat', 'local_tpebbb'));
                exit;
            }
        }
        if (!$isCreated) {
            $this->error("VCR SERVER DOWN");
        }
        if (in_array($roomType, $TECH_ROOM)) {
            if (has_capability("local/tpebbb:techroommod", $context)) {
                $password = $BBBInfo->moderatorpass;
                $role = $this->MOD;
            } else if (has_capability("local/tpebbb:techroomview", $context)) {//Vào lớp TechTest/TechSupport
                if (!in_array($classType, $LIST_CLASSTYPE)) {
                    $this->error("ClassType undified: " . $classType);
                }
                if (!in_array($joinParam["userdata-from"], $LIST_FROM)) {
                    $this->error("UserFrom undified: " . $joinParam["userdata-from"]);
                }

                $password = $BBBInfo->viewerpass;
                $role = $this->VIEWER;
            } else {
                //require_capability("local/tpebbb:techroomview", $context);
                if (!has_capability("local/tpebbb:techroomview", $context)) {
                    $this->error("Require capability : local/tpebbb:techroomview");
                }
            }
        } else {
            if (has_capability("local/tpebbb:classmod", $context)) {
                $password = $BBBInfo->moderatorpass;
                $role = $this->MOD;
            } else if (has_capability("local/tpebbb:classview", $context)) {
                $password = $BBBInfo->viewerpass;
                $role = $this->VIEWER;
            } else {
//                require_capability("local/tpebbb:classview", $context);
                if (!has_capability("local/tpebbb:classview", $context)) {
                    $this->error("Require capability : local/tpebbb:classview");
                }
            }
        }
        $joinParam["password"] = $password;
        $joinParam["role"] = $role;
        $joinParam["meetingId"] = $meetingId;
        $joinParam["vcrInfo"] = $meetingVcrInfo;
        $joinParam["userdata-startTime"] = $BBBInfo->timeavailable;
        return $joinParam;
    }

    public function uploadSildeXml($bbbInfo) {
        global $DB, $CFG;
        $xml = "";
        $TECH_ROOM = $this->getTpeRoomKey(true);
        list($TECH_TEST, $TECH_TEST_TEACHER, $TECH_SP, $TECH_SPNB) = $TECH_ROOM;
        $roomType = strtoupper($bbbInfo->roomtype);
        $techroomgroup = isset($bbbInfo->techroomgroup) ? strtoupper($bbbInfo->techroomgroup) : '';
        if ($techroomgroup != "THAI" && ($roomType == $TECH_SP || $roomType == $TECH_SPNB || ($roomType == $TECH_TEST_TEACHER && $techroomgroup == 'VN'))) {

            $slideUrl = $CFG->wwwroot . '/local/tpebbb/externalfile/slide/default_techroom.png';
            $xml = "<?xml version='1.0' encoding='UTF-8'?> "
                    . "<modules>"
                    . "  <module name='presentation'> "
                    . "    <document url='" . $slideUrl . "' /> "
                    . "  </module>"
                    . "</modules>";
        } else {
            $sql = "SELECT fileurl,class_outline "
                    . "FROM {tpebbb} as b "
                    . "JOIN {materialservice} as s ON s.subject_code=b.subject_code "
                    . "WHERE b.id = ? LIMIT 1";
            $params = Array($bbbInfo->id);
            $slideInfo = $DB->get_record_sql($sql, $params);
            if ($slideInfo && strlen($slideInfo->class_outline)) {
                $slideUrl = $slideInfo->fileurl . $slideInfo->class_outline;
                $xml = "<?xml version='1.0' encoding='UTF-8'?> "
                        . "<modules>"
                        . "  <module name='presentation'> "
                        . "    <document url='" . $slideUrl . "' /> "
                        . "  </module>"
                        . "</modules>";
            }
        }
        return $xml;
    }

    public function getRecentRoom($userId = null) {
        global $DB;
        if ($userId === null) {
            global $USER;
            $userId = $USER->id;
        }
        $timeAv = $this->getAvailableTime();

        $sql = "SELECT b.id,b.name as title, b.calendar_code,b.vcr_type,b.vcr_class_id "
                . ", t.type_class as type, CONCAT(u.firstname,' ', u.lastname) as teacher "
                . "FROM {logsservice_move_user} AS mv "
                . "JOIN {tpebbb} as b ON mv.roomidto=b.id "
                . "LEFT JOIN {tpe_calendar_teach} as t ON t.calendar_code=b.calendar_code "
                . "LEFT JOIN {user} as u ON u.id=t.teacher_id "
                . "WHERE b.roomtype = 'Room' AND b.timedue > ? AND b.timeavailable < ? "
                . "AND mv.userid = ?"
                . "ORDER BY mv.id DESC LIMIT 1";

        $params = Array($timeAv["time"], $timeAv["maxTime"], $userId);
        $recentRoom = $DB->get_record_sql($sql, $params);
        return $recentRoom;
    }

    public function bookSlotWithMeetingId($userId, $meetingId, $maxUser = 0, $context = null) {
        global $DB;
        if (!$context) {
            global $CFG;
            $courseid = $CFG->tpe_config->courseid;
            $context = context_course::instance($courseid);
        }
        $meetingInfo = $DB->get_record('tpebbb', Array("id" => $meetingId));
        if (!$meetingInfo) {
            $this->error("Meeting not exit");
        }
        $roomType = $meetingInfo->roomtype;
        return $this->bookSlotWithAutoRole($userId, $meetingId, $roomType, $maxUser, $context);
    }

    public function bookSlotWithAutoRole($userId, $meetingId, $roomType, $maxUser = 0, $context = null) {

        if (!$context) {
            global $CFG;
            $courseid = $CFG->tpe_config->courseid;
            $context = context_course::instance($courseid);
        }

        if (in_array($roomType, $this->getTpeRoomKey(true))) { //Là phòng kỹ thuật, ai cũng có thể tự book chỗ
            if (has_capability("local/tpebbb:techroommod", $context, $userId)) {
                $role = $this->MOD;
            } else {
                $role = $this->VIEWER;
            }
        } else {
            if (has_capability("local/tpebbb:classmod", $context, $userId)) {
                $role = $this->MOD;
            } else {
                $role = $this->VIEWER;
            }
        }

        return $this->bookSlotProcess($userId, $meetingId, $roomType, $maxUser, $context, $role);
    }

    public function bookSlotProcess($userId, $meetingId, $roomType, $maxUser = 0, $context = null, $role = "VIEWER") {
        global $CFG;
        if ($roomType != 'ROOM')
            $this->_bbbServerBaseUrl = $CFG->NAF2;
        if (!$context) {
            global $CFG;
            $courseid = $CFG->tpe_config->courseid;
            $context = context_course::instance($courseid);
        }
        if (in_array($roomType, $this->getTpeRoomKey(true))) { //Là phòng kỹ thuật, ai cũng có thể tự book chỗ
            return $this->bookSlot($userId, $meetingId, 0, $role);
        } else {
            if (has_capability("local/tpebbb:bookslot", $context)) {
                return $this->bookSlot($userId, $meetingId, $maxUser, $role);
            }
        }
        return false;
    }

    protected function bookSlot($userId, $meetingId, $maxUser = 0, $role = "VIEWER") {
        $api = 'bookSlot';
        $apiF = 'tpeapi';
        if ($role == $this->MOD) {
            $maxUser = 0;
        }
        $param = array(
            'externalUserId' => $userId,
            'meetingId' => $meetingId,
            'maxUser' => $maxUser,
            'role' => $role, //MODERATOR
        );
        try {
            $dataResponse = $this->customApiRequest($api, $param, $apiF);
            //0: ok
            //1: data invalid
            //2: Meeting not exit
            //3: Already book
            //4: Full
            if ($dataResponse["returncode"] == "SUCCESS") {
                if (isset($dataResponse["status"])) {
                    return $dataResponse["status"];
                } else {
                    return -2;
                }
            } else {
//                $this->error("Get Info Failed: " . $dataResponse["message"]);
                $this->errorMove = $dataResponse["message"];
                return -2;
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function getMeetingsDetailInfo($listRoom) {
        global $DB, $CFG;
        //datnv
        foreach ($listRoom as $key => $value) {
            //echo "<pre>";print_r($value);
            if ($value->roomtype == null) {
                $classRoomDetail = $DB->get_record('tpebbb', array('id' => $value->id), 'roomtype', MUST_EXIST);
            }
            if ($classRoomDetail->roomtype == 'ROOM') {
                break;
            }
            if ($value->roomtype != 'ROOM') {
                $this->flag = $CFG->NAF2;
                break;
            } else {
                $this->flag = '';
            }
        }
        //echo $this->_bbbServerBaseUrl."<br>Flag=".$this->flag;die;
        //datnv
        if (count($listRoom) == 0) {
            return Array();
        }
        $listId = Array();
        foreach ($listRoom as $item) {
            $listId[] = $item->id;
        }
        $api = 'getMeetingInfos';
        $apiF = 'tpeapi';
        $param = array('meetingIds' => join(",", $listId));
        try {
            $dataResponse = $this->customApiRequest($api, $param, $apiF);
            if ($dataResponse["returncode"] == "SUCCESS") {
                $dataReturn = Array();
                $meetingsInfo = $dataResponse['meetingInfo'];
                if (count($listRoom) == 1) {
                    $meetingsInfo = Array($meetingsInfo);
                }
                foreach ($meetingsInfo as $value) {
                    $fixInfo = $this->processMeetingInfo($value);
                    $dataReturn[$value["meetingId"]] = $fixInfo;
                }
            } else {
                $dataReturn = Array();
                foreach ($listRoom as $item) {
                    $dataReturn[$item->id]["userInfoText"] = "SERVER VCR DOWN";
                }
            }
        } catch (Exception $e) {
            $dataReturn = Array();
            foreach ($listRoom as $item) {
                $dataReturn[$item->id]["userInfoText"] = $e->getMessage();
            }
        }
        return $dataReturn;
    }

    public function processMeetingInfo($meetingInfo) {
        if ($meetingInfo["meetingExists"] == "SUCCESS") {
            $userType = Array("liveUsers", "joinedUsers", "waitingUsers");
            foreach ($userType as $type) {
                if (is_array($meetingInfo[$type])) { //Có user
                    if (isset($meetingInfo[$type]['user']['userExternalId'])) {//Có 1 user
                        $meetingInfo[$type] = Array($meetingInfo[$type]['user']);
                    } else { //Có nhiều user
                        //Dữ liệu đã đúng dạng mảng
                        $meetingInfo[$type] = array_values($meetingInfo[$type]['user']);
                    }
                } else { //Không có user
                    $meetingInfo[$type] = Array();
                }
            }
        }
        $meetingInfo = $this->getMeetingUserInfoText($meetingInfo);
        return $meetingInfo;
    }

    public function getMeetingUserInfoText($meetingInfo) {
        $keyList = Array('waitingUsers', 'joinedUsers', 'liveUsers');
        foreach ($keyList as $key) {
            $modKey = $key . 'Mod';
            $viewKey = $key . 'View';
            $meetingInfo[$modKey] = false;
            $meetingInfo[$viewKey] = false;
        }
        if (!isset($meetingInfo['meetingExists'])) {
            $meetingInfo["userInfoText"] = "DATA_RESPONSE_ERROR";
        } else if ($meetingInfo['meetingExists'] == "FAILED") {
            $meetingInfo["userInfoText"] = "NOT_OPEN";
        } else if ($meetingInfo['meetingExists'] == "SUCCESS") {
            $meetingInfo["teacherReal"] = "";
            $meetingInfo["assistantReal"] = "";
            foreach ($keyList as $key) {
                $modKey = $key . 'Mod';
                $viewKey = $key . 'View';
                $meetingInfo[$modKey] = Array();
                $meetingInfo[$viewKey] = Array();
                foreach ($meetingInfo[$key] as $item) {
                    if ($item["role"] == $this->VIEWER) {
                        $meetingInfo[$viewKey][] = $item;
                    } else {
                        $meetingInfo[$modKey][] = $item;
                    }
                }
            }
            foreach ($meetingInfo['liveUsers'] as $item) {
                if ($item["role"] == $this->MOD) {
                    $name = $item['fullname'];
                    $namePart = explode("-", $name);
                    $role = end($namePart);
                    if ($role == 'teacher') {
                        $meetingInfo["teacherReal"] = $name;
                    } else if ($role == 'assistant') {
                        $meetingInfo["assistantReal"] = $name;
                    }
                }
            }
            $meetingInfo["userInfoText"] = count($meetingInfo['joinedUsersView']) . "-" . count($meetingInfo['liveUsersView']);
        } else {
            $meetingInfo["userInfoText"] = "DATA_RESPONSE_INVALID_STRUCT";
        }
        return $meetingInfo;
    }

    public function getMeetingDetailInfo($meetingId) {
        global $DB, $CFG;
        $classRoomDetail = $DB->get_record('tpebbb', array('id' => $meetingId), 'roomtype', MUST_EXIST);
        if ($classRoomDetail->roomtype == 'ROOM')
            $this->_bbbServerBaseUrl = $CFG->NAF1;
        $roomInfo = new stdClass();
        $roomInfo->id = $meetingId;
        $listRoom = Array($roomInfo);
        $meetingsInfo = $this->getMeetingsDetailInfo($listRoom);
        return $meetingsInfo[$meetingId];
    }

    public function getMeetingsInfoCount($listRoom) {
        global $CFG;
        if (count($listRoom) == 0) {
            return Array();
        }
        $listId = Array();
        if ($listRoom[0]) {
            if ($listRoom[0]->roomtype != "ROOM") {
                $this->_bbbServerBaseUrl = $CFG->NAF2;
            }
        }

        foreach ($listRoom as $item) {
            $listId[] = $item->id;
        }
        $api = 'getInfoForMove';
        $apiF = 'tpeapi';
        $param = array('meetingIds' => join(",", $listId));
        try {
            $dataResponse = $this->customApiRequest($api, $param, $apiF);
            if ($dataResponse["returncode"] == "SUCCESS") {
                $dataReturn = Array();
                $meetingsInfo = $dataResponse['meetingInfo'];
                if (count($listRoom) == 1) {
                    $meetingsInfo = Array($meetingsInfo);
                }
                foreach ($meetingsInfo as $value) {
                    $processMeeting = $this->_processMeetingCount($value);
                    $value['userInfoText'] = isset($processMeeting['userInfoText']) ? $processMeeting['userInfoText'] : '0-0';
                    $dataReturn[$value["meetingId"]] = $value;
                }
            } else {
                $dataReturn = Array();
                foreach ($listRoom as $item) {
                    $dataReturn[$item->id]["userInfoText"] = "SERVER VCR DOWN";
                }
            }
        } catch (Exception $e) {
            $dataReturn = Array();
            foreach ($listRoom as $item) {
                $dataReturn[$item->id]["userInfoText"] = $e->getMessage();
            }
        }
        return $dataReturn;
    }

    public function getMeetingsInfoCountRecent($listRoom) {
        if (count($listRoom) == 0) {
            return Array();
        }
        $listId = Array();
        foreach ($listRoom as $item) {
            $listId[] = $item->id;
        }
        $api = 'getInfoForMoveRecent';
        $apiF = 'tpeapi';
        $param = array('meetingIds' => join(",", $listId));
        try {
            $dataResponse = $this->customApiRequest($api, $param, $apiF);
            if ($dataResponse["returncode"] == "SUCCESS") {
                $dataReturn = Array();
                $meetingsInfo = $dataResponse['meetingInfo'];
                if (count($listRoom) == 1) {
                    $meetingsInfo = Array($meetingsInfo);
                }
                foreach ($meetingsInfo as $value) {
                    $processMeeting = $this->_processMeetingCount($value);
                    $value['userInfoText'] = isset($processMeeting['userInfoText']) ? $processMeeting['userInfoText'] : '0-0';
                    $dataReturn[$value["meetingId"]] = $value;
                }
            } else {
                $dataReturn = Array();
                foreach ($listRoom as $item) {
                    $dataReturn[$item->id]["userInfoText"] = "SERVER VCR DOWN";
                }
            }
        } catch (Exception $e) {
            $dataReturn = Array();
            foreach ($listRoom as $item) {
                $dataReturn[$item->id]["userInfoText"] = $e->getMessage();
            }
        }
        return $dataReturn;
    }

    /**
     * Hàm xử lý thông tin lớp học lấy từ vcr (dữ liệu count - cho chuyển lớp)
      if($classRoomDetail->roomtype == 'ROOM')
      {
      break;
      }
      if($value->roomtype != 'ROOM'){
      $this->flag = $CFG->NAF2;
      break;
      }else{
      $this->flag = '';
      }
     */
    public function _processMeetingCount($meetingInfo) {
        if (!isset($meetingInfo['meetingExists'])) {
            $meetingInfo["userInfoText"] = "DATA_RESPONSE_ERROR";
        } else if ($meetingInfo['meetingExists'] == "FAILED") {
            $meetingInfo["userInfoText"] = "NOT_OPEN";
        } else if ($meetingInfo['meetingExists'] == "SUCCESS") {
            $joinedUsersView = isset($meetingInfo['joinedUsersView']) ? $meetingInfo['joinedUsersView'] : '0';
            $liveUsersView = isset($meetingInfo['liveUsersView']) ? $meetingInfo['liveUsersView'] : '0';
            $meetingInfo["userInfoText"] = $joinedUsersView . "-" . $liveUsersView;
        } else {
            $meetingInfo["userInfoText"] = "DATA_RESPONSE_INVALID_STRUCT";
        }
        return $meetingInfo;
    }

    /**
     *
     * @global type $CFG
     * @param type $from Den tu dau? LMS|TechTest|TechTestTeacher|TechSupport|TechSupportNewbie
     * @param type $classType Loai lop hoc muon vao: LS|SC|OTHER
     * @param type $meettingId: id lop muon vao
     * @param type $roomType : Loai phong muon vao TECHTEST|TECHTESTTECHER|TECHSUPPORT|TechSupportNewbie
     * @return type
     */
    public function getLinkVcr($from, $classType, $meettingId = 0, $roomType = '') {
        global $CFG;
        $from = strtoupper($from);
        $classType = strtoupper($classType);
        $roomType = strtoupper($roomType);
        $roomTypeList = $this->getTpeRoomKey(true);
        $fromList = $this->getTpeFromKey(true);
        $classTypeList = $this->getTpeClassTypeKey(true);
        if (!in_array($from, $fromList)) {
            $this->error("FROM value not in list: '" . $from . "' not in (" . join("|", $fromList) . ")");
        }

        if (!in_array($classType, $classTypeList)) {
            $this->error("ClassType value not in list: '" . $classType . "' not in (" . join("|", $classTypeList) . ")");
        }
        if (!$meettingId && strlen($roomType) == 0) {
            $this->error("MeetingId and RoomType are not empty both");
        }
        if ($meettingId) {
            return $CFG->wwwroot . '/local/tpebbb/process.php?meetingId=' . $meettingId . "&from=" . $from . "&classType=" . $classType;
        } else if (strlen($roomType) > 0) {
            if (!in_array($roomType, $roomTypeList)) {
                $this->error("RoomType value not in list: '" . $roomType . "' not in (" . join("|", $roomTypeList) . ")");
            } else {
                return $CFG->wwwroot . '/local/tpebbb/process.php?roomType=' . $roomType . "&from=" . $from . "&classType=" . $classType;
            }
        }
    }

    public function getAvailableTime($time = 0) {
        $time = intval($time);
        if ($time == 0) {
            $time = time();
        }
        $minTime = $time - $this->TpeSettings->DefaultTimeOpen * 60; //Lấy về phía trước bằng với thời gian mở lớp
        $maxTime = $time + $this->TpeSettings->DefaultTimeOpenBefore * 60;
        return Array(
            "minTime" => $minTime,
            "time" => $time,
            "maxTime" => $maxTime
        );
    }

    public function getMeetingInTime($time = 0, $context) {
        global $USER, $DB;
        require_capability("local/tpebbb:viewroominfo", $context);

        //begin checkpoint
        $user_id = $USER->id;
        $group = 'MOVEUSERMANUAL';
        $action = 'BEGIN_MOVEUSERMANUAL_APIGETMEETING_GETMEETINGINTIME_QUERY';
        $user_name = $USER->username;
        $ip = $_SERVER['REMOTE_ADDR'];
        $BEGIN_MOVEUSERMANUAL_APIGETMEETING_GETMEETINGINTIME_QUERY = microtime(true);
        checkpoint_native($user_id, $group, $action, $user_name, $ip);

        $timeAv = $this->getAvailableTime($time);
        $sql = "SELECT b.id,b.name as title,c.level_class as level,b.vcr_type, b.calendar_code, c.teacher_type as teacher_type, c.student_type as student_type, "
                . "c.type_class as type, CONCAT(u.firstname,' ', u.lastname) as teacher,u.id as teacher_id, "
                . "CONCAT(u2.firstname,' ', u2.lastname) as assistant, u2.id as assistant_id, b.timeavailable "
                . "FROM {tpebbb} as b "
                . "LEFT JOIN {tpe_calendar_teach} as c ON c.calendar_code=b.calendar_code "
                . "LEFT JOIN {user} as u ON c.teacher_id=u.id "
                . "LEFT JOIN {user} as u2 ON c.assistant_id=u2.id "
                . "WHERE b.roomtype = 'Room' AND b.timedue > ? "
                . "AND b.timeavailable < ? AND c.status > 0 ";
        $params = Array($timeAv["time"], $timeAv["maxTime"]);
        $listRoom = $DB->get_records_sql($sql, $params);
        //End checkpoint
        $user_id = $USER->id;
        $group = 'MOVEUSERMANUAL';
        $action = 'END_MOVEUSERMANUAL_APIGETMEETING_GETMEETINGINTIME_QUERY';
        $user_name = $USER->username;
        $ip = $_SERVER['REMOTE_ADDR'];
        checkpoint_native($user_id, $group, $action, $user_name, $ip, $BEGIN_MOVEUSERMANUAL_APIGETMEETING_GETMEETINGINTIME_QUERY);

        //Begin checkpoint
        $user_id = $USER->id;
        $group = 'MOVEUSERMANUAL';
        $action = 'BEGIN_MOVEUSERMANUAL_APIGETMEETING_GETMEETINGINTIME_getMeetingsDetailInfo';
        $user_name = $USER->username;
        $ip = $_SERVER['REMOTE_ADDR'];
        $BEGIN_MOVEUSERMANUAL_APIGETMEETING_GETMEETINGINTIME_getMeetingsDetailInfo = microtime(true);
        checkpoint_native($user_id, $group, $action, $user_name, $ip);

        $listRoomDetail = $this->getMeetingsDetailInfo($listRoom);
        foreach ($listRoom as $key => $value) {
            $listRoom[$key]->teacherType = $listRoom[$key]->teacher_type;
            $listRoom[$key]->studentType = $listRoom[$key]->student_type;
            if ($listRoom[$key]->vcr_type != '')
                $listRoom[$key]->type = $listRoom[$key]->type . '-' . $listRoom[$key]->vcr_type;
            $listRoom[$key]->studentNumber = $listRoomDetail[$key]['userInfoText'];
            $listRoom[$key]->joinedUsersView = count($listRoomDetail[$key]['joinedUsersView']);
            $listRoom[$key]->liveUsersView = count($listRoomDetail[$key]['liveUsersView']);
            $listRoom[$key]->joinedUsersMod = count($listRoomDetail[$key]['joinedUsersMod']);
            $listRoom[$key]->liveUsersMod = count($listRoomDetail[$key]['liveUsersMod']);
            $listRoom[$key]->teacherReal = $listRoomDetail[$key]['teacherReal'];
            $listRoom[$key]->assistantReal = $listRoomDetail[$key]['assistantReal'];
            $listRoom[$key]->time = date("H:i", $listRoom[$key]->timeavailable);
        }

        //End checkpoint
        $user_id = $USER->id;
        $group = 'MOVEUSERMANUAL';
        $action = 'END_MOVEUSERMANUAL_APIGETMEETING_GETMEETINGINTIME_getMeetingsDetailInfo';
        $user_name = $USER->username;
        $ip = $_SERVER['REMOTE_ADDR'];
        checkpoint_native($user_id, $group, $action, $user_name, $ip, $BEGIN_MOVEUSERMANUAL_APIGETMEETING_GETMEETINGINTIME_getMeetingsDetailInfo);

        return $listRoom;
    }

    public function getVideoWarmup($meetingId) {
        global $USER, $DB;
//        $context = get_context_instance(CONTEXT_USER, $USER->id);
//        require_capability("local/tpebbb:viewroominfo", $context);

        $sql = "SELECT m.video_warmup as video_name, "
                . "CONCAT(fileurl, m.video_warmup) as video_warmup "
                . "FROM {tpebbb} as b "
                . "JOIN {materialservice} as m ON b.subject_code=m.subject_code "
                . "WHERE b.id = ? ";

        $params = Array($meetingId);
        $roomData = $DB->get_record_sql($sql, $params);
        return $roomData;
    }

    public function getUserTokenByFunction($functions, $userId = null, $validTime = null, $ip = null, $context = null) {
        global $DB;
        if (!is_array($functions)) {
            $functions = Array($functions);
        }
        foreach ($functions as &$item) {
            $item = "'" . $item . "'";
        }
        $sql = "SELECT  functionname, externalserviceid  "
                . "FROM {external_services_functions} "
                . "WHERE functionname IN (" . implode(",", $functions) . ")";
        $params = Array($functions);
        $listService = $DB->get_records_sql($sql, $params);
        $listServiceId = Array();
        foreach ($listService as $item) {
            $listServiceId[] = $item->externalserviceid;
        }
        $listToken = $this->getUserToken($userId, array_unique($listServiceId), $validTime, $ip, $context);
        $dataReturn = Array();
        foreach ($listService as $key => $value) {
            $dataReturn[$value->functionname] = $listToken[$value->externalserviceid];
        }
        return $dataReturn;
    }

    public function getUserToken($userId = null, $serviceId = null, $validTime = null, $ip = null, $context = null) {
        global $DB;
        if ($context === null) {
            global $CFG;
            $courseid = $CFG->tpe_config->courseid;   // courseid
            $context = context_course::instance($courseid);
        }
        $contextid = $context->id;

        if ($userId === null) {
            global $USER;
            $userId = $USER->id;
        }
        $requiredService = Array();
        if (!is_array($serviceId)) {
            $requiredService[] = intval($serviceId);
        } else {
            $requiredService = $serviceId;
        }
        if ($validTime === null) {
            $intDate = strtotime('+1 day', time());
            $stringDate = date("Y-m-d", $intDate) . " 01:00:00";
            $validTime = strtotime($stringDate);
        }

        $tokens = Array();
        $sql = "SELECT  externalserviceid, token, userid, validuntil, contextid  "
                . "FROM {external_tokens} "
                . "WHERE userid = ? AND externalserviceid IN(" . implode(",", $requiredService) . ") "
                . "AND validuntil >= ? "
                . "AND contextid = ? ";
        $params = Array($userId, $validTime, $contextid);
        $listToken = $DB->get_records_sql($sql, $params);
        foreach ($requiredService as $item) {
            if (isset($listToken[$item])) { //Token exit and valid time
                $tokens[$item] = $listToken[$item]->token;
            } else {
                $tokens[$item] = external_generate_token(EXTERNAL_TOKEN_PERMANENT, $item, $userId, $contextid, $validTime, $ip);
            }
        }
        return $tokens;
    }

    public function getVcrRole($userId, $context, $classType = "OTHER") {
        global $DB;
        $roleString = $this->getVcrRoleByContext($userId, $context);
        if ($roleString == 'teacher') {
            $timeAv = $this->getAvailableTime();
            $sql = "SELECT b.id,assistant_id,teacher_id "
                    . "FROM {tpebbb} as b "
                    . "JOIN {tpe_calendar_teach} as c ON c.calendar_code=b.calendar_code "
                    . "WHERE b.timedue > ? AND b.timeavailable < ? "
                    . "AND (assistant_id = ? OR teacher_id = ? ) "
                    . "AND b.roomtype = 'Room' "
                    . "ORDER BY ABS(b.timeavailable - ?) ASC LIMIT 1";
            $params = Array($timeAv["time"], $timeAv["maxTime"], $userId, $userId, $timeAv["time"]);
            $room = $DB->get_record_sql($sql, $params);
            if ($room) {
                if ($room->teacher_id == $userId) {
                    $roleString = "teacher";
                } else if ($room->assistant_id == $userId) {
                    $roleString = "assistant";
                }
            } else {
                $teacherType = $this->getTeacherType($userId);
                $mapping = $this->TpeSettings->TeacherTypeMapping;
                $key = $teacherType . "_" . $classType;
                if (isset($mapping[$key])) {
                    $roleString = $mapping[$key];
                }
            }
        }
        if ($roleString === "") {
            global $CFG;
            $this->redirect($CFG->wwwroot, get_string('undefined_role', 'local_tpebbb'));
            exit;
        }
        return $roleString;
    }

    public function getVcrRoleByMeetingId($userId, $context, $meetingId, $classType = "OTHER") {
        global $DB;
        $roleString = $this->getVcrRoleByContext($userId, $context);
        if ($roleString == 'teacher') {
            $sql = " SELECT  bbb.id as bbb_id,bbb.*,c.*,
                c.calendar_code as calendar_code
                FROM {tpebbb} bbb
                JOIN {tpe_calendar_teach} c on c.calendar_code = bbb.calendar_code
                WHERE bbb.id=?";
            $param_sql = Array($meetingId);
            $meeting_info = $DB->get_record_sql($sql, $param_sql);
            $sql = "SELECT data
                FROM {user_info_data} d
                JOIN {user_info_field} f on f.id = d.fieldid
                WHERE  f.shortname = 'teachertype' AND d.userid = ?";
            $param_sql = Array($userId);
            $user_info = $DB->get_record_sql($sql, $param_sql);
            if ($meeting_info && $user_info) {
                if ($meeting_info->teacher_id == $userId) {//Lay theo assign
                    $roleString = "teacher";
                } else if ($meeting_info->assistant_id == $userId) {//Lay theo assign
                    $roleString = "assistant";
                } else {
                    $teacherType = $user_info->data;
                    $mapping = $this->TpeSettings->TeacherTypeMapping;
                    $key = $teacherType . "_" . $classType;
                    if (isset($mapping[$key])) {
                        $roleString = $mapping[$key];
                    }
                }
            }
        }
        return $roleString;
    }

    public function getVcrRoleByContext($userId, $context) {
        $listRole = get_user_roles($context, $userId, false); //Student-Teacher-POVH
        $listRoleDetail = role_get_names($context);
        $roleString = "";
        foreach ($listRole as $item) {
            $archetype = $listRoleDetail[$item->roleid]->archetype;
            if ($archetype == "manager" || $archetype == "coursecreator") {
                $roleString = 'povh';
                break;
            } else if ($archetype == "teacher" || $archetype == "editingteacher") {
                $roleString = 'teacher';
                break;
            } else {
                $roleString = 'student';
            }
        }
        return $roleString;
    }

    public function getUserError($errorId) {
        global $DB;
        $errorDetail = $DB->get_record('logsservice_technical_error', Array("id" => $errorId));
        if ($errorDetail) {
            return $errorDetail->content;
        } else {
            return "";
        }
    }

    public function saveMoveUserLog($courseid, $povhId, $meetingId, $fromid, $userid, $moveAuto) {
        //Luu log chuyen lop neu truyen du thong tin:
        if ($povhId && $fromid) {
            global $DB;
            $data_insert = new stdClass();
            $data_insert->courseid = $courseid;
            $data_insert->userid = $userid;
            $data_insert->roomidfrom = $fromid;
            $data_insert->povhid = $povhId;
            $data_insert->timecreated = time();
            $data_insert->roomidto = $meetingId;
            $data_insert->moveauto = $moveAuto;
            return $DB->insert_record('logsservice_move_user', $data_insert);
        } else {
            return 0;
        }
    }

    public function updateMoveUserLog($logId, $meetingId, $status) {
        if ($logId) {
            global $DB;
            $data_insert = new stdClass();
            $data_insert->id = $logId;
            $data_insert->roomidto = $meetingId;
            $data_insert->status = $status;
            return $DB->update_record('logsservice_move_user', $data_insert);
        }
    }

    public function getJoinMeetingURL_proxy($joinParams, $joinUrl = null) {
        /*
          NOTE: At this point, we don't use a corresponding joinMeetingWithXmlResponse here because the API
          doesn't respond on success, but you can still code that method if you need it. Or, you can take the URL
          that's returned from this method and simply send your users off to that URL in your code.
          USAGE:
          $joinParams = array(
          'meetingId' => '1234',        -- REQUIRED - A unique id for the meeting
          'username' => 'Jane Doe', -- REQUIRED - The name that will display for the user in the meeting
          'password' => 'ap',           -- REQUIRED - The attendee or moderator password, depending on what's passed here
          'createTime' => '',           -- OPTIONAL - string. Leave blank ('') unless you set this correctly.
          'userID' => '',               -- OPTIONAL - string
          'webVoiceConf' => ''      -- OPTIONAL - string
          );
         */
        global $CFG;
        $this->_meetingId = $this->_requiredParam($joinParams['meetingId'], 'meetingId');
        $this->_username = $this->_requiredParam($joinParams['username'], 'username');
        $this->_password = $this->_requiredParam($joinParams['password'], 'password');
        // Establish the basic join URL:
        if (!$joinUrl || !$this->TpeSettings->EnableProxyBalance) {
            $joinUrl = $this->_bbbServerBaseUrl . "api/join?";
        }
        // Add parameters to the URL:
        //datnv
        if ($joinParams['userdata-roomTypeOrigin'] != 'ROOM') {

            // //tao lop
            // $creationParams['meetingId']='213076';
            // $creationParams['meetingName']='213076';
            // $this->getCreateMeetingUrl($creationParams);

            $settings = get_config('local_tpebbb');
            if ($SecuritySalt === null) {
                $SecuritySalt = $settings->SecuritySalt;
            }
            if ($ServerBBBURL === null) {
                $ServerBBBURL = $settings->ServerBBBURL;
            }
            $ServerBBBURL = $CFG->NAF2;
            $this->_securitySalt = $SecuritySalt;
            $this->_bbbServerBaseUrl = $ServerBBBURL;
            $this->TpeSettings = $settings;
            $this->processConfigValue();
            parent::__construct($SecuritySalt, $ServerBBBURL);
            $joinUrl = $this->_bbbServerBaseUrl . "api/join?";
        }
        //end datnv
        $params = 'meetingID=' . urlencode($this->_meetingId) .
                '&fullName=' . urlencode($this->_username) .
                '&password=' . urlencode($this->_password) .
                '&userID=' . urlencode($joinParams['userId']) .
                '&webVoiceConf=' . urlencode($joinParams['webVoiceConf']);
        // Only use createTime if we really want to use it. If it's '', then don't pass it:
        if (((isset($joinParams['createTime'])) && ($joinParams['createTime'] != ''))) {
            $params .= '&createTime=' . urlencode($joinParams['createTime']);
        }
        foreach ($joinParams as $key => $value) {
            if (strpos($key, "userdata-") !== false) {
                $params .= "&" . $key . "=" . urlencode($value);
            }
        }
        // Return the URL:
        return ($joinUrl . $params . '&checksum=' . sha1("join" . $params . $this->_securitySalt));
    }

    //datnv kiem tra xem con lop tren adb con khong
    function isfull_adb($userInfo, $classType = NULL) {

        global $USER, $DB;

        //Begin checkpoint
        $user_id = $USER->id;
        $group = 'CHECK_CLASS_FULL';
        $action = 'BEGIN_CHECK_CLASS_FULL';
        $user_name = $USER->username;
        $ip = $_SERVER['REMOTE_ADDR'];
        $BEGIN_CHECK_CLASS_FULL = microtime(true);
        checkpoint_native($user_id, $group, $action, $user_name, $ip);

        // can hoi lai anh Manh loai VIP            
        $max_student_vip = array(
            "VIP1" => 1,
            "VIP2" => 2,
            "VIP3" => 3,
            "VIP" => 4,
        );

        $convert_student_type = array(
            "VI" => "VN",
            "TH" => "TL",
        );

        $convert_teacher_type_ls = array(
            "TAAM" => "AM",
            "TENUP" => "PHI",
        );

        $MAX_STUDENT_NO_VIP = 4; // TUNGND FIX SỐ HV TRONG LỚP 4
        $MAX_STUDENT_VIP = 4;
        $DEFAULT_TEACHER_TYPE = 'AM';
        $ROLE_STUDENT = 5;
        $MAX_STUDENT_SBASIC = 4;
        //xem co phai hoc vien khong
        $check_role = $DB->get_records("role_assignments", array("userid" => $userInfo->id));
        $check_role = array_values($check_role);

        if (!is_array($check_role) || $check_role[0]->roleid != $ROLE_STUDENT) {

            //End checkpoint
            $user_id = $USER->id;
            $group = 'CHECK_CLASS_FULL';
            $action = 'END_CHECK_CLASS_FULL';
            $user_name = $USER->username;
            $ip = $_SERVER['REMOTE_ADDR'];
            checkpoint_native($user_id, $group, $action, $user_name, $ip, $BEGIN_CHECK_CLASS_FULL);

            return;
        }

        $now = time();
        $curr_minutes = date("i", $now);
        $time_available_begin = strtotime(date("Y-m-d H:00:00"), $now);

        if ($curr_minutes >= 45) {
            $time_available_begin += 3600;
        }
        $time_available_end = $time_available_begin + 3600;
        //$hour_start = date("G", $time_available);
        $date_time = strtotime(date("Y-m-d 00:00:00", $now));


        $student_type = isset($convert_student_type[strtoupper($userInfo->lang)]) ? $convert_student_type[strtoupper($userInfo->lang)] : NULL;
        if (strpos($userInfo->packagetype, 'VIP') !== FALSE) {
            $student_type = "VIP";
        }
        //debug
        //    $student_type = "VIP";

        $teacher_type = $DEFAULT_TEACHER_TYPE;

        if ($classType == "SC") {
            $teacher_type = isset($convert_student_type[strtoupper($userInfo->lang)]) ? $convert_student_type[strtoupper($userInfo->lang)] : NULL;
        } else if ($classType == "LS") {
            if (isset($convert_teacher_type_ls[$userInfo->packagetype])) {
                $teacher_type = $convert_teacher_type_ls[$userInfo->packagetype];
            } else {
                $teacher_type = $DEFAULT_TEACHER_TYPE;
            }
        }

        //datnv kiem tra xem trinh do hoc vien la gi.neu la sbasic thi chuyen ve basic
        if (substr($userInfo->userlv, 0, 6) == 'sbasic' && $classType == "LS")
            $userInfo->userlv = 'basic';
        //end datnv 
        //datnv sql lay danh sach lop adb
        $sql_adb = "SELECT bbb.id , ct.hour_id, ct.teacher_type " //ht.hour_start,
                . "FROM {tpebbb} as bbb "
                . "JOIN {tpe_calendar_teach} as ct ON ct.calendar_code = bbb.calendar_code "
                . "WHERE bbb.roomtype = 'ROOM' AND bbb.vcr_type = 'ADB' AND ct.level_class = '{$userInfo->userlv}' "
                //      . "WHERE bbb.roomtype = 'ROOM' AND bbb.vcr_type = 'ADB' AND ct.level_class = 'inter' "
                . "AND ct.type_class = '{$classType}' AND bbb.timeavailable >= {$time_available_begin} AND bbb.timeavailable<{$time_available_end} AND ct.status > 0 AND ct.teacher_type NOT IN ('TRAINING', 'ORIENTATION') "
                // . "AND ct.date_time = '{$date_time}' "
                . "AND ct.student_type = '{$student_type}' ";
        //end datnv

        if ($teacher_type) {
            $sql_adb .= "AND ct.teacher_type LIKE '%" . $teacher_type . "%' ";
        }
        $list_curr_adb_class = $DB->get_records_sql($sql_adb, array());

        //BEGIN - Datnv Query lay danh sach lop adobe
        if (count($list_curr_adb_class) == 0) {
            if (strpos($teacher_type, 'PHI') >= 0 && $userInfo->userlv == 'inter') {
                $sql_adb = "SELECT bbb.id , ct.hour_id, ct.teacher_type "
                        . "FROM {tpebbb} as bbb "
                        . "JOIN {tpe_calendar_teach} as ct ON ct.calendar_code = bbb.calendar_code "
                        . "WHERE bbb.roomtype = 'ROOM' AND bbb.vcr_type = 'ADB' AND ct.level_class = '{$userInfo->userlv}' "
                        . "AND ct.type_class = '{$classType}' AND bbb.timeavailable >= {$time_available_begin} AND bbb.timeavailable<={$time_available_end} AND ct.status > 0 AND ct.teacher_type NOT IN ('TRAINING', 'ORIENTATION') "
                        . "AND ct.student_type = '{$student_type}' ";
                if ($teacher_type) {
                    $sql_adb .= "AND ct.teacher_type LIKE '%AM%' ";
                }
                $list_curr_adb_class = $DB->get_records_sql($sql_adb, array());
            }
        }
        //END - Datnv

        if (!$list_curr_adb_class) {
            $list_curr_adb_class = array();
        }
        $list_adb_room_id = array();

        foreach ($list_curr_adb_class as $one_class) {
            $list_adb_room_id [] = $one_class->id;
        }

        //=== So hoc vien ADB dang trong he thong, techtest + room
        $curr_number_student_adb_in = 0;
        $list_curr_number_student_adb_in = array();

        //---- Cach lay trong log_move_user ---------------
        // do chua lay so HV trong lop qua API ADB
        if (count($list_adb_room_id)) {
            $sql_curr_number_student_adb_in = "SELECT log_move.userid "
                    . "FROM {logsservice_move_user} as log_move "
                    . "JOIN {role_assignments} as role_assgin ON role_assgin.userid = log_move.userid "
                    . "WHERE  log_move.roomidto IN (" . implode(",", $list_adb_room_id) . ") "
                    //            . "AND log_move.status = 'SUCCESS' "
                    . "AND role_assgin.roleid = {$ROLE_STUDENT}"; // student
            $list_curr_number_student_adb_in = $DB->get_records_sql($sql_curr_number_student_adb_in, array());
            if (is_array($list_curr_number_student_adb_in)) {
                $curr_number_student_adb_in = count($list_curr_number_student_adb_in);
            }
        }

        //=== Tinh toan so luong max hoc vien cho phep
        $max_number_student_allow = 0;
        if ($student_type == "VIP") {
            foreach ($list_curr_adb_class as $one_class) {
                $explode_teacher_type = explode("-", $one_class->teacher_type);
                if (isset($max_student_vip[$explode_teacher_type[0]])) { // VIP1-AM, ...
                    $max_number_student_allow += $max_student_vip[$explode_teacher_type[0]];
                } else {
                    $max_number_student_allow += $MAX_STUDENT_VIP;
                }
            }
        } else if ($classType == 'SC' && substr($userInfo->userlv, 0, 6) == 'sbasic') {
            //Begin datnv
            $max_number_student_allow = $MAX_STUDENT_SBASIC * count($list_adb_room_id);
            //End datnv
        } else {
            $max_number_student_allow = $MAX_STUDENT_NO_VIP * count($list_adb_room_id);
        }

        // if ($curr_minutes < 45) {
        //     $curr_number_student_adb_in = $max_number_student_allow;
        // }

        if ($curr_number_student_adb_in < $max_number_student_allow || isset($list_curr_number_student_adb_in[$userInfo->id])) {
            //tech room ADB
            //End checkpoint
            $user_id = $USER->id;
            $group = 'CHECK_CLASS_FULL';
            $action = 'END_CHECK_CLASS_FULL';
            $user_name = $USER->username;
            $ip = $_SERVER['REMOTE_ADDR'];
            checkpoint_native($user_id, $group, $action, $user_name, $ip, $BEGIN_CHECK_CLASS_FULL);

            return true;
            //        $joinParam["userdata-LmsUrl"] = $LMS_DOMAIN_ADB . "/webservice/rest/server.php?moodlewsrestformat=json";
            //        $joinParam["userdata-lmsdomain"] = $LMS_DOMAIN_ADB;
        }

        //End checkpoint
        $user_id = $USER->id;
        $group = 'CHECK_CLASS_FULL';
        $action = 'END_CHECK_CLASS_FULL';
        $user_name = $USER->username;
        $ip = $_SERVER['REMOTE_ADDR'];
        checkpoint_native($user_id, $group, $action, $user_name, $ip, $BEGIN_CHECK_CLASS_FULL);

        return false;
    }

    //end anhld
    //datnv
    public function check_count_current_student_adb() {
        $sql_adb = "SELECT bbb.id , ct.hour_id, ht.hour_start, ct.teacher_type " //ht.hour_start,
                . "FROM {tpebbb} as bbb "
                . "JOIN {tpe_calendar_teach} as ct ON ct.calendar_code = bbb.calendar_code "
                . "JOIN {hour_teach} as ht ON ht.id = ct.hour_id "
//            . "WHERE bbb.roomtype = 'ROOM' AND bbb.vcr_type = 'ADB' AND ct.level_class = '{$USER->profile['currentlevel']}' "
                . "WHERE bbb.roomtype = 'ROOM' AND bbb.vcr_type = 'ADB' AND ct.level_class = 'inter' "
                . "AND ct.type_class = '{$classType}' AND ht.hour_start = '{$hour_start}' AND ct.status > 0 AND ct.teacher_type NOT IN ('TRAINING', 'ORIENTATION') "
                . "AND ct.date_time = '{$date_time}' "
                . "AND ct.student_type = '{$student_type}' ";
//            . "AND ct.teacher_id != 0";
        if ($teacher_type) {
            $sql_adb .= "AND ct.teacher_type LIKE '%" . $teacher_type . "%' ";
        }
        $list_curr_adb_class = $DB->get_records_sql($sql_adb, array());

        if (!$list_curr_adb_class) {
            $list_curr_adb_class = array();
        }

        $list_adb_room_id = array();

        foreach ($list_curr_adb_class as $one_class) {
            $list_adb_room_id [] = $one_class->id;
        }
        if (count($list_adb_room_id)) {
            $sql_curr_number_student_adb_in = "SELECT log_move.userid "
                    . "FROM {logsservice_move_user} as log_move "
                    . "JOIN {role_assignments} as role_assgin ON role_assgin.userid = log_move.userid "
                    . "WHERE  log_move.roomidto IN (" . implode(",", $list_adb_room_id) . ") "
                    . "AND role_assgin.roleid = {$ROLE_STUDENT}"; // student =5
            $list_curr_number_student_adb_in = $DB->get_records_sql($sql_curr_number_student_adb_in, array());
            if (is_array($list_curr_number_student_adb_in)) {
                $curr_number_student_adb_in = count($list_curr_number_student_adb_in);
                if ($curr_number_student_adb_in > 0)
                    return true;
            }
        }
        return false;
    }

    //end datnv

    public function autoMovingUser($userid, $povhid, $fromid, $classtype, $context) {
        global $CFG, $USER;
        if ($classtype == 'ROOM') {
            $this->_bbbServerBaseUrl = $CFG->NAF1;
        }
        $dataReturn = Array(
            "status" => false,
            "status_code" => "UNKNOW",
            "urlRedirect" => "",
            "msg" => "",
        );
        //Lấy thông tin học viên
        $userInfo = $this->getUserInfo($userid);
        if ($userInfo) {
            //Lấy thông tin quyền của user
            $roleLms = $this->getVcrRole($userid, $context);
            $roleVcr = $this->TpeSettings->VcrRole[$roleLms];

            //Begin checkpoint
            $user_id = $USER->id;
            $group = 'CHECK_AUTO_MOVING_USER_RECENT';
            $action = 'BEGIN_CHECK_AUTO_MOVING_USER_RECENT';
            $user_name = $USER->username;
            $ip = $_SERVER['REMOTE_ADDR'];
            $CHECK_AUTO_MOVING_USER_RECENT = microtime(true);
            checkpoint_native($user_id, $group, $action, $user_name, $ip);


            $dataReturn = $this->autoMovingUserRecent($userid, $roleVcr);

            //End checkpoint
            $user_id = $USER->id;
            $group = 'CHECK_AUTO_MOVING_USER_RECENT';
            $action = 'END_CHECK_AUTO_MOVING_USER_RECENT';
            $user_name = $USER->username;
            $ip = $_SERVER['REMOTE_ADDR'];
            checkpoint_native($user_id, $group, $action, $user_name, $ip, $CHECK_AUTO_MOVING_USER_RECENT);

            if (!$dataReturn["status"]) {
                if ($roleVcr == $this->STUDENT) {

                    //Manhnv(20/02/2016): Neu la Loai tai khoan Khac TENUP thi chuyen vao BBB
                    // if( $userInfo->packagetype != 'TENUP')
                    // {
                    //     $dataReturn = $this->autoMovingUserStudent_BBB($userInfo->userlv, $classtype, $userInfo->packagetype);
                    // }
                    //Neu khong thi chuyen vao lop ADB
                    // else
                    // {
                    //Kiem tra xem con slot ADB khong
                    if ($this->isfull_adb($userInfo, $classtype) == true) {
                        $dataReturn = $this->autoMovingUserStudent_ADB($userInfo->userlv, $classtype, $userInfo->packagetype);
                        if ($dataReturn['status'] == false && $dataReturn['fail'] == true) {
                            $dataReturn["status"] = false;
                            $dataReturn["msg"] = "Het lop hoc ADB va BBB. Vui long tao them lop";
                            return $dataReturn;
                        }
                        $listPlugins = get_plugin_list("local");
                        if (isset($listPlugins["tpeadb"])) {
                            require_once($listPlugins["tpeadb"] . "/locallib.php");
                        } else {
                            throw new Exception("require plugin 'local/tpeadb'");
                        }
                        //Timer: 1s
                        //Lay thong tin lop adb
                        $adbInfo = getLinkAutoADBStudent($dataReturn["classData"]->id, $userid);
                        //Kiem tra lay link thanh cong
                        if ($adbInfo['status']) {
                            //Tạo url redirect
                            $dataReturn["status"] = true;
                            $dataReturn["status_code"] = 'OK';
                            $dataReturn["msg"] = get_string('auto_move_success', 'local_tpebbb');
                            $dataReturn["msg"] = "Chuyển tự động thành công";
                            $dataReturn["urlRedirect"] = $adbInfo['link'];
                            save_log_move_user($fromid, $dataReturn["classData"]->id, $povhid, $userid, 1);
                            save_log_in_out($userid, $dataReturn["classData"]->id);
                        } else {
                            $dataReturn["status"] = false;
                            $dataReturn["status_code"] = $adbInfo['status_code'];
                            $dataReturn["msg"] = $adbInfo['status_code'];
                        }

                        return $dataReturn;
                    }
                    // Het lop ADB se chuyen vao BBB
                    else {
                        $dataReturn = $this->autoMovingUserStudent_BBB($userInfo->userlv, $classtype, $userInfo->packagetype);
                    }
                    // }
                } else if ($roleVcr == $this->TEACHER) {
                    $dataReturn = $this->autoMovingUserMod($userid, $roleVcr);
                } else if ($roleVcr == $this->ASSISTANT) {
                    $dataReturn = $this->autoMovingUserMod($userid, $roleVcr);
                } else {
                    $dataReturn = Array(
                        "status" => false,
                        "status_code" => "NO_AUTOMOVE_FOR_ROLE_" . $roleVcr,
                        "urlRedirect" => "",
                        "msg" => "",
                    );
                }
            }
            if ($dataReturn["status"]) {
                //datnv 16/02/2016 kiem tra xem rolevrc neu la STUDENT thi chuyen vao lop gan nhat da tung vao
                if ($dataReturn["classData"]->vcr_type == 'ADB' && $roleVcr != 'STUDENT') {
                    $listPlugins = get_plugin_list("local");
                    if (isset($listPlugins["tpeadb"])) {
                        require_once($listPlugins["tpeadb"] . "/locallib.php");
                    } else {
                        throw new Exception("require plugin 'local/tpeadb'");
                    }
                    if ($roleVcr == $this->TEACHER) {
                        $adbInfo = getLinkADBTeacher($dataReturn["classData"]->id, $userid);
                    } else if ($roleVcr == $this->ASSISTANT) {
                        $adbInfo = getLinkADBAssistant($dataReturn["classData"]->id, $userid);
                    }
                    if ($adbInfo['status']) {
                        //Tạo url redirect
                        $dataReturn["status"] = true;
                        $dataReturn["status_code"] = 'OK';
                        $dataReturn["msg"] = get_string('auto_move_success', 'local_tpebbb');
                        $dataReturn["msg"] = "Chuyển tự động thành công";
                        $dataReturn["urlRedirect"] = $adbInfo['link'];
                        save_log_move_user($fromid, $dataReturn["classData"]->id, $povhid, $userid, 1);
                        save_log_in_out($userid, $dataReturn["classData"]->id);
                    } else {
                        $dataReturn["status"] = false;
                        $dataReturn["status_code"] = $adbInfo['status_code'];
                        $dataReturn["msg"] = $adbInfo['status_code'];
                    }
                    return $dataReturn;
                }
                if ($dataReturn["classData"]->vcr_type == 'ADB' && $roleVcr == 'STUDENT') {
                    $listPlugins = get_plugin_list("local");
                    if (isset($listPlugins["tpeadb"])) {
                        require_once($listPlugins["tpeadb"] . "/locallib.php");
                    } else {
                        throw new Exception("require plugin 'local/tpeadb'");
                    }
                    $adbInfo = getLinkAutoADBStudent($dataReturn["classData"]->id, $userid);
                    //Kiem tra lay link thanh cong
                    if ($adbInfo['status']) {
                        //Tạo url redirect
                        $dataReturn["status"] = true;
                        $dataReturn["status_code"] = 'OK';
                        $dataReturn["msg"] = get_string('auto_move_success', 'local_tpebbb');
                        $dataReturn["msg"] = "Chuyển tự động thành công";
                        $dataReturn["urlRedirect"] = $adbInfo['link'];
                        save_log_move_user($fromid, $dataReturn["classData"]->id, $povhid, $userid, 1);
                        save_log_in_out($userid, $dataReturn["classData"]->id);
                    } else {
                        $dataReturn["status"] = false;
                        $dataReturn["status_code"] = $adbInfo['status_code'];
                        $dataReturn["msg"] = $adbInfo['status_code'];
                    }
                    return $dataReturn;
                }
                //end datnv

                $meetingid = $dataReturn["classData"]->id;
                //Booking slot
                if ($dataReturn["classData"]->level == 'sbasic') {
                    $maxUser = 4;
                } else {
                    $maxUser = $this->TpeSettings->maxUser;
                }
                $bookStatus = $this->bookSlotWithMeetingId($userid, $meetingid, $maxUser, $context);
                if ($bookStatus == 4) {
                    $dataReturn["status"] = false;
                    $dataReturn["status_code"] = 'book_slot_room_live_full';
                    $dataReturn["msg"] = get_string('error_auto_move_fail', 'local_tpebbb', 'book_slot_room_live_full');
                } else if ($bookStatus == 2) {
                    $dataReturn["status"] = false;
                    $dataReturn["status_code"] = 'book_slot_meeting_not_exist';
                    $dataReturn["msg"] = get_string('error_auto_move_fail', 'local_tpebbb', 'book_slot_meeting_not_exist');
                } else if ($bookStatus == 1) {
                    $dataReturn["status"] = false;
                    $dataReturn["status_code"] = 'book_slot_data_lms_not_correct';
                    $dataReturn["msg"] = get_string('error_auto_move_fail', 'local_tpebbb', 'book_slot_data_lms_not_correct');
                } else if ($bookStatus == 0 || $bookStatus == 3) {
                    //Tạo url redirect
                    $dataReturn["status"] = true;
                    $dataReturn["status_code"] = 'OK';
                    $dataReturn["msg"] = get_string('auto_move_success', 'local_tpebbb');
                    $dataReturn["msg"] = "Chuyển tự động thành công";
                    $dataReturn["urlRedirect"] = $this->getLinkVcr("TECHTEST", "OTHER", $meetingid);
                    $dataReturn["urlRedirect"] .= "&modId=" . $povhid . "&fromId=" . $fromid . "&auto=1";
                } else {
                    $dataReturn["status"] = false;
                    $dataReturn["status_code"] = 'book_slot_failed_unkown_error';
                    if ($this->errorMove) {
                        $dataReturn["msg"] = $this->errorMove;
                    } else {
                        $dataReturn["msg"] = get_string('error_auto_move_fail', 'local_tpebbb', 'book_slot_failed_unkown_error');
                    }
                }
            } else {
                if ($dataReturn["status_code"] == "NOT_OPEN") {
                    $dataReturn["msg"] = get_string('auto_move_room_not_open', 'local_tpebbb');
                } else {
                    $dataReturn["msg"] = get_string('error_room_not_have_technician', 'local_tpebbb', $dataReturn["status_code"]);
                }
            }
        } else {
            $this->error("USER ID INVALID");
        }
        return $dataReturn;
    }

    private function autoMovingUserSql() {
        $sql = "SELECT b.id,b.name as title,b.vcr_type,b.vcr_class_id,c.level_class as level, b.calendar_code, "
                . "c.type_class as type, CONCAT(u.firstname,' ', u.lastname) as teacher,u.id as teacher_id, "
                . "CONCAT(u2.firstname,' ', u2.lastname) as assistant, u2.id as assistant_id, b.timeavailable "
                . "FROM {tpebbb} as b "
                . "JOIN {tpe_calendar_teach} as c ON c.calendar_code=b.calendar_code "
                . "LEFT JOIN {user} as u ON c.teacher_id=u.id "
                . "LEFT JOIN {user} as u2 ON c.assistant_id=u2.id "
                . "WHERE b.timedue > ? AND b.timeavailable < ? "
                . "AND b.roomtype = 'Room' AND c.status > 0";
        return $sql;
    }

    private function autoMovingUserRecent($userid, $roleVcr) {
        $dataReturn = Array(
            "status" => false,
            "status_code" => "UNKNOW",
//            "classData" => null,
        );
        $recentRoom = $this->getRecentRoom($userid);
        //datnv 16/02/2016
        if ($recentRoom->vcr_type == 'ADB') {
            $dataReturn["classData"] = $recentRoom;
            $dataReturn["status"] = true;
            return $dataReturn;
        }
        //end datnv
        if ($recentRoom) {
            $roomInfo = new stdClass();
            $roomInfo->id = isset($recentRoom->id) ? $recentRoom->id : 0;
            $listRoom = Array($roomInfo);
            $meetingInfo = $this->getMeetingsInfoCountRecent($listRoom);
            if ($roleVcr == $this->STUDENT) {
                if (isset($meetingInfo[$roomInfo->id]["liveUsersView"]) && $meetingInfo[$roomInfo->id]["liveUsersView"] < 6) {
                    $dataReturn["classData"] = $recentRoom;
                    $dataReturn["status"] = true;
                }
            } else if ($roleVcr == $this->TEACHER) {
                $dataReturn["classData"] = $recentRoom;
                $dataReturn["status"] = true;
            } else if ($roleVcr == $this->ASSISTANT) {
                $dataReturn["classData"] = $recentRoom;
                $dataReturn["status"] = true;
            }
        }
        return $dataReturn;
    }

    private function autoMovingUserStudent_ADB($level, $classtype, $packageType = 'UNKNOW') {
        //Lấy danh sách lớp
        global $DB;
        $dataReturn = Array(
            "status" => false,
            "status_code" => "UNKNOW",
//            "classData" => null,
        );
        $classTypeMapping = $this->TpeSettings->AutoMoveMapping;
        $accKey = $packageType . "_" . $classtype;
        if (isset($classTypeMapping[$accKey])) {
            $classDataMapping = explode('_', $classTypeMapping[$accKey]);
            $teacherType = $classDataMapping[0];
            $studentType = $classDataMapping[1];
        } else { //Khong co kieu lop phu hop voi hoc vien de chuyen tu dong
            return Array(
                "status" => false,
                "status_code" => "NO_USER_TYPE_MATCHING",
            );
        }
        $sql = $this->autoMovingUserSql();
        //datnv 15/2/2016 Sau khi chuyển HV thì thấy HV bay vào lớp ADB dau tien               
        $sql .= " AND level_class = ? AND type_class = ? AND teacher_type = ? AND student_type = ? AND b.vcr_type = 'ADB' ORDER BY b.id";
        //end datnv
        /**
         * CHITCODE phần chuyển tự động của Hvien sbasic vào lớp LS - basic
         */
        if ($classtype == 'LS' && $level == 'sbasic') {
            $level = 'basic';
        }

        $timeAv = $this->getAvailableTime();
        $params = Array($timeAv["time"], $timeAv["maxTime"], $level, $classtype, $teacherType, $studentType);
        $listRoom = $DB->get_records_sql($sql, $params);

        /* BEGIN - datnv Học viên Tenup Inter học lớp trực tuyến thì vào lớp Inter Tenup.
          Nhưng nếu không có lớp Inter Tenup sẽ tự động chuyển vào lớp Inter AM */
        if (count($listRoom) == 0 && $teacherType == 'PHI') {
            $teacherType = 'AM';
            $sql = $this->autoMovingUserSql();
            $sql .= " AND level_class = ? AND type_class = ? AND teacher_type = ? AND student_type = ? AND (b.vcr_type = 'ADB' OR b.vcr_type IS NULL) ORDER BY b.id";
            $params = Array($timeAv["time"], $timeAv["maxTime"], $level, $classtype, $teacherType, $studentType);
            $listRoom = $DB->get_records_sql($sql, $params);
        }
        //END - datnv 
        // manhnv lay ra danh sach cac lop it hon 6 nguoi

        $ROLE_STUDENT = 5;
        foreach ($listRoom as $key => $value) {
            //User da duoc chuyen vao lop            
            $count_user_moved = count($DB->get_records_sql("select a.userid from (select DISTINCT userid from mdl_logsservice_move_user where roomidto = ? ) a LEFT JOIN mdl_role_assignments b on a.userid = b.userid where b.roleid = {$ROLE_STUDENT}", Array($value->id)));
            if ($count_user_moved >= 6) {
                unset($listRoom[$key]);
            }
            //datnv kiem tra neu la lop SC va trinh do basic chi co 4 nguoi trong lop
            if ($classtype == 'SC' && $level == 'sbasic') {
                if ($count_user_moved >= 4) {
                    unset($listRoom[$key]);
                }
            }
            //end datnv
        }
        //end manhnv
        if (count($listRoom) == 0) {
            return Array(
                "status" => false,
                "status_code" => "NO_CLASS_MATCHING",
                //datnv them fail de show ra loi het phong ADB va BBB
                'fail' => 'true',
            );
        }
        $listRoomDetail = $this->getMeetingsInfoCount($listRoom);
        //datnv 25/02/2016
        $roomFirst = "";
        //end datnv
        foreach ($listRoom as $key => $value) {
            $listRoom[$key]->studentNumber = isset($listRoomDetail[$key]['userInfoText']) ? $listRoomDetail[$key]['userInfoText'] : 0;
            $listRoom[$key]->joinedUsersView = isset($listRoomDetail[$key]['joinedUsersView']) ? $listRoomDetail[$key]['joinedUsersView'] : 0;
            $listRoom[$key]->liveUsersView = isset($listRoomDetail[$key]['liveUsersView']) ? $listRoomDetail[$key]['liveUsersView'] : 0;
            $listRoom[$key]->joinedUsersMod = isset($listRoomDetail[$key]['joinedUsersMod']) ? $listRoomDetail[$key]['joinedUsersMod'] : 0;
            $listRoom[$key]->liveUsersMod = isset($listRoomDetail[$key]['liveUsersMod']) ? $listRoomDetail[$key]['liveUsersMod'] : 0;
            $listRoom[$key]->waitingUsersView = isset($listRoomDetail[$key]['waitingUsers']) ? $listRoomDetail[$key]['waitingUsers'] : 0;
            $listRoom[$key]->time = date("H:i", $listRoom[$key]->timeavailable);
            $listRoom[$key]->teacherType = $teacherType;
            $total = $listRoom[$key]->joinedUsersView + $listRoom[$key]->waitingUsersView;
            if ($listRoom[$key]->level == 'sbasic' && $listRoom[$key]->teacherType != 'ORIENTATION') {
                $maxUser = 4;
            } elseif ($listRoom[$key]->teacherType == 'ORIENTATION') {
                $maxUser = 8;
            } else {
                $maxUser = $this->TpeSettings->maxUser;
            }
            if ($packageType == 'THAI' || $packageType == 'INDO') {
                $maxUser = 4;
            }
            if ($total < $maxUser &&
                    $listRoomDetail[$key]['joinedUsersView'] !== false && $listRoomDetail[$key]['meetingExists'] == 'SUCCESS') {
                $dataReturn["status"] = true;
                $dataReturn["status_code"] = "OK";
                $dataReturn["classData"] = $listRoom[$key];
                break;
            } else {
                //datnv 25/02/2016
                if ($roomFirst == "")
                    $roomFirst = $key;
                //end datnv
                $dataReturn["status"] = false;
                $dataReturn["status_code"] = "NOT_OPEN";
                $dataReturn["classData"] = $listRoom[$roomFirst];
            }
        }
        return $dataReturn;
    }

    private function autoMovingUserStudent_BBB($level, $classtype, $packageType = 'UNKNOW') {
        //Begin checkpoint
        $user_id = $USER->id;
        $group = 'GET_LINK_BBB';
        $action = 'BEGIN_GET_LINK_BBB_STUDENT';
        $user_name = $USER->username;
        $ip = $_SERVER['REMOTE_ADDR'];
        $BEGIN_GET_LINK_BBB_STUDENT = microtime(true);
        checkpoint_native($user_id, $group, $action, $user_name, $ip);

        //Lấy danh sách lớp
        global $DB;
        $dataReturn = Array(
            "status" => false,
            "status_code" => "UNKNOW",
//            "classData" => null,
        );
        $classTypeMapping = $this->TpeSettings->AutoMoveMapping;
        $accKey = $packageType . "_" . $classtype;
        if (isset($classTypeMapping[$accKey])) {
            $classDataMapping = explode('_', $classTypeMapping[$accKey]);
            $teacherType = $classDataMapping[0];
            $studentType = $classDataMapping[1];
        } else { //Khong co kieu lop phu hop voi hoc vien de chuyen tu dong
            return Array(
                "status" => false,
                "status_code" => "NO_USER_TYPE_MATCHING",
            );
        }
        $sql = $this->autoMovingUserSql();
//        $sql .= " AND level_class = ? AND type_class = ? AND teacher_type = ? ORDER BY ABS(b.timeavailable - ?) ASC";
        //datnv 23/02/2016 
        //$sql .= " AND level_class = ? AND type_class = ? AND teacher_type = ? AND student_type = ? AND (b.vcr_type = 'BBB' OR b.vcr_type IS NULL) ORDER BY b.vcr_type desc";
        //end datnv
        $sql .= " AND level_class = ? AND type_class = ? AND teacher_type = ? AND student_type = ? AND (b.vcr_type = 'BBB' OR b.vcr_type IS NULL) ORDER BY b.id asc";
        /**
         * CHITCODE phần chuyển tự động của Hvien sbasic vào lớp LS - basic
         */
        if ($classtype == 'LS' && $level == 'sbasic') {
            $level = 'basic';
        }

        $timeAv = $this->getAvailableTime();
        $params = Array($timeAv["time"], $timeAv["maxTime"], $level, $classtype, $teacherType, $studentType);
        $listRoom = $DB->get_records_sql($sql, $params);

        /* BEGIN - datnv Học viên Tenup Inter học lớp trực tuyến thì vào lớp Inter Tenup.
          Nhưng nếu không có lớp Inter Tenup sẽ tự động chuyển vào lớp Inter AM */
        if (count($listRoom) == 0 && $teacherType == 'PHI') {
            $teacherType = 'AM';
            $sql = $this->autoMovingUserSql();
            $sql .= " AND level_class = ? AND type_class = ? AND teacher_type = ? AND student_type = ? AND (b.vcr_type = 'BBB' OR b.vcr_type IS NULL) ORDER BY b.vcr_type desc";
            $params = Array($timeAv["time"], $timeAv["maxTime"], $level, $classtype, $teacherType, $studentType);
            $listRoom = $DB->get_records_sql($sql, $params);
        }
        //END - datnv

        if (count($listRoom) == 0) {
            return Array(
                "status" => false,
                "status_code" => "NO_CLASS_MATCHING",
            );
        }
        $listRoomDetail = $this->getMeetingsInfoCount($listRoom);
        foreach ($listRoom as $key => $value) {
            $listRoom[$key]->studentNumber = isset($listRoomDetail[$key]['userInfoText']) ? $listRoomDetail[$key]['userInfoText'] : 0;
            $listRoom[$key]->joinedUsersView = isset($listRoomDetail[$key]['joinedUsersView']) ? $listRoomDetail[$key]['joinedUsersView'] : 0;
            $listRoom[$key]->liveUsersView = isset($listRoomDetail[$key]['liveUsersView']) ? $listRoomDetail[$key]['liveUsersView'] : 0;
            $listRoom[$key]->joinedUsersMod = isset($listRoomDetail[$key]['joinedUsersMod']) ? $listRoomDetail[$key]['joinedUsersMod'] : 0;
            $listRoom[$key]->liveUsersMod = isset($listRoomDetail[$key]['liveUsersMod']) ? $listRoomDetail[$key]['liveUsersMod'] : 0;
            $listRoom[$key]->waitingUsersView = isset($listRoomDetail[$key]['waitingUsers']) ? $listRoomDetail[$key]['waitingUsers'] : 0;
            $listRoom[$key]->time = date("H:i", $listRoom[$key]->timeavailable);
            $listRoom[$key]->teacherType = $teacherType;
            $total = $listRoom[$key]->joinedUsersView + $listRoom[$key]->waitingUsersView;
            if ($listRoom[$key]->level == 'sbasic' && $listRoom[$key]->teacherType != 'ORIENTATION') {
                $maxUser = 4;
            } elseif ($listRoom[$key]->teacherType == 'ORIENTATION') {
                $maxUser = 8;
            } else {
                $maxUser = $this->TpeSettings->maxUser;
            }
            if ($packageType == 'THAI' || $packageType == 'INDO') { //thuyvv
                $maxUser = 4;
            }
            if ($total < $maxUser &&
                    $listRoomDetail[$key]['joinedUsersView'] !== false && $listRoomDetail[$key]['meetingExists'] == 'SUCCESS') {
                $dataReturn["status"] = true;
                $dataReturn["status_code"] = "OK";
                $dataReturn["classData"] = $listRoom[$key];
                break;
            } else {
                $dataReturn["status"] = false;
                $dataReturn["status_code"] = "NOT_OPEN";
                $dataReturn["classData"] = $listRoom[$key];
            }
        }

        //End checkpoint
        $user_id = $USER->id;
        $group = 'GET_LINK_BBB';
        $action = 'END_GET_LINK_BBB_STUDENT';
        $user_name = $USER->username;
        $ip = $_SERVER['REMOTE_ADDR'];
        checkpoint_native($user_id, $group, $action, $user_name, $ip, $BEGIN_GET_LINK_BBB_STUDENT);
        return $dataReturn;
    }

    private function autoMovingUserMod($userid, $role) {
        global $DB;
        $dataReturn = Array(
            "status" => false,
            "status_code" => "UNKNOW",
//            "classData" => null,
        );
        $sql = $this->autoMovingUserSql();
        if ($role == $this->TEACHER) {
            $sql .= " AND u.id = ? ORDER BY ABS(b.timeavailable - ?) ASC LIMIT 1";
        } else if ($role == $this->ASSISTANT) {
            $sql .= " AND u2.id = ? ORDER BY ABS(b.timeavailable - ?) ASC LIMIT 1";
        } else {
            print_error("CODE_ERROR");
        }
        $timeAv = $this->getAvailableTime();
        $params = Array($timeAv["time"], $timeAv["maxTime"], $userid, $timeAv["time"]);
        $room = $DB->get_record_sql($sql, $params);

        if ($room) {
            /**
             * TungTB: Lop ADB
             * 26.11.2015
             */
            if ($room->vcr_type == 'ADB') {
                $dataReturn["status"] = true;
                $dataReturn["status_code"] = "OK";
                $dataReturn["classData"] = $room;
                return $dataReturn;
            }
            /**
             * End TungTB
             */
            $roomDetail = $this->getMeetingDetailInfo($room->id);
            $room->studentNumber = $roomDetail['userInfoText'];
            $room->joinedUsersView = count($roomDetail['joinedUsersView']);
            $room->liveUsersView = count($roomDetail['liveUsersView']);
            $room->joinedUsersMod = count($roomDetail['joinedUsersMod']);
            $room->liveUsersMod = count($roomDetail['liveUsersMod']);
            $room->time = date("H:i", $roomDetail->timeavailable);
            if ($roomDetail['joinedUsersMod'] !== false) {
                $dataReturn["status"] = true;
                $dataReturn["status_code"] = "OK";
                $dataReturn["classData"] = $room;
            } else {
                $dataReturn["status"] = false;
                $dataReturn["status_code"] = "NOT_OPEN";
                $dataReturn["classData"] = $room;
            }
        }
        return $dataReturn;
    }

    //AM,PHI,VN,TG,TRAINING
    private function getTeacherType($userid) {
        global $DB;
        $sql_user = "SELECT d.data
                FROM {user_info_data} d
                JOIN {user_info_field} f ON f.id = d.fieldid
                WHERE  f.shortname = 'teachertype' AND d.userid = :userid";
        $user = $DB->get_record_sql($sql_user, array('userid' => $userid));
        if ($user) {
            return $user->data;
        } else {
            return "OTHER";
        }
    }

    private function getUserInfo($userid) {
        global $DB;
        //$sql_user = "SELECT u.id, u.firstname, u.lastname, u.email, u.phone1,
        $sql_user = "SELECT u.*,
            (
                SELECT data
                FROM {user_info_data} d
                JOIN {user_info_field} f on f.id = d.fieldid
                WHERE  f.shortname = 'currentlevel' AND d.userid = u.id
            ) as userlv,
            (
                SELECT data
                FROM {user_info_data} d
                JOIN {user_info_field} f on f.id = d.fieldid
                WHERE  f.shortname = 'package' AND d.userid = u.id
            ) as accounttype,
            (
                SELECT data
                FROM {user_info_data} d
                JOIN {user_info_field} f on f.id = d.fieldid
                WHERE  f.shortname = 'studenttype' AND d.userid = u.id
            ) as studenttype,
            (
                SELECT data
                FROM {user_info_data} d
                JOIN {user_info_field} f on f.id = d.fieldid
                WHERE  f.shortname = 'packageparent' AND d.userid = u.id
            ) as packagetype
            FROM {user} u
            WHERE u.id = :userid";
        $user = $DB->get_record_sql($sql_user, array('userid' => $userid));
        /**
         * TungTB:02/10/2015
         * Change package when user is orientation
         */
        if ($user) {
            $user->packagetype = orientation_student_package($userid, $user->packagetype);
        }
        /* End TungTB */

        /**
         * LocMX:05/11/2015
         * Du an PIELE
         */
        if ($user) {
            $user->userlv = piele_student_level($user);
        }
        /* End LocMX */
        return $user;
    }

    private function countFirstTimeVcr($userId) {
        global $DB;
        $sql = "SELECT count(*) as count "
                . "FROM {logsservice_move_user} AS mv "
                . "JOIN {tpebbb} as b ON mv.roomidto=b.id "
                . "WHERE mv.userid = ? AND b.roomtype = 'Room' ";

        $params = Array($userId);
        $countRoom = $DB->get_record_sql($sql, $params);
        if ($countRoom) {
            return $countRoom->count;
        } else {
            return 0;
        }
    }

    function changerTeacherType($userId, $meetingId) {
        $userInfo = $this->getUserInfo($userId);
        $mapping = $this->TpeSettings->ChangerRoomType;
        $changeValue = FALSE;
        if (isset($mapping[$userInfo->packagetype])) {
            global $DB;
            $changeValue = $mapping[$userInfo->packagetype];
            $sql = "SELECT t.id,t.teacher_type "
                    . "FROM {tpebbb} AS b "
                    . "JOIN {tpe_calendar_teach} as t ON b.calendar_code=t.calendar_code "
                    . "WHERE b.id = ? ";
            $params = Array($meetingId);
            $bbbInfo = $DB->get_record_sql($sql, $params);
            if ($bbbInfo && $bbbInfo->teacher_type != $changeValue) {
                $data_insert = new stdClass();
                $data_insert->id = $bbbInfo->id;
                $data_insert->teacher_type = $changeValue;
                $DB->update_record('tpe_calendar_teach', $data_insert);
                return true;
            }
        }
        return false;
    }

    public function get_priority($userId) {
        $userInfo = $this->getUserInfo($userId);
        $pri = time();
        $match = false;
        foreach ($this->TpeSettings->PriorityPackage as $key => $value) {
            if ($userInfo->packagetype == $key) {
                $pri = $value . substr($pri, strlen($value) + 1);
                $match = true;
            }
        }
        if (!$match) {
            $value = $this->TpeSettings->PriorityPackage['OTHER'];
            $pri = $pri = $value . substr($pri, strlen($value) + 1);
        }
        return $pri;
    }

    public function get_vcr_lang($lms_lang) {
        $langConfig = $this->TpeSettings->LangConfigMapping;
        if (isset($langConfig[$lms_lang])) {
            return $langConfig[$lms_lang];
        } else {
            return "";
        }
    }

    function getStudentMark($userId, $context) {
        global $SESSION;
        //Đánh dấu học viên đặc biệt
        $mark = '';
        $userInfo = $this->getUserInfo($userId);
        foreach ($this->TpeSettings->ListMarkUser as $key => $value) {
            if ($userInfo->packagetype == $key) {
                $mark .= $value;
            }
        }
        //Đánh dấu học viên mới
        if ($this->countFirstTimeVcr($userId) == 0) {
            $mark .= $this->TpeSettings->MarkUserNew;
        }

        if (isset($SESSION->pr_usetime) && $SESSION->pr_usetime > 0) { //Là gói thỏa thích
            $time = time();
            $timeRemain = $SESSION->expired_date - $time;
            if ($timeRemain < ($this->TpeSettings->MarkUserOldTime * 86400)) {
                $mark .= $this->TpeSettings->MarkUserOld;
            }
        }
        return $mark;
    }

    function endMeeting($roomid) {
        global $DB, $CFG;
        $bbbInfo = $DB->get_record('tpebbb', Array("id" => $roomid), "*", MUST_EXIST);
        if ($bbbInfo->roomtype != 'ROOM') {
            $this->_bbbServerBaseUrl = $CFG->NAF2;
        }
        $endParams = Array(
            "meetingId" => $roomid,
            "password" => $bbbInfo->moderatorpass,
        );
        return $this->endMeetingWithXmlResponseArray($endParams);
    }

    function checkBalanceBeforeJoin($classType, $roleString, $recentRoom = false) {
        if ($classType == "SC") {
            return true;
        }
        if ($roleString != "student") {
            return true;
        }
        if ($recentRoom) {
            return true;
        }
        $listPlugin = get_plugin_list("local");
        require_once($listPlugin["vcrpayment"] . "/lib.php");
        $payment = new vcrpayment();
        $result = $payment->check_balance_joinvcr();
        if (!isset($result->status)) {
            $result = $this->checkBalanceBeforeJoin($classType, $roleString, $recentRoom);
        }
        return $result;
    }

    function getLastVcr($userid) {
        global $DB;
        $sql_user = "SELECT *
            FROM {logsservice_in_out}
            WHERE userid = :userid
            ORDER BY id DESC
            LIMIT 1";
        $user = $DB->get_record_sql($sql_user, array('userid' => $userid));
        return $user;
    }

    function error($error, $url = '') {
//        global $CFG;
//        if ($CFG->debug == DEBUG_DEVELOPER ||
//                $CFG->debug == DEBUG_ALL) {
        error($error, $url);
//        } else {
//            $url = $CFG->wwwroot . '/local/tpebbb/notify.php?error=' . urlencode ($error) . "&url=" . urlencode($url);
//            redirect($url);
//        }
    }

    function redirect($url, $msg, $time = 0) {
//        global $CFG;
//        if ($CFG->debug == DEBUG_DEVELOPER ||
//                $CFG->debug == DEBUG_ALL) {
        redirect($url, $msg, $time);
//        } else {
//            $url_next = $CFG->wwwroot . '/local/tpebbb/notify.php?'
//                    . 'redirect=' . urlencode($msg) . "&url=" . urlencode($url) . "&delay=" . $time;
//            redirect($url_next);
//        }
    }

    /**
     * ThieuLM: 14/07/2015
     * Get info class by meeting id
     */
    public function getInfoClassByMeetingId($meetingId) {
        global $DB;
        $sql = " SELECT  bbb.id as bbb_id,c.type_class,c.teacher_type, c.level_class
                FROM {tpebbb} bbb
                JOIN {tpe_calendar_teach} c on c.calendar_code = bbb.calendar_code
                WHERE bbb.id=?";
        $param_sql = Array($meetingId);
        $meeting_info = $DB->get_record_sql($sql, $param_sql);
        return $meeting_info;
    }

    /**
     * End...
     */
}

class TpeLearningRoom {

    function __construct() {
        global $LS_KEY, $SC_KEY, $BASIC_KEY, $INTER_KEY, $ADVAN_KEY;
        $LS_KEY = "LS";
        $SC_KEY = "SC";
        $BASIC_KEY = 'Basic';
        $INTER_KEY = 'Inter';
        $ADVAN_KEY = 'Advan';
    }

    /**
     * Lấy thông tin của 1 lớp bbb
     * @global type $DB
     * @param type $param_sql
     * @param type $condition
     * @return type
     */
    function get_info_bbb($param_sql, $condition) {
        global $DB;
        $sql = "SELECT  bbb.id as bbb_id,c.calendar_code as calendar_code,m.id as material_id
            FROM {tpebbb} bbb
            JOIN {tpe_calendar_teach} c on c.calendar_code = bbb.calendar_code
            JOIN {materialservice} m on c.subject_code = m.subject_code
            WHERE bbb.roomtype='Room' AND c.status IN (1,2) " . $condition . "
            ORDER BY bbb.timeavailable ASC";
        $list_bbb_info = $DB->get_records_sql($sql, $param_sql);
        $bbb_info = array();
        if (is_array($list_bbb_info) && count($list_bbb_info)) {
            $arr_bbb_info = (array_values($list_bbb_info));
            $arr_bbb_info = $this->_process_info_bbb($arr_bbb_info);
            $bbb_info = $arr_bbb_info[0];
        }
        return $bbb_info;
    }

    function get_info_bbbs($param_sql, $condition) {
        global $DB;
        $sql = "SELECT  bbb.id as bbb_id,bbb.timeavailable,bbb.subject_code,bbb.timedue,
            m.subject_type,m.objective,m.topic,m.subject,m.class_outline,m.fileurl,m.outline_homework ,m.lesson_plan,m.video_warmup,m.background,m.id as material_id,
            c.type_class,c.calendar_code as calendar_code, c.teacher_id
            FROM {tpebbb} bbb
            JOIN {tpe_calendar_teach} c on c.calendar_code = bbb.calendar_code
            JOIN {materialservice} m on c.subject_code = m.subject_code
            WHERE bbb.roomtype='Room' AND c.status IN (1,2) " . $condition . "
            ORDER BY bbb.timeavailable ASC";
        $list_bbb_info = $DB->get_records_sql($sql, $param_sql);

        return $this->_process_info_bbb($list_bbb_info);
    }

    function get_logs_info_bbb($param_sql, $condition) {
        global $DB;
        $sql = "SELECT  bbb.id as bbb_id,bbb.timeavailable,bbb.subject_code,bbb.timedue,
            log.*,
            m.subject_type,m.objective,m.topic,m.subject,m.class_outline,m.fileurl,m.outline_homework ,m.lesson_plan,m.video_warmup,m.background,m.id as material_id,
            c.type_class,c.calendar_code as calendar_code
            FROM {logsservice_in_out} log
            JOIN {tpebbb} bbb on bbb.id = log.roomid
            JOIN {tpe_calendar_teach} c on c.calendar_code = bbb.calendar_code
            JOIN {materialservice} m on c.subject_code = m.subject_code
            WHERE bbb.roomtype='Room' AND c.status IN (1,2) AND " . $condition . "
            GROUP BY log.roomid";
        $list_log_bbb_info = $DB->get_records_sql($sql, $param_sql);

        return $this->_process_info_bbb($list_log_bbb_info);
    }

    /**
     * Lấy thông tin của một lớp bbb kiểu Livesession
     * @global type $DB
     * @global type $LS_KEY
     * @param type $param_sql_ls
     * @param type $condition
     * @return type
     */
    function get_info_livesession($param_sql_ls, $condition) {
        global $DB, $LS_KEY;
        $sql_ls = "SELECT  bbb.id as bbb_id,c.level_class ,bbb.timeavailable,bbb.subject_code,bbb.timedue,
            m.subject_type,m.objective,m.topic,m.subject,m.class_outline,m.fileurl,m.outline_homework ,m.lesson_plan,m.video_warmup,m.background,m.id as material_id,
            c.type_class,c.calendar_code as calendar_code
            FROM {tpebbb} bbb
            JOIN {tpe_calendar_teach} c on c.calendar_code = bbb.calendar_code
            JOIN {materialservice} m on c.subject_code = m.subject_code
            WHERE bbb.roomtype='Room' AND c.status IN (1,2) AND c.type_class ='" . $LS_KEY . "' AND " . $condition . "
            ORDER BY bbb.timeavailable ASC, c.level_class DESC ";

        $list_ls_info = $DB->get_records_sql($sql_ls, $param_sql_ls);

        $ls_info = array();
        if (is_array($list_ls_info) && count($list_ls_info)) {
            $arr_ls_info = (array_values($list_ls_info));
            $arr_ls_info = $this->_process_info_bbb($arr_ls_info);
            $ls_info = $arr_ls_info[0];
        }
        return $ls_info;
    }

    /** Manhnv them moi
     * Lấy thông tin của một lớp bbb kiểu Livesession
     * @global type $DB
     * @global type $LS_KEY
     * @param type $param_sql_ls
     * @param type $condition
     * @return type
     */
    function get_info_livesession_student($param_sql_ls) {
        global $DB, $LS_KEY;
        $sql_ls = "SELECT bbb.id AS bbb_id,
					 bbb.timeavailable,
					 bbb.subject_code,
					 bbb.timedue,
					 m.subject_type,
					 m.objective,
					 m.topic,
					 m. SUBJECT,
					 m.class_outline,
					 m.fileurl,
					 m.outline_homework,
					 m.lesson_plan,
					 m.video_warmup,
					 m.background,
					 m.id AS material_id,
					 c.type_class,
					 c.calendar_code AS calendar_code
					FROM
					 (
					  SELECT
					   b.id,
					   b.timeavailable,
					   b.subject_code,
					   b.timedue,
					   b.roomtype,
					   b.calendar_code
					  FROM
					   mdl_tpebbb b
					  WHERE
					   b.timeavailable BETWEEN :timeavailable AND :timeavailable_end 
					  AND b.roomtype = 'Room'
					  AND b.course = :course
					 ) as bbb
					JOIN (
					  SELECT
					   cc.type_class,
					   cc.calendar_code,
					   cc.subject_code   
					  FROM
					   mdl_tpe_calendar_teach cc
					  WHERE
					   cc. STATUS IN (1, 2)
					   AND cc.type_class = '" . $LS_KEY . "'
					   AND cc.teacher_type = :teacher_type
					   AND cc.level_class = :level_class
					   AND cc.student_type = :student_type
					  ) as c ON c.calendar_code = bbb.calendar_code
					JOIN mdl_materialservice m ON c.subject_code = m.subject_code
					ORDER BY
					 bbb.timeavailable ASC
					 LIMIT 1";
        $list_ls_info = $DB->get_records_sql($sql_ls, $param_sql_ls);

        $ls_info = array();
        if (is_array($list_ls_info) && count($list_ls_info)) {
            $arr_ls_info = (array_values($list_ls_info));
            $arr_ls_info = $this->_process_info_bbb($arr_ls_info);
            $ls_info = $arr_ls_info[0];
        }
        return $ls_info;
    }

    function get_info_livesessions($param_sql_ls, $condition) {
        global $DB, $LS_KEY;
        $sql_ls = "SELECT  bbb.id as bbb_id,bbb.timeavailable,bbb.subject_code,bbb.timedue,
            m.subject_type,m.objective,m.topic,m.subject,m.class_outline,m.fileurl,m.outline_homework ,m.lesson_plan,m.video_warmup,m.background,m.id as material_id,
            c.type_class,c.calendar_code as calendar_code
            FROM {tpebbb} bbb
            JOIN {tpe_calendar_teach} c on c.calendar_code = bbb.calendar_code
            JOIN {materialservice} m on c.subject_code = m.subject_code
            WHERE bbb.roomtype='Room' AND c.status IN (1,2) AND c.type_class ='" . $LS_KEY . "' AND " . $condition . "
            ORDER BY bbb.timeavailable ASC";
        $list_ls_info = $DB->get_records_sql($sql_ls, $param_sql_ls);

        return $this->_process_info_bbb($list_ls_info);
    }

    /**
     * Lấy thông tin của một lớp bbb kiểu studentcovertion
     * @global type $DB
     * @global type $SC_KEY
     * @param type $param_sql_sc
     * @param type $condition
     * @return type
     */
    function get_info_studentcovertion($param_sql_sc, $condition) {
        global $DB, $SC_KEY;
        $sql_sc = "SELECT  bbb.id as bbb_id,bbb.timeavailable,bbb.subject_code,bbb.timedue,
            m.subject_type,m.objective,m.topic,m.subject,m.class_outline,m.fileurl,m.outline_homework ,m.lesson_plan,m.video_warmup,m.background,m.id as material_id,
            c.type_class,c.calendar_code as calendar_code
            FROM {tpebbb} bbb
            JOIN {tpe_calendar_teach} c on c.calendar_code = bbb.calendar_code
            JOIN {materialservice} m on c.subject_code = m.subject_code
            WHERE bbb.roomtype='Room' AND c.status IN (1,2) AND c.type_class ='" . $SC_KEY . "' AND " . $condition . "
            ORDER BY bbb.timeavailable ASC, c.level_class DESC ";

        $list_sc_info = $DB->get_records_sql($sql_sc, $param_sql_sc);

        $sc_info = array();
        if (is_array($list_sc_info) && count($list_sc_info)) {
            $arr_sc_info = (array_values($list_sc_info));
            $arr_sc_info = $this->_process_info_bbb($arr_sc_info);
            $sc_info = $arr_sc_info[0];
        }
        return $sc_info;
    }

    function get_info_studentcovertions($param_sql_sc, $condition) {
        global $DB, $SC_KEY;
        $sql_sc = "SELECT  bbb.id as bbb_id,bbb.timeavailable,bbb.subject_code,bbb.timedue,
            m.subject_type,m.objective,m.topic,m.subject,m.class_outline,m.fileurl,m.outline_homework ,m.lesson_plan,m.video_warmup,m.background,m.id as material_id,
            c.type_class,c.calendar_code as calendar_code
            FROM {tpebbb} bbb
            JOIN {tpe_calendar_teach} c on c.calendar_code = bbb.calendar_code
            JOIN {materialservice} m on c.subject_code = m.subject_code
            WHERE bbb.roomtype='Room' AND c.status IN (1,2) AND c.type_class ='" . $SC_KEY . "' AND " . $condition . "
            ORDER BY bbb.timeavailable ASC";
        $list_sc_info = $DB->get_records_sql($sql_sc, $param_sql_sc);

        return $this->_process_info_bbb($list_sc_info);
    }

    /**
     * Hàm xử lý dữ liệu của 1 lớp bbb
     * @global type $CFG
     * @param type $bbb_info
     * @return type
     */
    function _process_info_bbb($bbb_info) {
        global $CFG, $USER;
        if (count($bbb_info)) {
            $tpeBBB = new TpeBigBlueButton();
            $settings = $tpeBBB->TpeSettings;
            foreach ($bbb_info as $item) {
                $value_time_change_btn = $settings->DefaultTimeOpenBefore * 60;
                $item->time_now = time();
                $item->value_time_change_btn = $value_time_change_btn;
                /**
                 * 14.10.2015
                 * TungTB: Fix loai HV thai vao truoc 5 phut
                 */
                if ($USER->profile["packageparent"] == 'THAI') {
                    $item->timeavailable = $item->timeavailable - 5 * 60;
                }
                /**
                 * End TungTB
                 */
                $item->timeavailable_late = $item->timeavailable + $value_time_change_btn;
                $item->time_countdown = intval($item->timeavailable) - time();
                $item->link_down_document = ($item->class_outline != '0') ? $item->fileurl . $item->class_outline : "#";
                $item->link_down_homework = ($item->outline_homework != null) ? $item->fileurl . $item->outline_homework : "#";
                $item->link_down_plan = ($item->lesson_plan != '0') ? $item->fileurl . $item->lesson_plan : "#";
                $item->link_video_warmup = ($item->video_warmup != '0') ? ($item->fileurl . $item->video_warmup) : "#";
                $item->link_img = ($item->background) ? $item->fileurl . $item->background : $CFG->wwwroot . '/local/tpelearning/pix/no_live_session.png';
                $item->link_join_class = $tpeBBB->getLinkVcr("LMS", $item->type_class, 0, 'TechTest') . '&joinbbbid=' . $item->bbb_id;
                $item->link_join_class_povh = $tpeBBB->getLinkVcr("LMS", $item->type_class, $item->bbb_id);
                $item->link_join_class_teacher = $tpeBBB->getLinkVcr("LMS", $item->type_class, 0, 'TechTestTeacher') . '&joinbbbid=' . $item->bbb_id;
                $item->link_log = $CFG->wwwroot . '/local/tpelearning/save_log.php';
                $item->testpre_vcr = 'http://qlhl.topicanative.edu.vn/previewPI.html?subject_code=' . $item->subject_code;
                //$item->testpre_vcr = 'http://qlhl.topmito.edu.vn/previewPI.html?subject_code=20141019LSIO';
            }
        }
        return $bbb_info;
    }

}

/**
 * Class for log tpebbb
 */
class logBBB {

    var $table;

    public function logBBB($table) {
        $this->table = $table;
    }

    /**
     * Add log
     */
    public function addLog(stdClass $data) {
        global $DB;
        $insertId = $DB->insert_record($this->table, $data);
        return $insertId;
    }

}

class ratingClass {

    var $table = 'tpebbb_ratingclass';

    public function __contruct() {
        
    }

    public function addRatingClass($data) {
        global $DB;
        $table = $this->table;
        if (is_object($data)) {
            if (isset($data->roomid)) {
                //add
                $data->timecreated = time();
                return $DB->insert_record($table, $data);
            }
        }
        return false;
    }

    public function getRatingClassByRoom($roomId) {
        global $DB;
        if ($roomId) {
            $data = $DB->get_records($this->table, array('roomid' => $roomId));
        } else {
            $data = $DB->get_records($this->table, array());
        }
        return $data;
    }

    public function getRatingClassById($id) {
        global $DB;
        if ($id) {
            return $DB->get_record($this->table, array('id' => $id));
        }
        return false;
    }

    public function getAllRatingClass($where = array()) {
        global $DB;
        return $DB->get_records($this->table, $where);
    }

}
