<?php
require_once("../../../config.php");
global $CFG;
require_once("../lib.php");
require_once("../../tpeadb/locallib.php");
require_once("locallib.php");
date_default_timezone_set('Asia/Ho_Chi_Minh');
$id = optional_param('course', $CFG->tpe_config->courseid, PARAM_INT); // course ID
$perpage = optional_param('perpage', 20, PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);
$teacher_type = optional_param('type', 0, PARAM_TEXT);
$level = optional_param('level', 0, PARAM_TEXT);
$teacher = optional_param('teacher', 0, PARAM_TEXT);
$assistant = optional_param('assistant', 0, PARAM_TEXT);
$code = optional_param('code', '', PARAM_TEXT);
$fromday = optional_param('fromday', date('Y-m-d'), PARAM_TEXT);
$today = optional_param('today', date('Y-m-d'), PARAM_TEXT);
$status_teacher = optional_param('status_teacher', 0, PARAM_TEXT);
$class_type = optional_param('class_type', 0, PARAM_TEXT);
$vcr_type = optional_param('vcr_type', null, PARAM_TEXT);

global $COURSE;
require_login($id, true);
$context = context_course::instance($id);
require_capability('local/tpebbb:classview', $context);
$listPlugins = get_plugin_list("local");
require_once($listPlugins["tpebbb"] . "/lib.php");
$tpeLearning = new nextclass();
$time_now = time();
$param_sql = array(
    'custom_where' => array(),
    'custom_like' => array(),
    'custom_wherenull' => array(),
);
$param_sql['custom_where']['course'] = $id;

$param_sql['custom_where']['timeavailable <='] = $time_now + 15 * 60;
$param_sql['custom_where']['timedue >='] = $time_now;

if ($teacher_type) {
    $param_sql['custom_where']['c.teacher_type'] = $teacher_type;
}
if ($level) {
    $param_sql['custom_where']['level_class'] = $level;
}
if ($teacher) {
    $param_sql['custom_where']['teacher_id'] = $teacher;
}
if ($code) {
    $param_sql['custom_like']['bbb.calendar_code'] = $code;
}
if ($status_teacher && $status_teacher == 1) {
    $param_sql['custom_where']['teacher_id >'] = 0;
} elseif ($status_teacher && $status_teacher == 2) {
    $param_sql['custom_wherenull']['teacher_id'] = '';
}
if ($vcr_type) {
    $param_sql['custom_where']['bbb.vcr_type'] = $vcr_type;
}
// proccess class_type
if ($class_type) { // replace old conds: teacher_type, level_class, student_type
    $cond_class_type = split_class_type($class_type);

    $param_sql['custom_where']['m.teacher_type'] = $cond_class_type["cond_teacher_type"];
    $param_sql['custom_where']['c.level_class'] = $cond_class_type["cond_level_class"];
    $param_sql['custom_where']['m.student_type'] = $cond_class_type["cond_student_type"];

    $class_info = $tpeLearning->get_info_learning_room($param_sql);
} else {
    $class_info = $tpeLearning->get_info_learning_room($param_sql);
}
$aclcount = count($class_info); // no use
//List teacher
$roles = array();
$role_list = $DB->get_records("role", array());
foreach ($role_list as $key => $value) {
    $roles[$value->shortname] = $value;
}
$povhs = array();
$povhs = get_role_users($roles['povh']->id, $context, true);

$list_teacher = $tpeLearning->get_list_teacher_by_type();
$list_teacher_vn = $tpeLearning->get_list_teacher_by_type(array('VN'));
$teacher_am = array();
$teacher_vn = array();
foreach ($list_teacher as $teacher) {
    array_push($teacher_am, $teacher->id);
}
foreach ($list_teacher_vn as $teacher) {
    array_push($teacher_vn, $teacher->id);
}
$popup_vcr_detail = $CFG->wwwroot . "/local/tpebbb/manager/vcr_detail.php";

// init table column
$list_th = array('ID', get_string('created_class_table_head_level', 'local_tpebbb'), get_string('student_type', 'local_tpebbb'),
    get_string('created_class_table_head_time', 'local_tpebbb'), get_string('created_class_table_head_class_type', 'local_tpebbb'),
    get_string('created_class_table_head_teacher', 'local_tpebbb'), get_string('created_class_table_head_assistant', 'local_tpebbb'),
    get_string('povh', 'local_tpebbb'), get_string('created_class_table_head_total_student', 'local_tpebbb'), get_string('created_class_table_head_total_student_joined', 'local_tpebbb'), get_string('created_class_table_head_action', 'local_tpebbb'), 'STT');
$ls_class = array();
$status = array("teacher", "no_teacher", "wrong_teacher");
$room_not_teacher = 0;
$room_not_assistant = 0;

// get real running class from bbb server 
$obj_bbb = new TpeBigBlueButton();
$class_data = $obj_bbb->getMeetingsWithXmlResponseArray();

$class_vcr = Array();
if ($class_data['returncode'] == 'SUCCESS') {
    $class_vcr = $class_data['meetings'];
}

// merge list of class in db and from bbb server
$vcr_ids = array();
foreach ($class_info as $key => $value) {
    $vcr_ids[] = $value->bbb_id;
}
foreach ($class_vcr as $class) {
    $vcr_ids[] = $class['meetingId'];
}

// merge class info ========== giangdv Xóa đoạn code này đi vẫn chạy bình thường
// $vcr_ids = array_unique($vcr_ids);
// $data_array = Array();
// $data_array_other = Array();
// foreach ($vcr_ids as $item) {
// if (isset($class_info[$item])) {
// $classItem = $class_info[$item];
// $data_array[$item] = $classItem;
// } else {
// $classItem = new stdClass();
// $classItem->id = $item;
// $data_array_other[$item] = $classItem;
// }
// }
// get class info from server bbb
if ($vcr_type == 'BBB') {
    try {
        $api = 'getMeetingInfos';
        $apiF = 'tpeapi';
        $param = array('meetingIds' => join(",", $vcr_ids));
        $dataResponse = $obj_bbb->customApiRequest($api, $param, $apiF);

        if ($dataResponse["returncode"] == "SUCCESS") {
            $dataReturn = Array();
            $meetingsInfo = $dataResponse['meetingInfo'];
            if (count($vcr_ids) == 1) {
                $meetingsInfo = Array($meetingsInfo);
            }
            foreach ($meetingsInfo as $value) {
                $fixInfo = $obj_bbb->processMeetingInfo($value);
                // if($class_type){
                // var_dump($fixInfo);
                // exit();
                // }
                $dataReturn[$value["meetingId"]] = $fixInfo;
            }
        } else {
            $dataReturn = Array();
            /* foreach ($listRoom as $item) {
              $dataReturn[$item->id]["userInfoText"] = "SERVER VCR DOWN";
              } */
        }
    } catch (Exception $e) {
        $dataReturn = Array();
        /* foreach ($listRoom as $item) {
          $dataReturn[$item->id]["userInfoText"] = $e->getMessage();
          } */
    }
}

$total_student = 0;
$not_have_teacher = 0;
$not_have_assistant = 0;
$stt_am_b = 1;
$stt_am_i = 1;
$stt_vn_b = 1;
$stt_vn_i = 1;
$stt_vn_sb = 1;
$stt_phi_b = 1;
$stt_phi_i = 1;
$total_number_room = 0;

$symbol_array = array(
    'VIP-AM' => "<font size='3' color='#99CC00'>VIP</font>&nbsp;",
    'THAI' => "<font size='3' color='red'>THAI</font>&nbsp;",
);
// generate view data
foreach ($class_info as $key => $value) {
    $popup_vcr_detail = $popup_vcr_detail_joined = $CFG->wwwroot . "/local/tpebbb/manager/vcr_detail.php";
    $array_student = $array_student_joined = array();
    $array_mod = array();
    $number_student = $number_student_joined = 0;
    $number_mod = 0;
    //datnv 15/02/2016 hien thi so hoc vien hiện có trong lop
    if ($value->vcr_type == 'ADB') {
        // $count_user_moved = count($DB->get_records_sql("select DISTINCT userid from mdl_logsservice_move_user where roomidto = ? ", Array($value->bbb_id)));
        // //datnv số học viên hiện có
        // $number_student = $count_user_moved;
        // // datnv số học viên đã chuyển
        // $number_student_joined = $count_user_moved;

        $value_class_list = $value;

        if (!isset($value_class_list->teacher_id)) {
            $value->teacher_id = 0;
        }
        if (!isset($value_class_list->assistant_id)) {
            $value->assistant_id = 0;
        }

        $user_moved = $DB->get_records_sql("SELECT DISTINCT lm.userid from mdl_logsservice_move_user lm left join mdl_role_assignments ra on(lm.userid=ra.userid) where ra.roleid=5 and roomidto = ? ", Array($value_class_list->bbb_id));

        $number_student_move = count($user_moved);
        $dataLog = get_log_for_running_class($logInAdb, $value_class_list->bbb_id);
        $stringlog = json_encode($dataLog);
        str_replace("session-date-created", "", $stringlog);
        if (isset($stringlog)) {
            $stringa = "report-meeting-attendance-details";
            $log = $dataLog->$stringa;
            $countlog = count($log->row);
            $log_all = array();
            $attendee_login = "attendee-login";
            $date_end = "date-end";
            for ($i = 0; $i < $countlog; $i++) {
                if (!isset($log->row[$i]->$date_end)) {
                    $user = '' . $log->row[$i]->$attendee_login;
                    $log_all[] = array('username' => $user);
                }
            }
            //Đếm số học viên đang trong lớp
            foreach ($user_moved as $key => $user) {
                $url_number_student_move .= "userid[]=" . $user->userid . "&";
                $username_online = $DB->get_records_sql("SELECT u.email from mdl_user u left join mdl_role_assignments ra on(u.id=ra.userid) where ra.roleid=5 and u.id= ?", array($user->userid));
                foreach ($username_online as $key => $value1) {
                    $username = $key;
                }
                for ($i = 0; $i < $countlog; $i++) {
                    if ($log_all[$i]['username'] == $username) {
                        $url_number_student_joint .= "userid[]=" . $user->userid . "&";
                        $number_student_joint++;
                        unset($log_all[$i]);
                    }
                }
            }
            //Hiển thị danh sách các PO trong lớp
            foreach ($log_all as $key => $log_po) {
                $username_po = $DB->get_records_sql("SELECT u.firstname,u.lastname FROM mdl_user u LEFT JOIN mdl_role_assignments r on(u.id = r.userid) WHERE (r.roleid = 9 or r.roleid = 14 or r.roleid = 15) and u.email= ?", array($log_po['username']));
                if (count($username_po)) {
                    unset($log_all[$key]);
                    foreach ($username_po as $key1 => $value1) {
                        $po_list .= $value1->lastname . " " . $value1->firstname . "<br>";
                    }
                }
            }
            //Xem giáo viên có Online hay ko
            if ($value_class_list->teacher_id != '') {
                $teacher_username_array = $DB->get_records_sql("SELECT email FROM mdl_user WHERE id= ?", array($value_class_list->teacher_id));
                foreach ($teacher_username_array as $key => $value1) {
                    $teacher_username = $key;
                }
                for ($i = 0; $i < $countlog; $i++) {
                    if ($log_all[$i]['username'] == $teacher_username) {
                        $class_teacher = 'teacher';
                        unset($log_all[$i]);
                        break;
                    }
                }
            }
            //Xem trợ giảng có Online hay ko
            if ($value_class_list->assistant_id != '') {
                $teacher_username_array = $DB->get_records_sql("SELECT email FROM mdl_user WHERE id= ?", array($value_class_list->assistant_id));
                foreach ($teacher_username_array as $key => $value1) {
                    $teacher_username = $key;
                }
                for ($i = 0; $i < $countlog; $i++) {
                    if ($log_all[$i]['username'] == $teacher_username) {
                        $class_assistant = 'teacher';
                        unset($log_all[$i]);
                        break;
                    }
                }
            }
            if (count($log_all)) {
                if ($class_teacher != 'teacher') {
                    foreach ($log_all as $key => $log_po) {
                        $username_po = $DB->get_records_sql("SELECT u.firstname,u.lastname FROM mdl_user u LEFT JOIN mdl_role_assignments r on(u.id = r.userid) WHERE (r.roleid = 3 or r.roleid = 11 or r.roleid = 12) and u.email= ?", array($log_po['username']));
                        if (count($username_po)) {
                            unset($log_all[$key]);
                            foreach ($username_po as $key1 => $value1) {
                                $teacher_name = $value1->lastname . " " . $value1->firstname;
                                break;
                            }
                            $class_teacher = 'teacher';
                            break;
                        }
                    }
                }
                if ($class_assistant != 'teacher') {
                    foreach ($log_all as $key => $log_po) {
                        $username_po = $DB->get_records_sql("SELECT u.firstname,u.lastname FROM mdl_user u  WHERE u.email= ?", array($log_po['username']));
                        if (count($username_po)) {
                            unset($log_all[$key]);
                            foreach ($username_po as $key1 => $value1) {
                                $assistant_name = $value1->lastname . " " . $value1->firstname;
                                break;
                            }
                            $class_assistant = 'teacher';
                            break;
                        }
                    }
                }
            }
        }
    }
    //end datnv
    if (isset($dataReturn[$key])) {
        $popup_vcr_detail .= "?";
        $popup_vcr_detail_joined .= "?";
        if (isset($dataReturn[$key]['liveUsersView']) && count($dataReturn[$key]['liveUsersView']) > 0 && !empty($dataReturn[$key]['liveUsersView'])) {
            foreach ($dataReturn[$key]['liveUsersView'] as $liveUsersView) {
                $number_student++;
                $array_student[] = "userid[]=" . $liveUsersView['userExternalId'];
            }
            $popup_vcr_detail .= implode("&", $array_student);
        }
        if (isset($dataReturn[$key]['joinedUsersView']) && count($dataReturn[$key]['joinedUsersView']) > 0 && !empty($dataReturn[$key]['joinedUsersView'])) {
            foreach ($dataReturn[$key]['joinedUsersView'] as $joinedUsersView) {
                $number_student_joined++;
                $array_student_joined[] = "userid[]=" . $joinedUsersView['userExternalId'];
            }
            $popup_vcr_detail_joined .= implode("&", $array_student_joined);
        }
        if (isset($dataReturn[$key]['liveUsersMod']) && count($dataReturn[$key]['liveUsersMod']) > 0 && !empty($dataReturn[$key]['liveUsersMod'])) {
            foreach ($dataReturn[$key]['liveUsersMod'] as $liveUsersMod) {
                $array_mod[$liveUsersMod['userExternalId']] = $liveUsersMod['userExternalId'];
            }
        }
    }
    $data_tmp = array();
    if ($value->class_fast == '2') {
        $data_tmp['bbb_id'] = "<div title='" . get_string('create_fast_class', 'local_tpebbb') . "' style='background:green;color:#fff;cursor:pointer;'>" . $value->bbb_id . ' - ' . $value->vcr_type . "</div>";
    } else {
        if ($value->student_type == "TL") {
            $data_tmp['bbb_id'] = "<span style='color: #ff0000;'>" . $value->bbb_id . ' - ' . $value->vcr_type . "</span>";
        } else {
            $data_tmp['bbb_id'] = $value->bbb_id . ' - ' . $value->vcr_type;
        }
    }
    $data_tmp['level_class'] = "<div title='{$value->calendar_code}'>" . $value->level_class . "</div>";
    $data_tmp['student_type'] = "<div title='{$value->student_type}'>" . $value->student_type . "</div>";
    $data_tmp['timeavailable'] = date("H:i d-m-Y", $value->timeavailable);
    $symbol = $symbol_array[$value->teacher_type_changed];
    $data_tmp['type_class'] = "<div>" . $symbol . $value->type_class . " - " . $value->teacher_type . "</div>";
    $class_state_teacher = 'no_teacher';
    $class_state_assistant = 'no_teacher';
    $teacher_name = "";
    $assistant_name = "";
    $teacher_name_assign = $value->teacher;
    $assistant_name_assign = $value->assistant;

    if (isset($dataReturn[$key]) && isset($dataReturn[$key]['teacherReal']) && strlen($dataReturn[$key]['teacherReal']) > 0) {
        $class_state_teacher = 'teacher';
        $teacher_name .= $dataReturn[$key]['teacherReal'] . "<br>";
    }
    if (isset($dataReturn[$key]) && isset($dataReturn[$key]['assistantReal']) && strlen($dataReturn[$key]['assistantReal']) > 0) {
        $class_state_assistant = 'teacher';
        $assistant_name .= $dataReturn[$key]['assistantReal'] . "<br>";
    }
    if ($teacher_name == "") {
        $teacher_name = $teacher_name_assign;
        $not_have_teacher += 1;
    }
    if ($assistant_name == "" && $value->type_class == 'LS' && $value->level_class == 'basic') {
        $assistant_name = $assistant_name_assign;
        $not_have_assistant += 1;
    }
    if ($value->level_class == 'inter' || $value->teacher_type == 'VN') {
        $class_state_assistant = 'teacher';
    }
    if (trim($value->vcr_type) == 'ADB') {
        $class_state_teacher = $class_teacher;
        $class_state_assistant = $class_assistant;
        $number_student = $number_student_move;
        $number_student_joined = $number_student_joint;
    }
    $data_tmp['teacher_id'] = "<div class='teacher_status " . $class_state_teacher . "' title='" . $value->teacher . "'>";
    $data_tmp['teacher_id'] .= $teacher_name . "</div>";
    $data_tmp['assistant_id'] = "<div class='teacher_status " . $class_state_assistant . "' title='" . $value->assistant . "'>";
    $data_tmp['assistant_id'] .= $assistant_name;
    $data_tmp['assistant_id'] .= "</div>";
    $data_tmp['povh'] = "";
    foreach ($array_mod as $key => $mod) {
        if (isset($povhs[$key])) {
            if ($key == $povhs[$key]->id) {
                $data_tmp['povh'] .= $povhs[$key]->lastname . " " . $povhs[$key]->firstname . "<br>";
            }
        }
    }
    $total_student += $number_student;
    $data_tmp['number_student'] = "<span class='e_colorbox' style='cursor:pointer;' href='" . $popup_vcr_detail . "'>" . $number_student . "</span>";
    $data_tmp['number_student_joined'] = "<span class='e_colorbox' style='cursor:pointer;' href='" . $popup_vcr_detail_joined . "'>" . $number_student_joined . "</span>";
    if ($value->vcr_type == "ADB") {
        // $data_tmp['action'] .= "<a target='_blank' href='http://" . $_SERVER["HTTP_HOST"] . "/local/tpebbb/adb_client.php?act=join&meeturl=" . str_replace("/", "", $room_adb->meeturl) . "&studentId=" . $USER->id . "'><img title='Vào lớp' src='" . $CFG->wwwroot . "/local/tpebbb/manager/images/icon_go_class.png" . "'></a>";
        $data_tmp['action'] = "<a target='_blank' href='http://" . $_SERVER["HTTP_HOST"] . "/local/tpebbb/adb_client.php?act=join&class_id=" . $value->bbb_id . "&studentId=" . $USER->id . "'><img title='Vào lớp' src='" . $CFG->wwwroot . "/local/tpebbb/manager/images/icon_go_class.png" . "'></a>";
        // $data_tmp['action'] .= "<a href='' data-toggle='modal' id='" . $value->bbb_id . "' data-href='" . $CFG->wwwroot . "/local/tpebbb/manager/form_msg_error.php" . "" . "' data-target='.msg_error' class='e_msg_error'> "
        //         . "<img title='Báo lỗi' src='" . $CFG->wwwroot . "/local/tpebbb/manager/images/Speaker_Icon.gif" . "'></a>";
    } else {
        $data_tmp['action'] = "<a target='_blank' href='" . $value->link_join_class_povh . "'><img title='" . get_string('enter_class', 'local_tpebbb') . "' src='" . $CFG->wwwroot . "/local/tpebbb/manager/images/icon_go_class.png" . "'></a>";
    }
    // echo '<pre>'; var_dump($value);
    $total_number_room ++;

    if ($value->teacher_type == 'AM' && $value->level_class == 'basic') {
        if ($stt_am_b == 1) {
            $bbb_id_am_b = $value->bbb_id;
            $data_tmp['stt'] = 1;
        } else {
            $data_tmp['stt'] = $value->bbb_id - $bbb_id_am_b + 1;
        }
        $stt_am_b++;
    } else if ($value->teacher_type == 'AM' && $value->level_class == 'inter') {
        if ($stt_am_i == 1) {
            $bbb_id_am_i = $value->bbb_id;
            $data_tmp['stt'] = 1;
        } else {
            $data_tmp['stt'] = $value->bbb_id - $bbb_id_am_i + 1;
        }
        $stt_am_i++;
    } else if ($value->teacher_type == 'VN' && $value->level_class == 'basic') {
        $data_tmp['stt'] = $stt_vn_b;
        $stt_vn_b++;
    } else if ($value->teacher_type == 'VN' && $value->level_class == 'inter') {
        $data_tmp['stt'] = $stt_vn_i;
        $stt_vn_i++;
    } else if ($value->teacher_type == 'VN' && $value->level_class == 'sbasic') {
        $data_tmp['stt'] = $stt_vn_sb;
        $stt_vn_sb++;
    } else if ($value->teacher_type == 'PHI' && $value->level_class == 'basic') {
        $data_tmp['stt'] = $stt_phi_b;
        $stt_phi_b++;
    } else if ($value->teacher_type == 'PHI' && $value->level_class == 'inter') {
        $data_tmp['stt'] = $stt_phi_i;
        $stt_phi_i++;
    } else {
        $total_number_room --;
        $value->teacher_type = 'z' . $value->teacher_type;
        $data_tmp['stt'] = '';
    }
    $ls_class[$value->teacher_type . '_' . $value->level_class . '_' . $value->student_type][] = $data_tmp;
}
foreach ($class_info as $key => $value) {
    $popup_vcr_detail = $popup_vcr_detail_joined = $CFG->wwwroot . "/local/tpebbb/manager/vcr_detail.php";
    $array_student = $array_student_joined = array();
    $array_mod = array();
    $number_student = $number_student_joined = 0;
    $number_mod = 0;
    //datnv 15/02/2016 hien thi so hoc vien hiện có trong lop
    if ($value->vcr_type == 'ADB') {
        // $count_user_moved = count($DB->get_records_sql("select DISTINCT userid from mdl_logsservice_move_user where roomidto = ? ", Array($value->bbb_id)));
        // //datnv số học viên hiện có
        // $number_student = $count_user_moved;
        // // datnv số học viên đã chuyển
        // $number_student_joined = $count_user_moved;

        $value_class_list = $value;

        if (!isset($value_class_list->teacher_id)) {
            $value->teacher_id = 0;
        }
        if (!isset($value_class_list->assistant_id)) {
            $value->assistant_id = 0;
        }

        $user_moved = $DB->get_records_sql("SELECT DISTINCT lm.userid from mdl_logsservice_move_user lm left join mdl_role_assignments ra on(lm.userid=ra.userid) where ra.roleid=5 and roomidto = ? ", Array($value_class_list->bbb_id));

        $number_student_move = count($user_moved);
        $dataLog = get_log_for_running_class($logInAdb, $value_class_list->bbb_id);
        $stringlog = json_encode($dataLog);
        str_replace("session-date-created", "", $stringlog);
        if (isset($stringlog)) {
            $stringa = "report-meeting-attendance-details";
            $log = $dataLog->$stringa;
            $countlog = count($log->row);
            $log_all = array();
            $attendee_login = "attendee-login";
            $date_end = "date-end";
            for ($i = 0; $i < $countlog; $i++) {
                if (!isset($log->row[$i]->$date_end)) {
                    $user = '' . $log->row[$i]->$attendee_login;
                    $log_all[] = array('username' => $user);
                }
            }
            //Đếm số học viên đang trong lớp
            foreach ($user_moved as $key => $user) {
                $url_number_student_move .= "userid[]=" . $user->userid . "&";
                $username_online = $DB->get_records_sql("SELECT u.email from mdl_user u left join mdl_role_assignments ra on(u.id=ra.userid) where ra.roleid=5 and u.id= ?", array($user->userid));
                foreach ($username_online as $key => $value1) {
                    $username = $key;
                }
                for ($i = 0; $i < $countlog; $i++) {
                    if ($log_all[$i]['username'] == $username) {
                        $url_number_student_joint .= "userid[]=" . $user->userid . "&";
                        $number_student_joint++;
                        unset($log_all[$i]);
                    }
                }
            }
            //Hiển thị danh sách các PO trong lớp
            foreach ($log_all as $key => $log_po) {
                $username_po = $DB->get_records_sql("SELECT u.firstname,u.lastname FROM mdl_user u LEFT JOIN mdl_role_assignments r on(u.id = r.userid) WHERE (r.roleid = 9 or r.roleid = 14 or r.roleid = 15) and u.email= ?", array($log_po['username']));
                if (count($username_po)) {
                    unset($log_all[$key]);
                    foreach ($username_po as $key1 => $value1) {
                        $po_list .= $value1->lastname . " " . $value1->firstname . "<br>";
                    }
                }
            }
            //Xem giáo viên có Online hay ko
            if ($value_class_list->teacher_id != '') {
                $teacher_username_array = $DB->get_records_sql("SELECT email FROM mdl_user WHERE id= ?", array($value_class_list->teacher_id));
                foreach ($teacher_username_array as $key => $value1) {
                    $teacher_username = $key;
                }
                for ($i = 0; $i < $countlog; $i++) {
                    if ($log_all[$i]['username'] == $teacher_username) {
                        $class_teacher = 'teacher';
                        unset($log_all[$i]);
                        break;
                    }
                }
            }
            //Xem trợ giảng có Online hay ko
            if ($value_class_list->assistant_id != '') {
                $teacher_username_array = $DB->get_records_sql("SELECT email FROM mdl_user WHERE id= ?", array($value_class_list->assistant_id));
                foreach ($teacher_username_array as $key => $value1) {
                    $teacher_username = $key;
                }
                for ($i = 0; $i < $countlog; $i++) {
                    if ($log_all[$i]['username'] == $teacher_username) {
                        $class_assistant = 'teacher';
                        unset($log_all[$i]);
                        break;
                    }
                }
            }
            if (count($log_all)) {
                if ($class_teacher != 'teacher') {
                    foreach ($log_all as $key => $log_po) {
                        $username_po = $DB->get_records_sql("SELECT u.firstname,u.lastname FROM mdl_user u LEFT JOIN mdl_role_assignments r on(u.id = r.userid) WHERE (r.roleid = 3 or r.roleid = 11 or r.roleid = 12) and u.email= ?", array($log_po['username']));
                        if (count($username_po)) {
                            unset($log_all[$key]);
                            foreach ($username_po as $key1 => $value1) {
                                $teacher_name = $value1->lastname . " " . $value1->firstname;
                                break;
                            }
                            $class_teacher = 'teacher';
                            break;
                        }
                    }
                }
                if ($class_assistant != 'teacher') {
                    foreach ($log_all as $key => $log_po) {
                        $username_po = $DB->get_records_sql("SELECT u.firstname,u.lastname FROM mdl_user u  WHERE u.email= ?", array($log_po['username']));
                        if (count($username_po)) {
                            unset($log_all[$key]);
                            foreach ($username_po as $key1 => $value1) {
                                $assistant_name = $value1->lastname . " " . $value1->firstname;
                                break;
                            }
                            $class_assistant = 'teacher';
                            break;
                        }
                    }
                }
            }
        }
    }
    //end datnv
    if (isset($dataReturn[$key])) {
        $popup_vcr_detail .= "?";
        $popup_vcr_detail_joined .= "?";
        if (isset($dataReturn[$key]['liveUsersView']) && count($dataReturn[$key]['liveUsersView']) > 0 && !empty($dataReturn[$key]['liveUsersView'])) {
            foreach ($dataReturn[$key]['liveUsersView'] as $liveUsersView) {
                $number_student++;
                $array_student[] = "userid[]=" . $liveUsersView['userExternalId'];
            }
            $popup_vcr_detail .= implode("&", $array_student);
        }
        if (isset($dataReturn[$key]['joinedUsersView']) && count($dataReturn[$key]['joinedUsersView']) > 0 && !empty($dataReturn[$key]['joinedUsersView'])) {
            foreach ($dataReturn[$key]['joinedUsersView'] as $joinedUsersView) {
                $number_student_joined++;
                $array_student_joined[] = "userid[]=" . $joinedUsersView['userExternalId'];
            }
            $popup_vcr_detail_joined .= implode("&", $array_student_joined);
        }
        if (isset($dataReturn[$key]['liveUsersMod']) && count($dataReturn[$key]['liveUsersMod']) > 0 && !empty($dataReturn[$key]['liveUsersMod'])) {
            foreach ($dataReturn[$key]['liveUsersMod'] as $liveUsersMod) {
                $array_mod[$liveUsersMod['userExternalId']] = $liveUsersMod['userExternalId'];
            }
        }
    }
    $data_tmp = array();
    if ($value->class_fast == '2') {
        $data_tmp['bbb_id'] = "<div title='" . get_string('create_fast_class', 'local_tpebbb') . "' style='background:green;color:#fff;cursor:pointer;'>" . $value->bbb_id . ' - ' . $value->vcr_type . "</div>";
    } else {
        if ($value->student_type == "TL") {
            $data_tmp['bbb_id'] = "<span style='color: #ff0000;'>" . $value->bbb_id . ' - ' . $value->vcr_type . "</span>";
        } else {
            $data_tmp['bbb_id'] = $value->bbb_id . ' - ' . $value->vcr_type;
        }
    }
    $data_tmp['level_class'] = "<div title='{$value->calendar_code}'>" . $value->level_class . "</div>";
    $data_tmp['student_type'] = "<div title='{$value->student_type}'>" . $value->student_type . "</div>";
    $data_tmp['timeavailable'] = date("H:i d-m-Y", $value->timeavailable);
    $symbol = $symbol_array[$value->teacher_type_changed];
    $data_tmp['type_class'] = "<div>" . $symbol . $value->type_class . " - " . $value->teacher_type . "</div>";
    $class_state_teacher = 'no_teacher';
    $class_state_assistant = 'no_teacher';
    $teacher_name = "";
    $assistant_name = "";
    $teacher_name_assign = $value->teacher;
    $assistant_name_assign = $value->assistant;

    if (isset($dataReturn[$key]) && isset($dataReturn[$key]['teacherReal']) && strlen($dataReturn[$key]['teacherReal']) > 0) {
        $class_state_teacher = 'teacher';
        $teacher_name .= $dataReturn[$key]['teacherReal'] . "<br>";
    }
    if (isset($dataReturn[$key]) && isset($dataReturn[$key]['assistantReal']) && strlen($dataReturn[$key]['assistantReal']) > 0) {
        $class_state_assistant = 'teacher';
        $assistant_name .= $dataReturn[$key]['assistantReal'] . "<br>";
    }
    if ($teacher_name == "") {
        $teacher_name = $teacher_name_assign;
        $not_have_teacher += 1;
    }
    if ($assistant_name == "" && $value->type_class == 'LS' && $value->level_class == 'basic') {
        $assistant_name = $assistant_name_assign;
        $not_have_assistant += 1;
    }
    if ($value->level_class == 'inter' || $value->teacher_type == 'VN') {
        $class_state_assistant = 'teacher';
    }
    if (trim($value->vcr_type) == 'ADB') {
        $class_state_teacher = $class_teacher;
        $class_state_assistant = $class_assistant;
        $number_student = $number_student_move;
        $number_student_joined = $number_student_joint;
    }
    $data_tmp['teacher_id'] = "<div class='teacher_status " . $class_state_teacher . "' title='" . $value->teacher . "'>";
    $data_tmp['teacher_id'] .= $teacher_name . "</div>";
    $data_tmp['assistant_id'] = "<div class='teacher_status " . $class_state_assistant . "' title='" . $value->assistant . "'>";
    $data_tmp['assistant_id'] .= $assistant_name;
    $data_tmp['assistant_id'] .= "</div>";
    $data_tmp['povh'] = "";
    foreach ($array_mod as $key => $mod) {
        if (isset($povhs[$key])) {
            if ($key == $povhs[$key]->id) {
                $data_tmp['povh'] .= $povhs[$key]->lastname . " " . $povhs[$key]->firstname . "<br>";
            }
        }
    }
    $total_student += $number_student;
    $data_tmp['number_student'] = "<span class='e_colorbox' style='cursor:pointer;' href='" . $popup_vcr_detail . "'>" . $number_student . "</span>";
    $data_tmp['number_student_joined'] = "<span class='e_colorbox' style='cursor:pointer;' href='" . $popup_vcr_detail_joined . "'>" . $number_student_joined . "</span>";
    if ($value->vcr_type == "ADB") {
        // $data_tmp['action'] .= "<a target='_blank' href='http://" . $_SERVER["HTTP_HOST"] . "/local/tpebbb/adb_client.php?act=join&meeturl=" . str_replace("/", "", $room_adb->meeturl) . "&studentId=" . $USER->id . "'><img title='Vào lớp' src='" . $CFG->wwwroot . "/local/tpebbb/manager/images/icon_go_class.png" . "'></a>";
        $data_tmp['action'] = "<a target='_blank' href='http://" . $_SERVER["HTTP_HOST"] . "/local/tpebbb/adb_client.php?act=join&class_id=" . $value->bbb_id . "&studentId=" . $USER->id . "'><img title='Vào lớp' src='" . $CFG->wwwroot . "/local/tpebbb/manager/images/icon_go_class.png" . "'></a>";
        // $data_tmp['action'] .= "<a href='' data-toggle='modal' id='" . $value->bbb_id . "' data-href='" . $CFG->wwwroot . "/local/tpebbb/manager/form_msg_error.php" . "" . "' data-target='.msg_error' class='e_msg_error'> "
        //         . "<img title='Báo lỗi' src='" . $CFG->wwwroot . "/local/tpebbb/manager/images/Speaker_Icon.gif" . "'></a>";
    } else {
        $data_tmp['action'] = "<a target='_blank' href='" . $value->link_join_class_povh . "'><img title='" . get_string('enter_class', 'local_tpebbb') . "' src='" . $CFG->wwwroot . "/local/tpebbb/manager/images/icon_go_class.png" . "'></a>";
    }
    // echo '<pre>'; var_dump($value);
    $total_number_room ++;

    if ($value->teacher_type == 'AM' && $value->level_class == 'basic') {
        if ($stt_am_b == 1) {
            $bbb_id_am_b = $value->bbb_id;
            $data_tmp['stt'] = 1;
        } else {
            $data_tmp['stt'] = $value->bbb_id - $bbb_id_am_b + 1;
        }
        $stt_am_b++;
    } else if ($value->teacher_type == 'AM' && $value->level_class == 'inter') {
        if ($stt_am_i == 1) {
            $bbb_id_am_i = $value->bbb_id;
            $data_tmp['stt'] = 1;
        } else {
            $data_tmp['stt'] = $value->bbb_id - $bbb_id_am_i + 1;
        }
        $stt_am_i++;
    } else if ($value->teacher_type == 'VN' && $value->level_class == 'basic') {
        $data_tmp['stt'] = $stt_vn_b;
        $stt_vn_b++;
    } else if ($value->teacher_type == 'VN' && $value->level_class == 'inter') {
        $data_tmp['stt'] = $stt_vn_i;
        $stt_vn_i++;
    } else if ($value->teacher_type == 'VN' && $value->level_class == 'sbasic') {
        $data_tmp['stt'] = $stt_vn_sb;
        $stt_vn_sb++;
    } else if ($value->teacher_type == 'PHI' && $value->level_class == 'basic') {
        $data_tmp['stt'] = $stt_phi_b;
        $stt_phi_b++;
    } else if ($value->teacher_type == 'PHI' && $value->level_class == 'inter') {
        $data_tmp['stt'] = $stt_phi_i;
        $stt_phi_i++;
    } else {
        $total_number_room --;
        $value->teacher_type = 'z' . $value->teacher_type;
        $data_tmp['stt'] = '';
    }
    $ls_class[$value->teacher_type . '_' . $value->level_class . '_' . $value->student_type][] = $data_tmp;
}
foreach ($class_info as $key => $value) {
    $popup_vcr_detail = $popup_vcr_detail_joined = $CFG->wwwroot . "/local/tpebbb/manager/vcr_detail.php";
    $array_student = $array_student_joined = array();
    $array_mod = array();
    $number_student = $number_student_joined = 0;
    $number_mod = 0;
    //datnv 15/02/2016 hien thi so hoc vien hiện có trong lop
    if ($value->vcr_type == 'ADB') {
        // $count_user_moved = count($DB->get_records_sql("select DISTINCT userid from mdl_logsservice_move_user where roomidto = ? ", Array($value->bbb_id)));
        // //datnv số học viên hiện có
        // $number_student = $count_user_moved;
        // // datnv số học viên đã chuyển
        // $number_student_joined = $count_user_moved;

        $value_class_list = $value;

        if (!isset($value_class_list->teacher_id)) {
            $value->teacher_id = 0;
        }
        if (!isset($value_class_list->assistant_id)) {
            $value->assistant_id = 0;
        }

        $user_moved = $DB->get_records_sql("SELECT DISTINCT lm.userid from mdl_logsservice_move_user lm left join mdl_role_assignments ra on(lm.userid=ra.userid) where ra.roleid=5 and roomidto = ? ", Array($value_class_list->bbb_id));

        $number_student_move = count($user_moved);
        $dataLog = get_log_for_running_class($logInAdb, $value_class_list->bbb_id);
        $stringlog = json_encode($dataLog);
        str_replace("session-date-created", "", $stringlog);
        if (isset($stringlog)) {
            $stringa = "report-meeting-attendance-details";
            $log = $dataLog->$stringa;
            $countlog = count($log->row);
            $log_all = array();
            $attendee_login = "attendee-login";
            $date_end = "date-end";
            for ($i = 0; $i < $countlog; $i++) {
                if (!isset($log->row[$i]->$date_end)) {
                    $user = '' . $log->row[$i]->$attendee_login;
                    $log_all[] = array('username' => $user);
                }
            }
            //Đếm số học viên đang trong lớp
            foreach ($user_moved as $key => $user) {
                $url_number_student_move .= "userid[]=" . $user->userid . "&";
                $username_online = $DB->get_records_sql("SELECT u.email from mdl_user u left join mdl_role_assignments ra on(u.id=ra.userid) where ra.roleid=5 and u.id= ?", array($user->userid));
                foreach ($username_online as $key => $value1) {
                    $username = $key;
                }
                for ($i = 0; $i < $countlog; $i++) {
                    if ($log_all[$i]['username'] == $username) {
                        $url_number_student_joint .= "userid[]=" . $user->userid . "&";
                        $number_student_joint++;
                        unset($log_all[$i]);
                    }
                }
            }
            //Hiển thị danh sách các PO trong lớp
            foreach ($log_all as $key => $log_po) {
                $username_po = $DB->get_records_sql("SELECT u.firstname,u.lastname FROM mdl_user u LEFT JOIN mdl_role_assignments r on(u.id = r.userid) WHERE (r.roleid = 9 or r.roleid = 14 or r.roleid = 15) and u.email= ?", array($log_po['username']));
                if (count($username_po)) {
                    unset($log_all[$key]);
                    foreach ($username_po as $key1 => $value1) {
                        $po_list .= $value1->lastname . " " . $value1->firstname . "<br>";
                    }
                }
            }
            //Xem giáo viên có Online hay ko
            if ($value_class_list->teacher_id != '') {
                $teacher_username_array = $DB->get_records_sql("SELECT email FROM mdl_user WHERE id= ?", array($value_class_list->teacher_id));
                foreach ($teacher_username_array as $key => $value1) {
                    $teacher_username = $key;
                }
                for ($i = 0; $i < $countlog; $i++) {
                    if ($log_all[$i]['username'] == $teacher_username) {
                        $class_teacher = 'teacher';
                        unset($log_all[$i]);
                        break;
                    }
                }
            }
            //Xem trợ giảng có Online hay ko
            if ($value_class_list->assistant_id != '') {
                $teacher_username_array = $DB->get_records_sql("SELECT email FROM mdl_user WHERE id= ?", array($value_class_list->assistant_id));
                foreach ($teacher_username_array as $key => $value1) {
                    $teacher_username = $key;
                }
                for ($i = 0; $i < $countlog; $i++) {
                    if ($log_all[$i]['username'] == $teacher_username) {
                        $class_assistant = 'teacher';
                        unset($log_all[$i]);
                        break;
                    }
                }
            }
            if (count($log_all)) {
                if ($class_teacher != 'teacher') {
                    foreach ($log_all as $key => $log_po) {
                        $username_po = $DB->get_records_sql("SELECT u.firstname,u.lastname FROM mdl_user u LEFT JOIN mdl_role_assignments r on(u.id = r.userid) WHERE (r.roleid = 3 or r.roleid = 11 or r.roleid = 12) and u.email= ?", array($log_po['username']));
                        if (count($username_po)) {
                            unset($log_all[$key]);
                            foreach ($username_po as $key1 => $value1) {
                                $teacher_name = $value1->lastname . " " . $value1->firstname;
                                break;
                            }
                            $class_teacher = 'teacher';
                            break;
                        }
                    }
                }
                if ($class_assistant != 'teacher') {
                    foreach ($log_all as $key => $log_po) {
                        $username_po = $DB->get_records_sql("SELECT u.firstname,u.lastname FROM mdl_user u  WHERE u.email= ?", array($log_po['username']));
                        if (count($username_po)) {
                            unset($log_all[$key]);
                            foreach ($username_po as $key1 => $value1) {
                                $assistant_name = $value1->lastname . " " . $value1->firstname;
                                break;
                            }
                            $class_assistant = 'teacher';
                            break;
                        }
                    }
                }
            }
        }
    }
    //end datnv
    if (isset($dataReturn[$key])) {
        $popup_vcr_detail .= "?";
        $popup_vcr_detail_joined .= "?";
        if (isset($dataReturn[$key]['liveUsersView']) && count($dataReturn[$key]['liveUsersView']) > 0 && !empty($dataReturn[$key]['liveUsersView'])) {
            foreach ($dataReturn[$key]['liveUsersView'] as $liveUsersView) {
                $number_student++;
                $array_student[] = "userid[]=" . $liveUsersView['userExternalId'];
            }
            $popup_vcr_detail .= implode("&", $array_student);
        }
        if (isset($dataReturn[$key]['joinedUsersView']) && count($dataReturn[$key]['joinedUsersView']) > 0 && !empty($dataReturn[$key]['joinedUsersView'])) {
            foreach ($dataReturn[$key]['joinedUsersView'] as $joinedUsersView) {
                $number_student_joined++;
                $array_student_joined[] = "userid[]=" . $joinedUsersView['userExternalId'];
            }
            $popup_vcr_detail_joined .= implode("&", $array_student_joined);
        }
        if (isset($dataReturn[$key]['liveUsersMod']) && count($dataReturn[$key]['liveUsersMod']) > 0 && !empty($dataReturn[$key]['liveUsersMod'])) {
            foreach ($dataReturn[$key]['liveUsersMod'] as $liveUsersMod) {
                $array_mod[$liveUsersMod['userExternalId']] = $liveUsersMod['userExternalId'];
            }
        }
    }
    $data_tmp = array();
    if ($value->class_fast == '2') {
        $data_tmp['bbb_id'] = "<div title='" . get_string('create_fast_class', 'local_tpebbb') . "' style='background:green;color:#fff;cursor:pointer;'>" . $value->bbb_id . ' - ' . $value->vcr_type . "</div>";
    } else {
        if ($value->student_type == "TL") {
            $data_tmp['bbb_id'] = "<span style='color: #ff0000;'>" . $value->bbb_id . ' - ' . $value->vcr_type . "</span>";
        } else {
            $data_tmp['bbb_id'] = $value->bbb_id . ' - ' . $value->vcr_type;
        }
    }
    $data_tmp['level_class'] = "<div title='{$value->calendar_code}'>" . $value->level_class . "</div>";
    $data_tmp['student_type'] = "<div title='{$value->student_type}'>" . $value->student_type . "</div>";
    $data_tmp['timeavailable'] = date("H:i d-m-Y", $value->timeavailable);
    $symbol = $symbol_array[$value->teacher_type_changed];
    $data_tmp['type_class'] = "<div>" . $symbol . $value->type_class . " - " . $value->teacher_type . "</div>";
    $class_state_teacher = 'no_teacher';
    $class_state_assistant = 'no_teacher';
    $teacher_name = "";
    $assistant_name = "";
    $teacher_name_assign = $value->teacher;
    $assistant_name_assign = $value->assistant;

    if (isset($dataReturn[$key]) && isset($dataReturn[$key]['teacherReal']) && strlen($dataReturn[$key]['teacherReal']) > 0) {
        $class_state_teacher = 'teacher';
        $teacher_name .= $dataReturn[$key]['teacherReal'] . "<br>";
    }
    if (isset($dataReturn[$key]) && isset($dataReturn[$key]['assistantReal']) && strlen($dataReturn[$key]['assistantReal']) > 0) {
        $class_state_assistant = 'teacher';
        $assistant_name .= $dataReturn[$key]['assistantReal'] . "<br>";
    }
    if ($teacher_name == "") {
        $teacher_name = $teacher_name_assign;
        $not_have_teacher += 1;
    }
    if ($assistant_name == "" && $value->type_class == 'LS' && $value->level_class == 'basic') {
        $assistant_name = $assistant_name_assign;
        $not_have_assistant += 1;
    }
    if ($value->level_class == 'inter' || $value->teacher_type == 'VN') {
        $class_state_assistant = 'teacher';
    }
    if (trim($value->vcr_type) == 'ADB') {
        $class_state_teacher = $class_teacher;
        $class_state_assistant = $class_assistant;
        $number_student = $number_student_move;
        $number_student_joined = $number_student_joint;
    }
    $data_tmp['teacher_id'] = "<div class='teacher_status " . $class_state_teacher . "' title='" . $value->teacher . "'>";
    $data_tmp['teacher_id'] .= $teacher_name . "</div>";
    $data_tmp['assistant_id'] = "<div class='teacher_status " . $class_state_assistant . "' title='" . $value->assistant . "'>";
    $data_tmp['assistant_id'] .= $assistant_name;
    $data_tmp['assistant_id'] .= "</div>";
    $data_tmp['povh'] = "";
    foreach ($array_mod as $key => $mod) {
        if (isset($povhs[$key])) {
            if ($key == $povhs[$key]->id) {
                $data_tmp['povh'] .= $povhs[$key]->lastname . " " . $povhs[$key]->firstname . "<br>";
            }
        }
    }
    $total_student += $number_student;
    $data_tmp['number_student'] = "<span class='e_colorbox' style='cursor:pointer;' href='" . $popup_vcr_detail . "'>" . $number_student . "</span>";
    $data_tmp['number_student_joined'] = "<span class='e_colorbox' style='cursor:pointer;' href='" . $popup_vcr_detail_joined . "'>" . $number_student_joined . "</span>";
    if ($value->vcr_type == "ADB") {
        // $data_tmp['action'] .= "<a target='_blank' href='http://" . $_SERVER["HTTP_HOST"] . "/local/tpebbb/adb_client.php?act=join&meeturl=" . str_replace("/", "", $room_adb->meeturl) . "&studentId=" . $USER->id . "'><img title='Vào lớp' src='" . $CFG->wwwroot . "/local/tpebbb/manager/images/icon_go_class.png" . "'></a>";
        $data_tmp['action'] = "<a target='_blank' href='http://" . $_SERVER["HTTP_HOST"] . "/local/tpebbb/adb_client.php?act=join&class_id=" . $value->bbb_id . "&studentId=" . $USER->id . "'><img title='Vào lớp' src='" . $CFG->wwwroot . "/local/tpebbb/manager/images/icon_go_class.png" . "'></a>";
        // $data_tmp['action'] .= "<a href='' data-toggle='modal' id='" . $value->bbb_id . "' data-href='" . $CFG->wwwroot . "/local/tpebbb/manager/form_msg_error.php" . "" . "' data-target='.msg_error' class='e_msg_error'> "
        //         . "<img title='Báo lỗi' src='" . $CFG->wwwroot . "/local/tpebbb/manager/images/Speaker_Icon.gif" . "'></a>";
    } else {
        $data_tmp['action'] = "<a target='_blank' href='" . $value->link_join_class_povh . "'><img title='" . get_string('enter_class', 'local_tpebbb') . "' src='" . $CFG->wwwroot . "/local/tpebbb/manager/images/icon_go_class.png" . "'></a>";
    }
    // echo '<pre>'; var_dump($value);
    $total_number_room ++;

    if ($value->teacher_type == 'AM' && $value->level_class == 'basic') {
        if ($stt_am_b == 1) {
            $bbb_id_am_b = $value->bbb_id;
            $data_tmp['stt'] = 1;
        } else {
            $data_tmp['stt'] = $value->bbb_id - $bbb_id_am_b + 1;
        }
        $stt_am_b++;
    } else if ($value->teacher_type == 'AM' && $value->level_class == 'inter') {
        if ($stt_am_i == 1) {
            $bbb_id_am_i = $value->bbb_id;
            $data_tmp['stt'] = 1;
        } else {
            $data_tmp['stt'] = $value->bbb_id - $bbb_id_am_i + 1;
        }
        $stt_am_i++;
    } else if ($value->teacher_type == 'VN' && $value->level_class == 'basic') {
        $data_tmp['stt'] = $stt_vn_b;
        $stt_vn_b++;
    } else if ($value->teacher_type == 'VN' && $value->level_class == 'inter') {
        $data_tmp['stt'] = $stt_vn_i;
        $stt_vn_i++;
    } else if ($value->teacher_type == 'VN' && $value->level_class == 'sbasic') {
        $data_tmp['stt'] = $stt_vn_sb;
        $stt_vn_sb++;
    } else if ($value->teacher_type == 'PHI' && $value->level_class == 'basic') {
        $data_tmp['stt'] = $stt_phi_b;
        $stt_phi_b++;
    } else if ($value->teacher_type == 'PHI' && $value->level_class == 'inter') {
        $data_tmp['stt'] = $stt_phi_i;
        $stt_phi_i++;
    } else {
        $total_number_room --;
        $value->teacher_type = 'z' . $value->teacher_type;
        $data_tmp['stt'] = '';
    }
    $ls_class[$value->teacher_type . '_' . $value->level_class . '_' . $value->student_type][] = $data_tmp;
}

// prepare to view
array_reorder_keys($ls_class, 'AM_basic_VN,AM_inter_VN,PHI_basic_VN,PHI_inter_VN,VN_basic_VN,VN_inter_VN,VN_sbasic_VN,AM_basic_TL,AM_inter_TL,zTL_basic_TL,zTL_inter_TL');
$convert_head = array(
    'AM_basic_VN' => 'LSAMVN_Basic',
    'AM_inter_VN' => 'LSAMVN_Inter',
    'PHI_basic_VN' => 'LSPHIVN_Basic',
    'PHI_inter_VN' => 'LSPHIVN_Inter',
    'VN_basic_VN' => 'SCVN_Basic',
    'VN_inter_VN' => 'SCVN_Inter',
    'VN_sbasic_VN' => 'Song ngữ VN_sbasic',
    'AM_basic_TL' => 'LSAMTL_Basic',
    'AM_inter_TL' => 'LSAMTL_Inter',
    'PHI_basic_TL' => 'LSPHITL_Basic',
    'PHI_inter_TL' => 'LSPHITL_Inter',
    'zTL_basic_TL' => 'SCTL_Basic',
    'zTL_sbasic_TL' => 'Song ngữ TL_sbasic',
    'zTL_inter_TL' => 'SCTL_Inter',
    'zTRAINING_basic_VN' => 'TRAINING',
    'zTRAINING_inter_VN' => 'TRAINING',
    'zORIENTATION_basic_VN' => 'ORIENTATION',
    'AM_basic_VIP' => 'LSAMVIP_Basic',
    'AM_inter_VIP' => 'LSAMVIP_Inter',
    'PHI_basic_VIP' => 'LSPHIVIP_Basic',
    'PHI_inter_VIP' => 'LSPHIVIP_Inter',
    'VN_basic_VIP' => 'SCVIP_Basic',
    'VN_inter_VIP' => 'SCVIP_Inter',
    //thuyvv 28/01/2016
    'AM_basic_INDO' => 'LSAMINDO_Basic',
    'AM_inter_INDO' => 'LSAMINDO_Inter',
    'PHI_basic_INDO' => 'LSPHIINDO_Basic',
    'PHI_inter_INDO' => 'LSPHIINDO_Inter',
    'zINDO_basic_INDO' => 'SCINDO_Basic',
    'zINDO_sbasic_INDO' => 'Song ngữ INDO_sbasic',
    'zINDO_inter_INDO' => 'SCINDO_Inter',
        //thuyvv 28/01/2016
);
if (isset($_POST['ajax']) && $_POST['ajax']) {
    $data_return = array(
        'state' => 1,
        'number_room' => $total_number_room,
        'room_not_teacher' => $not_have_teacher,
        'room_not_assistant' => $not_have_assistant,
        'total_student' => $total_student,
        'content' => $tpeLearning->get_content_data_search($list_th, $ls_class, $convert_head)
    );
    echo json_encode($data_return);
    return TRUE;
}
//$coursecontext = context_course::instance($id);
$PAGE->set_url('/local/tpebbb/manager/running_class_v2.php');
$PAGE->set_title(get_string('running_class', 'local_tpebbb'));
$PAGE->set_pagelayout('povh_manager');
$PAGE->set_heading("");
//$PAGE->set_context($coursecontext);
$PAGE->requires->jquery();
$PAGE->requires->jquery_plugin('ui');
$PAGE->requires->jquery_plugin('ui-css');
$PAGE->requires->css(new moodle_url($CFG->wwwroot . '/local/tpebbb/manager/css/manager.css'));
$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/tpebbb/manager/js/jquery.form.js'));
$PAGE->requires->css('/local/tpebbb/manager/css/bootstrap.min.css');
$PAGE->requires->js('/local/tpebbb/manager/js/bootstrap.min.js');
$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/tpebbb/manager/js/notify.js'));
$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/tpebbb/manager/js/manager.js'));
$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/tpebbb/manager/js/msg_error_ADB.js'));
$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/tpebbb/manager/plugins/colorbox/jquery.colorbox.js'));
$PAGE->requires->css(new moodle_url($CFG->wwwroot . '/local/tpebbb/manager/plugins/colorbox/colorbox.css'));
$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/tpebbb/manager/plugins/chosen/chosen.jquery.min.js'));
$PAGE->requires->css(new moodle_url($CFG->wwwroot . '/local/tpebbb/manager/plugins/chosen/chosen.css'));
$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/tpebbb/manager/plugins/datatable/jquery.dataTables.min.js'));
$PAGE->requires->css(new moodle_url($CFG->wwwroot . '/local/tpebbb/manager/plugins/datatable/jquery.dataTables.min.css'));
echo $OUTPUT->header();
?>
<div class="wrap_data_povh e_wrap_data_povh" data_img="<?php echo $CFG->wwwroot . '/local/teacherservice/pix/loading-spiral.gif'; ?>">
    <div class="search_povh">
        <form method="post" class="e_ajax_search_submit" action="<?php echo $CFG->wwwroot . "/local/tpebbb/manager/running_class_v2.php" ?>">
            <input type="hidden" name="course" value="<?php echo $id ?>"/>
            <input type="hidden" name="ajax" value="0"/>
            <div class="row-search">
                <div class="type">
                    <label><?php echo get_string('type', 'local_tpebbb'); ?></label>
                    <select name="type" class="chosen-select">
                        <option value="0"><?php echo get_string('choose_type', 'local_tpebbb'); ?></option>
                        <option value="AM" <?php echo ($teacher_type === 'AM') ? 'selected' : '' ?> >LS-AM</option>
                        <option value="PHI" <?php echo ($teacher_type === 'PHI') ? 'selected' : '' ?> >LS-PHI</option>
                        <option value="VN" <?php echo ($teacher_type === 'VN') ? 'selected' : '' ?> >SC</option>
                    </select>
                </div>
                <div class="level">
                    <label><?php echo get_string('level', 'local_tpebbb'); ?></label>
                    <select name="level" class="chosen-select">
                        <option value="0"><?php echo get_string('choose_level', 'local_tpebbb'); ?></option>
                        <option value="basic" <?php echo ($level === 'basic') ? 'selected' : '' ?> ><?php echo get_string('basic', 'local_tpebbb'); ?></option>
                        <option value="inter" <?php echo ($level === 'inter') ? 'selected' : '' ?> ><?php echo get_string('inter', 'local_tpebbb'); ?></option>
                        <option value="advan" <?php echo ($level === 'advan') ? 'selected' : '' ?> ><?php echo get_string('advance', 'local_tpebbb'); ?></option>
                    </select>
                </div>
                <div class="teacher">
                    <label><?php echo get_string('teacher', 'local_tpebbb'); ?></label>
                    <select name="teacher" class="chosen-select">
                        <option value="0"><?php echo get_string('choose_teacher', 'local_tpebbb'); ?></option>
<?php
if (count($list_teacher)) {
    foreach ($list_teacher as $teacher) {
        ?>
                                <option value="<?php echo $teacher->id ?>"><?php echo $teacher->firstname . ' ' . $teacher->lastname ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="assistant">
                    <label><?php echo get_string('assistant', 'local_tpebbb'); ?></label>
                    <select name="assistant" class="chosen-select">
                        <option value="0"><?php echo get_string('choose_assistant', 'local_tpebbb'); ?></option>
<?php
if (count($list_teacher)) {
    foreach ($list_teacher as $teacher) {
        ?>
                                <option value="<?php echo $teacher->id ?>"><?php echo $teacher->firstname . ' ' . $teacher->lastname ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="povh">
                    <label><?php echo get_string('povh', 'local_tpebbb'); ?></label>
                    <select name="povh" class="chosen-select">
                        <option value="0"><?php echo get_string('choose_povh', 'local_tpebbb'); ?></option>
                    </select>
                </div>

            </div>
            <div class="row-search">
                <div class="code row-1" style="width:25%;">
                    <label><?php echo get_string('code', 'local_tpebbb'); ?></label>
                    <input type="text" name="code" class="form-control" value='<?php echo $code; ?>'/>
                </div>
                <div class="fromday row-1" style="margin-right: 3px;">
                    <label><?php echo get_string('fromday', 'local_tpebbb'); ?></label>
                    <input id='' readonly="" type="text" class="form-control" name="fromday" value='<?php echo $fromday ?>' />
                </div>
                <div class="today row-1" style="margin-right: 2px;">
                    <label><?php echo get_string('today', 'local_tpebbb'); ?></label>
                    <input id='' readonly="" type="text" class="form-control" name="today" value='<?php echo $today ?>' />
                </div>
                <div class="status_teacher" style="width: 24%;margin-left: 3px">
                    <label><?php echo get_string('status', 'local_tpebbb'); ?></label>
                    <select name="status_teacher" class="chosen-select">
                        <option value="0"><?php echo get_string('all', 'local_tpebbb'); ?></option>
                        <option value="1" <?php echo ($status_teacher === '1') ? 'selected' : '' ?>><?php echo get_string('enough_teacher', 'local_tpebbb'); ?></option>
                        <option value="2" <?php echo ($status_teacher === '2') ? 'selected' : '' ?>><?php echo get_string('not_enough_teacher', 'local_tpebbb'); ?></option>
                    </select>
                </div>
            </div>
            <div class="row-search">
                <div class="class_type" style="width: 25%;">
                    <label><?php echo get_string('tpebbb_classtype', 'local_tpebbb'); ?></label>
                    <select name="class_type" class="chosen-select">
                        <option value="0">---------------</option>
<?php
foreach ($convert_head as $key => $value) {
    # code...
    echo '<option value="' . $key . '">' . $value . '</option>';
}
?>
                    </select>
                </div>
                <div class="class_type">
                    <label><?php echo get_string('tpebbb_vcrtype', 'local_tpebbb'); ?></label>
                    <select name="vcr_type" class="chosen-select" style="width: 188px;">
                        <option value="0">---------------</option>
                        <option value="ADB">ADB</option>
                        <option value="BBB">BBB</option>
                    </select>
                </div>
            </div>
            <div class="row-search">
                <button class="btn_form_search btn_submit_search" type="submit"><?php echo get_string('search', 'local_tpebbb'); ?></button>
                <button class="btn_form_search" type="reset"><?php echo get_string('reset', 'local_tpebbb'); ?></button>
                <div class='update'>
                    <label><?php echo get_string('auto_update', 'local_tpebbb'); ?></label>
                    <input type='checkbox' class='e_update checkbox' name='update'>
                </div>
            </div>
        </form>
    </div>
    <div class='wrap_info'>
        <div class='tittle_info'><?php echo get_string('info', 'local_tpebbb'); ?></div>
        <div class='detail_info'>
            <div><?php echo get_string('created_class_total', 'local_tpebbb'); ?>: <b id="i_number_room"><?php echo $total_number_room; ?></b></div>
            <div><?php echo get_string('created_class_not_enough_teacher', 'local_tpebbb'); ?>: <b id="i_room_not_teacher"><?php echo $not_have_teacher; ?></b></div>
            <div><?php echo get_string('created_class_not_enough_assistant', 'local_tpebbb'); ?>: <b id="i_room_not_assistant"><?php echo $not_have_assistant; ?></b></div>
            <div><?php echo get_string('running_class_total_student', 'local_tpebbb'); ?>: <b id="i_total_student"><?php echo $total_student; ?></b></div>
        </div>

    </div>
    <div style='clear: both'></div>
    <div class="e_content_search">
        <table>
            <thead>
                <tr>
<?php foreach ($list_th as $item) { ?>
                        <th><?php echo $item ?></th>
<?php }
?>
                </tr>
            </thead>
            <tbody>
                    <?php
                    if (count($ls_class)) {
                        foreach ($ls_class as $type => $class) {
                            ?>
                        <tr class="<?php echo 'head ' . $type; ?>" style="color: #FFF;background-color: #3B5998;font-weight: bold;">
                        <?php foreach ($list_th as $key => $value) { ?>
                                <td><?php echo $convert_head[$type]; ?></td>
                        <?php } ?>
                        </tr>
                            <?php foreach ($class as $key => $item_class) {
                                ?>
                            <tr class="<?php echo $type; ?>">
                            <?php foreach ($item_class as $k => $item) { ?>
                                    <td class="<?php echo $k; ?>"><?php echo $item ?></td>
                            <?php } ?>
                            </tr>
                                <?php
                            }
                        }
                    }
                    ?>

            </tbody>
        </table>
    </div>
</div>

<div class="modal fade msg_error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

        </div>
    </div>
</div>
<?php
echo $OUTPUT->footer();

function array_reorder_keys(&$array, $keynames) {
    if (empty($array) || !is_array($array) || empty($keynames))
        return;
    if (!is_array($keynames))
        $keynames = explode(',', $keynames);
    if (!empty($keynames))
        $keynames = array_reverse($keynames);
    foreach ($keynames as $n) {
        if (array_key_exists($n, $array)) {
            $newarray = array($n => $array[$n]); //copy the node before unsetting
            unset($array[$n]); //remove the node
            $array = $newarray + array_filter($array); //combine copy with filtered array
        }
    }
}

function split_class_type($type_class, $convert_head = array()) {
    $condition_array = explode("_", $type_class);

    $cond_teacher_type = str_replace("z", "", $condition_array[0]);

    $cond_level_class = $condition_array[1];

    $cond_student_type = $condition_array[2];

    $result = array(
        "cond_teacher_type" => $cond_teacher_type,
        "cond_level_class" => $cond_level_class,
        "cond_student_type" => $cond_student_type,
    );
    return $result;
}
