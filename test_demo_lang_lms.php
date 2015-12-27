<?php
defined('MOODLE_INTERNAL') || die();
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');

class ViewTpeLearning {

    function __construct() {
        $listPlugins = get_plugin_list("local");
        if (isset($listPlugins["tpebbb"])) {
            require_once($listPlugins["tpebbb"] . "/lib.php");
        } else {
            throw new Exception("require plugin 'local/tpebbb'");
        }
        $this->tpebbb = new TpeBigBlueButton();
        global $LS_KEY, $SC_KEY, $BASIC_KEY, $INTER_KEY, $ADVAN_KEY;
        $LS_KEY = "LS";
        $SC_KEY = "SC";
        $BASIC_KEY = 'Basic';
        $INTER_KEY = 'Inter';
        $ADVAN_KEY = 'Advan';
    }

    /**
     * Lấy thông tin level của học viên
     * @global type $DB
     * @global type $USER
     * @return type
     */
    function get_level_user() {
        global $DB, $USER;
        $sql_level_user = "SELECT l.id,(SELECT level.shortname FROM {tpe_userinfo_leveltype} as level WHERE l.parentid = level.id) as shortname
            FROM {tpe_userinfo_leveltype} l
            JOIN {user_info_data} d on d.data = l.id 
            JOIN {user_info_field} f on f.id = d.fieldid 
            WHERE  f.shortname = :currentlevel AND d.userid = :userid";
        $level_user = $DB->get_record_sql($sql_level_user, array('currentlevel' => 'currentlevel', 'userid' => $USER->id));

        return $level_user;
    }

    /**
     * Lấy thông tin lịch học đăng ký của học viên trong ngày
     * @global type $DB
     * @param type $parma_block_hour
     * @return type
     */
    function get_info_block_hour($parma_block_hour) {
        global $DB;
        $sql_block_hour = "SELECT h.*
            FROM {tpebbb_block_hour} h
            WHERE  h.courseid = :course 
            AND h.datetime >= :datetime_begin AND h.datetime < :datetime_end";
        $block_hour_info = $DB->get_records_sql($sql_block_hour, $parma_block_hour);
        $arr_block_hour = array();
        if ($block_hour_info) {
            foreach ($block_hour_info as $item) {
                $key = date('H', $item->datetime);
                $arr_block_hour[intval($key)] = $item;
            }
        }
        return $arr_block_hour;
    }

    /**
     * Lấy thông tin về lớp dạy theo từng giờ trong ngày của giáo viên
     * @global type $DB
     * @param type $param_sql
     * @param type $condition
     * @return type
     */
    function get_info_block_hour_teacher($param_sql, $condition) {
        global $DB;
        $sql = "SELECT  bbb.id as bbb_id,bbb.*,c.*,m.*,c.calendar_code as calendar_code,m.id as material_id
            FROM {tpebbb} bbb
            JOIN {tpe_calendar_teach} c on c.calendar_code = bbb.calendar_code 
            JOIN {materialservice} m on c.subject_code = m.subject_code 
            WHERE bbb.roomtype='Room' AND " . $condition . "           
            ORDER BY bbb.timeavailable ASC";
        $list_bbb_info = $DB->get_records_sql($sql, $param_sql);
        $tpeLearningRoom = new TpeLearningRoom();
        $list_bbb_info = $tpeLearningRoom->_process_info_bbb($list_bbb_info);
        $arr_block_hour = array();
        if ($list_bbb_info) {
            foreach ($list_bbb_info as $item) {
                $key = date('H', $item->timeavailable);
                $arr_block_hour[intval($key)] = $item;
            }
        }
        return $arr_block_hour;
    }

    function get_info_block_hour_povh($param_sql, $condition) {
        global $DB;
        $sql = "SELECT  bbb.id as bbb_id,bbb.*,c.*,m.*,c.calendar_code as calendar_code,m.id as material_id
            FROM {tpebbb} bbb
            JOIN {tpe_calendar_teach} c on c.calendar_code = bbb.calendar_code 
            JOIN {materialservice} m on c.subject_code = m.subject_code 
            WHERE bbb.roomtype='Room' AND " . $condition . "           
            ORDER BY bbb.timeavailable ASC";
        $list_bbb_info = $DB->get_records_sql($sql, $param_sql);
        $tpeLearningRoom = new TpeLearningRoom();
        $list_bbb_info = $tpeLearningRoom->_process_info_bbb($list_bbb_info);
        $arr_block_hour = array();
        if ($list_bbb_info) {
            foreach ($list_bbb_info as $item) {
                $key = date('H', $item->timeavailable);
                $arr_block_hour[intval($key)][] = $item;
            }
        }
        return $arr_block_hour;
    }

    function get_log_join_room_bbb($userid) {
        global $DB;
        $time = $this->tpebbb->getAvailableTime();
        $sql = "SELECT l.id as log_id,
            bbb.id as bbb_id,bbb.timeavailable,bbb.subject_code,bbb.timedue,
            m.subject_type,m.objective,m.topic,m.subject,m.class_outline,m.fileurl,m.outline_homework ,m.lesson_plan,m.video_warmup,m.background,m.id as material_id,
            c.type_class,c.calendar_code as calendar_code
            FROM {tpebbb} bbb
            JOIN {tpebbb_join} l on l.bigbluebuttonbnid = bbb.id 
            JOIN {tpe_calendar_teach} c on c.calendar_code = bbb.calendar_code 
            JOIN {materialservice} m on c.subject_code = m.subject_code 
            WHERE bbb.roomtype='Room' AND timeavailable > :mintime AND timeavailable <= :maxtime AND l.userid = :userid       
            ORDER BY l.timecreated DESC";
        $param_sql = array(
            'userid' => $userid,
            'mintime' => $time["minTime"],
            'maxtime' => $time["maxTime"],
        );
        $list_bbb_info = $DB->get_records_sql($sql, $param_sql);
        $bbb_info = array();
        $tpeLearningRoom = new TpeLearningRoom();
        if (is_array($list_bbb_info) && count($list_bbb_info)) {
            $arr_bbb_info = (array_values($list_bbb_info));
            $arr_bbb_info = $tpeLearningRoom->_process_info_bbb($arr_bbb_info);
            $bbb_info = $arr_bbb_info[0];
        } else {
            $sql = "SELECT l.id as log_id,
            bbb.id as bbb_id,bbb.timeavailable,bbb.subject_code,bbb.timedue,
            m.subject_type,m.objective,m.topic,m.subject,m.class_outline,m.fileurl,m.outline_homework ,m.lesson_plan,m.video_warmup,m.background,m.id as material_id,
            c.type_class,c.calendar_code as calendar_code
            FROM {tpebbb} bbb
            JOIN {logsservice_move_user} l on l.roomidto = bbb.id 
            JOIN {tpe_calendar_teach} c on c.calendar_code = bbb.calendar_code 
            JOIN {materialservice} m on c.subject_code = m.subject_code 
            WHERE bbb.roomtype='Room' AND timeavailable > :mintime AND timeavailable <= :maxtime AND l.userid = :userid       
            ORDER BY l.timecreated DESC";
            $list_bbb_info = $DB->get_records_sql($sql, $param_sql);
            if (is_array($list_bbb_info) && count($list_bbb_info)) {
                $arr_bbb_info = (array_values($list_bbb_info));
                $arr_bbb_info = $tpeLearningRoom->_process_info_bbb($arr_bbb_info);
                $bbb_info = $arr_bbb_info[0];
            }
        }
        return $bbb_info;
    }

    /**
     * Hiển thị giao diện của trang học trực tuyến của học viên
     * @param type $ls_info
     * @param type $sc_info
     * @param type $arr_block_hour
     * @return type
     */
    function view_tpe_learning($ls_info, $sc_info, $arr_block_hour) {
        global $USER, $CFG;
        $time_now = time();
        ob_start();
        $role = 'student';
        ?>
        <?php if ((strtotime('2015/04/11 00:00:00') <= $time_now) && ($time_now <= strtotime('2015/04/15 23:59:59')) && ($USER->profile['packageparent']) == 'TL100') { ?>
            <script src='<?php echo $CFG->wwwroot . '/local/tpelearning/externalfile/js/jquery.bpopup.min.js'; ?>' type='text/javascript'></script>
            <img id='thai_holiday' style='display:none;' src='<?php echo $CFG->wwwroot . '/local/tpelearning/pix/native-holiday.jpg'; ?>'>
            <script type='text/javascript'>
                $(document).ready(function () {
                    $('#thai_holiday').bPopup();
                });
            </script>
            <?php
        }
        ?>
        <div class="wrap_left" data_url="<?php echo $CFG->wwwroot . '/local/tpelearning/index.php?id=2&ajax=1' ?>" data_image='<?php echo $CFG->wwwroot . '/local/tpelearning/externalfile/images/loading-spiral.gif' ?>'>
            <div class="wrap_box">
                <?php if(isset($USER->profile['studenttype']) == 'AUTOSALE') { ?>
                    <div class="wrap_bbb">
                        <?php echo $this->view_bbb_for_autosale($ls_info, $role) ?>
                    </div>

                <?php }else{ ?>
                    <div class="wrap_bbb">
                        <?php echo $this->view_bbb($ls_info, $role) ?>
                    </div>

                    <div class="info_other">
                        <?php echo $this->view_bbb_sc($sc_info, $role); ?>
                        <?php echo $this->view_ask_teacher($sc_info); ?>
                </div>
                <?php }?>
                
                <?php echo $this->view_support_bbb(); ?>
            </div>

            <?php echo $this->view_col_hour($arr_block_hour) ?>
        </div>
        <?php
        $string = ob_get_clean();
        return $string;
    }

    /**
     * Hiển thị giao diện của trang học trực tuyến của povh
     * @param type $ls_info
     * @param type $sc_info
     * @return type
     */
    function view_tpe_learning_povh($ls_info, $sc_info, $arr_block_hour) {
        global $CFG;
        ob_start();
        $role = 'povh';
        ?>
        <div class="wrap_left" data_url="<?php echo $CFG->wwwroot . '/local/tpelearning/index.php?id=2&ajax=1' ?>" data_image='<?php echo $CFG->wwwroot . '/local/tpelearning/externalfile/images/loading-spiral.gif' ?>'>
            <div class="wrap_box">
                <div class="wrap_bbb wrap_bbb_forpovh">
                    <?php echo $this->view_bbb($ls_info, $role) ?>
                </div>
                <div class="info_other">
                    <?php echo $this->view_bbb_sc($sc_info, $role); ?>
                    <?php echo $this->view_ask_teacher(); ?>
                </div>
                <?php echo $this->view_support_bbb(); ?>
            </div>

            <?php echo $this->view_col_hour_povh($arr_block_hour) ?>
        </div>
        <?php
        $string = ob_get_clean();
        return $string;
    }

    /**
     * Hiển thị giao diện của trang học trực tuyến của giáo viên, trợ giảng
     * @param type $bbb_info
     * @return type
     */
    function view_tpe_learning_teacher($ls_info, $sc_info, $arr_block_hour) {
        global $CFG;
        ob_start();
        $role = 'teacher';
        ?>
        <div class="wrap_left" data_url="<?php echo $CFG->wwwroot . '/local/tpelearning/index.php?id=2&ajax=1' ?>" data_image='<?php echo $CFG->wwwroot . '/local/tpelearning/externalfile/images/loading-spiral.gif' ?>'>
            <style>
                #i_clock{
                    background-color: #fff;
                    border-radius: 5px;
                    font-size: 15px;
                    font-weight: bold;
                    left: 10px;
                    padding: 10px 6px;
                    position: absolute;
                    width: auto;
                    text-align: center;
                }
            </style>
            <script type="text/javascript">
                $(document).ready(function () {
                    var customtimestamp = '<?php echo round(microtime(true) * 1000); ?>';
                    console.log(customtimestamp);
                    $("#i_clock").clock({
                        "timestamp": customtimestamp,
                        "format": "24"
                    });
                });
            </script>
            <div id="i_clock" time="<?php echo round(microtime(true) * 1000); ?>"></div>
            <div class="wrap_box">
                <div class="wrap_bbb wrap_bbb_forteacher_v2">
                    <?php echo $this->view_bbb($ls_info, $role) ?>
                </div>

                <div class="info_other">
                    <?php echo $this->view_bbb_sc($sc_info, $role); ?>
                    <?php echo $this->view_ask_teacher(); ?>
                </div>
                <?php echo $this->view_support_bbb(); ?>
            </div>
            <?php echo $this->view_col_hour_teacher($arr_block_hour) ?>
        </div>
        <?php
        $string = ob_get_clean();
        return $string;
    }

    /**
     * Hiển thị giao diện của lớp bbb
     * @param type $bbb_info
     * @return type
     */
    function view_bbb($bbb_info, $role) {
        ob_start();
        ?>
        <div class="info_livesession">
            <?php if ($bbb_info) {//có lớp mở    
                ?>
                <div class="detail_livesession e_detail_livesession">
                    <img src='<?php echo $bbb_info->link_img; ?>'>
                    <?php echo $this->view_count_down_bbb($bbb_info); ?>
                    <div class="title_livesession">
                        <?php echo get_string("unit_ls", "local_tpelearning") . ': '; ?>
                        <span>
                            <?php echo isset($bbb_info->topic) ? $bbb_info->topic : '' ?>
                        </span>
                    </div>
                    <div class="info_vcr">
                        <div class="name_livesession">
                            <?php if ($role != 'student') { ?>
                                <img class="<?php echo strtolower($bbb_info->subject_type); ?>" src="pix/<?php echo $bbb_info->subject_type; ?>.png" />
                            <?php } ?>
                            <span><?php echo isset($bbb_info->topic) ? $bbb_info->topic : '' ?></span>
                        </div>
                        <div class="left_field">
                            <div class="about">
                                <?php echo isset($bbb_info->objective) ? $bbb_info->objective : '' ?>
                            </div>
                            <?php echo $this->view_button_join_class_bbb($bbb_info, $role) ?>
                        </div>

                        <div><?php //echo $this->view_document_bbb($bbb_info, $role);            ?></div>

                        <div class="clear"></div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="detail_livesession">
                    <div style="padding-top:150px;text-align:center;height:300px">
                        <?php echo get_string("note_alert", "local_tpelearning"); ?>
                    </div>
                </div>       
                <?php
            }
            ?>
            <div class="clear"></div>
        </div>
        <?php
        $string = ob_get_clean();
        return $string;
    }

    /**
     * Hiển thị giao diện của lớp bbb theo hoc vien AUTOSALE
     * @param type $bbb_info
     * @return type
     */
    function view_bbb_for_autosale($bbb_info, $role) {
        global $USER, $CFG, $DB;
        $tpeBBB = new TpeBigBlueButton();
        $support_info = array(
            'link_tech_support' => $tpeBBB->getLinkVcr("LMS", 'OTHER', 0, 'TechSupportNewbie'), //link vào lớp kiểm tra kĩ thuật
        );
        $sql = "SELECT  bbb.id as bbb_id,log.userid
            FROM {logsservice_in_out} log
            JOIN {tpebbb} bbb on bbb.id = log.roomid
            WHERE bbb.roomtype='TECHTEST' AND log.userid = :userid
            GROUP BY log.roomid
            LIMIT 1
            ";
        $param_sql = array(
            'userid' => $USER->id,
        );
        $check_techtest = $DB->get_records_sql($sql, $param_sql);
        $background = $CFG->wwwroot . '/local/tpelearning/externalfile/images/image_gotoclass_1.png';
        if(count($check_techtest)){
            $background = $CFG->wwwroot . '/local/tpelearning/externalfile/images/img_tech_1.png';
        }
        ob_start();
        ?>
        <div class="info_livesession">
            <?php if ($bbb_info) {//có lớp mở    
                ?>
                <div class="detail_livesession e_detail_livesession">
                    <img src='<?php echo $background; ?>' />
                    <?php echo $this->view_count_down_bbb($bbb_info); ?>
                    <div class='clear'></div>
                    <?php if(!count($check_techtest)) { ?>
                        <div class='autosale_bbb_wrap' style='padding-top: 60px;color: #810c15;background: none;position: absolute;top: 0px;left: 10px;'>
                            <h3 class='autosale_bbb_title' style='font-size:27px;margin: 0px 0px 20px 0px;'>
                                <?php echo get_string("welcome_topica", "local_tpelearning"); ?>
                            </h3>
                            <div class='autosale_bbb_content' style='font-size: 18px;text-align: center;'>
                                <!-- <p style='margin-bottom: 15px'>Buổi học thử của bạn sẽ bắt đầu lúc</p> -->
                                <!-- <p style='margin-bottom: 15px'>Your class starts at </p> -->
                                <p style='margin-bottom: 15px'><?php echo get_string("auditing", "local_tpelearning"); ?></p>
                                <?php $date = get_string("date", "local_tpelearning");?>
                                <p style='margin-bottom: 15px'><b style='font-weight: 700;'><?php echo date('H:s',$bbb_info->timeavailable) . $date . date('d/m/Y',$bbb_info->timeavailable) ?></b></p>
                                <p style='font-style: italic;border-top: 2px solid #f2f2f2;border-bottom: 2px solid #f2f2f2;padding: 5px;margin-bottom: 0px'>
                                    <?php echo get_string("in_class", "local_tpelearning"); ?></br>
                                    <?php echo get_string("within", "local_tpelearning"); ?> <b style='font-weight: 700;'>15 <?php echo get_string("minute", "local_tpelearning"); ?></b>
                                    <?php echo get_string("before_classes_begin", "local_tpelearning"); ?>
                                </p>
                                <?php echo $this->view_button_join_class_bbb_autosale($bbb_info) ?>
                            </div>
                        </div>
                    <?php }else{ ?>
                        <div class='autosale_bbb_wrap' style='padding-top: 60px;color: #810c15;background: none;position: absolute;top: 0px;right: 5px;'>
                            <div class='autosale_bbb_content' style='font-size: 18px;text-align: center'>
                                <!-- <p style='margin-bottom: 15px'>Buổi học thử của bạn sẽ bắt đầu lúc</p> -->
                                <!-- <p style='margin-bottom: 15px'>Your class starts at </p> -->
                                <p style='margin-bottom: 15px'> <?php echo get_string("class_starts", "local_tpelearning"); ?></p>
                                <p style='margin-bottom: 15px'><b style='font-weight: 700;'><?php echo date('H:s',$bbb_info->timeavailable). ' on ' . date('d/m/Y',$bbb_info->timeavailable) ?></b></p>
                                <p style='font-style: italic;border-top: 1px solid #f2f2f2;border-bottom: 2px solid #f2f2f2;padding: 12px 0px 12px 0px;margin-bottom: 0px;margin: 0px 8px 0px 0px;font-size: 15px;color: #666666;'>



                                    <!-- Vui lòng kiểm tra kĩ thuật ngay để</br>
                                    đảm bảo trải nghiệm</br>
                                    học thử của bạn được tốt nhất -->
                                    <?php echo get_string("technical_inspection", "local_tpelearning"); ?><br/>
                                    <?php echo get_string("to_experience", "local_tpelearning"); ?><br/>
                                    <?php echo get_string("try_out_best", "local_tpelearning"); ?>
                                    
                                </p>
                                <p>
                                    <a tagert='_blank' href='<?php echo $support_info['link_tech_support'] ?>' style="
                                    margin-top: 35px;
                                    padding: 8px 12px;
                                    background-color: #810c15;
                                    color: #fff;
                                    border: 1px solid #fff;
                                    display: inline-block;
                                    border-radius: 20px;
                                    font-weight: 700;
                                ">
                                <!-- KIỂM TRA KỸ THUẬT -->
                                <!-- JOIN TECHNICAL CHECK -->
                                <?php echo get_string("join_technical", "local_tpelearning"); ?>

                                </a>
                                </p>
                            </div>   
                        </div> 
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="detail_livesession">
                    <div style="padding-top:150px;text-align:center;height:300px">
                        <?php echo get_string("note_alert", "local_tpelearning"); ?>
                    </div>
                </div>       
                <?php
            }
            ?>
            <div class="clear"></div>
        </div>
        <?php
        $string = ob_get_clean();
        return $string;
    }

    /**
     * Hiển thị giao diện phần lớp nhóm thảo luận
     * @param type $sc_info
     * @return type
     */
    function view_bbb_sc($sc_info, $role) {
        global $USER;
        ob_start();
        ?>
        <div class="info_conversationroom info_room">
            <div>
                <?php if ($role == 'student' && isset($USER->profile['currentlevel']) && $USER->profile['currentlevel'] == 'sbasic') { ?>
                    <span class="name_class sbasic"><?php echo get_string("title_sc_sbasic_2", "local_tpelearning"); ?>
                        <br/><span><font size='1'>(<?php echo get_string("title_sc_sbasic", "local_tpelearning"); ?>)</font></span>
                    </span>
                <?php } else { ?>
                    <span class="name_class"><?php echo get_string("title_sc", "local_tpelearning"); ?></span>
                <?php } ?>


                <?php if ($sc_info) { ?>
                    <?php echo $this->view_count_down_bbb_sc($sc_info) ?>
                </div>
                <div class="detail_conversationroom">
                    <div class="title_conversationroom"><?php echo get_string("topic_sc", "local_tpelearning") . ': '; ?> 
                        <b class="" title='<?php echo $sc_info->subject; ?>'><?php echo $sc_info->subject; ?></b>
                    </div>
                    <div class='wrap_btn_down_document_sc'>
                        <?php
                        echo $this->view_document_bbb_sc($sc_info, $role);
                        ?>
                    </div>

                    <div class='wrap_btn_join_class_sc'>

                    </div>
                </div>
            <?php } else { ?>
            </div>
            <div class="detail_conversationroom">
                <div class="" style="padding:10px"><?php echo get_string("note_alert_sc", "local_tpelearning"); ?></div>
            </div>
        <?php } ?>
        </div>
        <?php
        $string = ob_get_clean();
        return $string;
    }

    /**
     * Hiển thị giao diên phần hỏi đáp giáo viên
     * @global type $CFG
     * @return type
     */
    function view_ask_teacher($sc_info = array()) {
        global $CFG, $USER;
        $link_askteacher = $CFG->wwwroot;
        ob_start();
        ?>
        <div class="info_askteacher info_room">
            <div>
                <?php if (isset($USER->profile['currentlevel']) && $USER->profile['currentlevel'] == 'sbasic') { ?>
                    <span class="name_class sbasic"><?php echo get_string("title_sc_sbasic_2", "local_tpelearning"); ?>
                        <br/><span><font size='1'>(<?php echo get_string("title_askteacher_sbasic", "local_tpelearning"); ?>)</font></span>
                    </span>
                    <a class="e_popup_testpre_vcr" href="<?php echo (!empty($sc_info)) ? $sc_info->testpre_vcr : '#'; ?>">
                        <span class="btn_ask"><?php echo get_string("askteacher_sbasic", "local_tpelearning"); ?></span>
                    </a>
                <?php } else { ?>
                    <span class="name_class"><?php echo get_string("title_askteacher", "local_tpelearning"); ?></span>
                    <a class="" href="">
                        <span class="btn_ask" href="<?php echo $link_askteacher ?>"><?php echo get_string("askteacher", "local_tpelearning"); ?></span>
                    </a>
                <?php } ?>


            </div>
            <?php if (!(isset($USER->profile['currentlevel']) && $USER->profile['currentlevel'] == 'sbasic')) { ?>
                <div class="detail_askteacher">
                    <div class="about_askteacher">
                        <?php echo get_string("content_askteacher", "local_tpelearning"); ?>
                    </div>
                </div>
            <?php } ?>

        </div>
        <?php
        $string = ob_get_clean();
        return $string;
    }

    /**
     * Hiển thị giao diện phần đến thời gian vào lớp của lớp bbb
     * @param type $bbb_info
     * @return type
     */
    function view_count_down_bbb($bbb_info) {
        ob_start();
        ?>
        <div class="time_count e_time_count_livesession"></div>
        <div class="hidden e_value_time_livesession"><?php echo $bbb_info->timeavailable - $bbb_info->time_now ?></div>
        <div class="time_count hidden e_time_count_livesession_late"></div>
        <div class="hidden e_value_time_livesession_late"><?php echo $bbb_info->timeavailable_late ? $bbb_info->timeavailable_late - $bbb_info->time_now : 0; ?></div>
        <?php
        $string = ob_get_clean();
        return $string;
    }

    /**
     * Hiển thị giao diện phần đếm thời gian vào lớp của lớp nhóm thảo luận
     * @param type $sc_info
     * @return type
     */
    function view_count_down_bbb_sc($sc_info) {
        ob_start();
        ?>
        <?php
        echo $this->view_button_join_class_bbb_sc($sc_info);
        ?>
        <?php if ($sc_info->timeavailable < $sc_info->time_now) {
            ?>
            <span class="hidden time_count e_time_count_conversationroom"></span>
            <?php
        } else {
            if ($sc_info->time_countdown < $sc_info->value_time_change_btn) {
                ?>
                <span class="hidden time_count e_time_count_conversationroom"></span>
            <?php } else { ?>
                <span class="time_count e_time_count_conversationroom"></span>
                <?php
            }
        }
        ?>
        <div class="hidden e_value_time_conversationroom"><?php echo $sc_info->timeavailable - $sc_info->time_now ?></div>
        <span class="hidden e_time_count_conversationroom_late"></span>
        <div class="hidden e_value_time_conversationroom_late"><?php echo $sc_info->timeavailable_late ? $sc_info->timeavailable_late - $sc_info->time_now : 0; ?></div>
        <?php
        $string = ob_get_clean();
        return $string;
    }

    /**
     * Hiển thị giao diện nút Vào Lớp của lớp bbb
     * @param type $bbb_info
     * @return type
     */
    function view_button_join_class_bbb($bbb_info, $role) {
        global $USER;
        ob_start();
        if ($role == 'teacher') {
            $bbb_info->link_join_class = $bbb_info->link_join_class_teacher;
        }
        if ($role == 'povh') {
            $bbb_info->link_join_class = $bbb_info->link_join_class_povh;
        }
        ?>

        <div class="action_vcr">
            <a href="<?php echo $bbb_info->link_down_document ?>" target="_blank" class="btn_download e_btn_download_document e_join_class" bbbid="<?php echo $bbb_info->bbb_id ?>" courseid="<?php echo isset($bbb_info->course) ? $bbb_info->course : 2 ?>" data-url="<?php echo $bbb_info->link_log ?>">
                <span><?php echo get_string("down_document", "local_tpelearning"); ?></span>
            </a>
            <?php if ($role == 'student') { ?>
                <a class="e_popup_testpre_vcr" href="<?php echo $bbb_info->testpre_vcr ?>">
                    <span class="" style=""><?php echo get_string("testpre_vcr", "local_tpelearning"); ?></span>
                </a>
            <?php } else { ?>
                <a class="e_popup_lesson_plan" href="<?php echo $bbb_info->link_down_plan ?>">
                    <span class="" style=""><?php echo get_string("down_lesson_plan", "local_tpelearning"); ?></span>
                </a>
                <?php if ($bbb_info->type_class == 'LS') { ?>
                    <a class="e_popup_video e_popup" 
                       href="<?php echo $bbb_info->link_video_warmup ?>">
                    </a>
                <?php } ?>
            <?php } ?>
            <?php if ($bbb_info->timeavailable < $bbb_info->time_now) {//quá thời gian học       ?>
                <div class="hidden e_time_reload_page">
                    <?php echo $bbb_info->timedue - $bbb_info->time_now; ?>
                </div>
                <a class="e_btn_goto_vcr e_join_class" href="<?php echo $bbb_info->link_join_class ?>" bbbid="<?php echo $bbb_info->bbb_id ?>" courseid="<?php echo isset($bbb_info->course) ? $bbb_info->course : 2 ?>" data-url="<?php echo $bbb_info->link_log; ?>">
                    <span class="btn_goto_crv" style="">
                        <?php
                        if ($role == 'student') {
                            echo get_string("btn_name_go_class", "local_tpelearning");
                            if (isset($USER->profile['currentlevel']) && $USER->profile['currentlevel'] == 'sbasic') {
                                ?>
                                <br><div class='l_note_go_class' style='font-size: 10px'>(<?php echo get_string("btn_name_go_class_sbasic", "local_tpelearning"); ?>)</div>
                            <?php
                            }
                        } else {
                            echo get_string("btn_name_go_class_teacher", "local_tpelearning");
                        };
                        ?>
                    </span>
                </a>
                <?php
            } else {
                if ($bbb_info->time_countdown < $bbb_info->value_time_change_btn) {
                    ?>
                    <div class="hidden e_time_reload_page"><?php echo $bbb_info->timedue - $bbb_info->time_now; ?></div>
                    <a class="e_btn_goto_vcr e_join_class" href="<?php echo $bbb_info->link_join_class ?>" bbbid="<?php echo $bbb_info->bbb_id ?>" courseid="<?php echo isset($bbb_info->course) ? $bbb_info->course : 2 ?>" data-url="<?php echo $bbb_info->link_log; ?>">
                        <span class="btn_goto_crv" style="">
                            <?php
                            if ($role == 'student') {
                                echo get_string("btn_name_go_class", "local_tpelearning");
                                if (isset($USER->profile['currentlevel']) && $USER->profile['currentlevel'] == 'sbasic') {
                                    ?>
                                    <br><div class='l_note_go_class' style='font-size: 10px'>(<?php echo get_string("btn_name_go_class_sbasic", "local_tpelearning"); ?>)</div>
                                <?php
                                }
                            } else {
                                echo get_string("btn_name_go_class_teacher", "local_tpelearning");
                            }
                            ?>
                        </span>
                    </a>
            <?php } else { ?>
                    <div class="hidden e_data_url_goto_class">
                            <?php echo $bbb_info->link_join_class ?>
                    </div>
                    <a class="e_btn_goto_vcr" href="#"  bbbid="<?php echo $bbb_info->bbb_id ?>" courseid="<?php echo isset($bbb_info->course) ? $bbb_info->course : 2 ?>" data-url="<?php echo $bbb_info->link_log; ?>">
                        <span class="btn_goto_crv" style="color:#868080;cursor: default;background: #9A9696;">
                            <?php
                            if ($role == 'student') {
                                echo get_string("btn_name_go_class", "local_tpelearning");
                                if (isset($USER->profile['currentlevel']) && $USER->profile['currentlevel'] == 'sbasic') {
                                    ?>
                                    <br><div class='l_note_go_class' style='font-size: 10px'>(<?php echo get_string("btn_name_go_class_sbasic", "local_tpelearning"); ?>)</div>
                                <?php
                                }
                            } else {
                                echo get_string("btn_name_go_class_teacher", "local_tpelearning");
                            };
                            ?>
                        </span>
                    </a>
                    <div class="hidden e_value_time_change_btn_livesession">
                    <?php echo $bbb_info->time_countdown - $bbb_info->value_time_change_btn; ?>
                    </div>
                <?php
            }
        }
        ?>
        </div>

        <?php
        $string = ob_get_clean();
        return $string;
    }

    /**
     * Hiển thị giao diện nút Vào Lớp của lớp bbb autosale
     * @param type $bbb_info
     * @return type
     */
    function view_button_join_class_bbb_autosale($bbb_info) {
        global $USER;
        ob_start();
        ?>
        <div class="action_vcr">
            <?php if ($bbb_info->timeavailable < $bbb_info->time_now) {//quá thời gian học       ?>
                <div class="hidden e_time_reload_page">
                    <?php echo $bbb_info->timedue - $bbb_info->time_now; ?>
                </div>
                <a class="e_btn_goto_vcr e_join_class" href="<?php echo $bbb_info->link_join_class ?>" bbbid="<?php echo $bbb_info->bbb_id ?>" courseid="<?php echo isset($bbb_info->course) ? $bbb_info->course : 2 ?>" data-url="<?php echo $bbb_info->link_log; ?>">
                    <span class="btn_goto_crv" style="">
                        <?php
                        if ($role == 'student') {
                            echo get_string("btn_name_go_class", "local_tpelearning");
                            if (isset($USER->profile['currentlevel']) && $USER->profile['currentlevel'] == 'sbasic') {
                                ?>
                                <br><div class='l_note_go_class' style='font-size: 10px'>(<?php echo get_string("btn_name_go_class_sbasic", "local_tpelearning"); ?>)</div>
                            <?php
                            }
                        } else {
                            echo get_string("btn_name_go_class_teacher", "local_tpelearning");
                        };
                        ?>
                    </span>
                </a>
                <?php
            } else {
                if ($bbb_info->time_countdown < $bbb_info->value_time_change_btn) {
                    ?>
                    <div class="hidden e_time_reload_page"><?php echo $bbb_info->timedue - $bbb_info->time_now; ?></div>
                    <a class="e_btn_goto_vcr e_join_class" href="<?php echo $bbb_info->link_join_class ?>" bbbid="<?php echo $bbb_info->bbb_id ?>" courseid="<?php echo isset($bbb_info->course) ? $bbb_info->course : 2 ?>" data-url="<?php echo $bbb_info->link_log; ?>">
                        <span class="btn_goto_crv" style="">
                            <?php
                            if ($role == 'student') {
                                echo get_string("btn_name_go_class", "local_tpelearning");
                                if (isset($USER->profile['currentlevel']) && $USER->profile['currentlevel'] == 'sbasic') {
                                    ?>
                                    <br><div class='l_note_go_class' style='font-size: 10px'>(<?php echo get_string("btn_name_go_class_sbasic", "local_tpelearning"); ?>)</div>
                                <?php
                                }
                            } else {
                                echo get_string("btn_name_go_class_teacher", "local_tpelearning");
                            }
                            ?>
                        </span>
                    </a>
            <?php } else { ?>
                    <div class="hidden e_data_url_goto_class">
                            <?php echo $bbb_info->link_join_class ?>
                    </div>
                    <a class="e_btn_goto_vcr" href="#"  bbbid="<?php echo $bbb_info->bbb_id ?>" courseid="<?php echo isset($bbb_info->course) ? $bbb_info->course : 2 ?>" data-url="<?php echo $bbb_info->link_log; ?>">
                        <span class="btn_goto_crv" style="color:#868080;cursor: default;background: #9A9696;">
                            <?php
                            if ($role == 'student') {
                                echo get_string("btn_name_go_class", "local_tpelearning");
                                if (isset($USER->profile['currentlevel']) && $USER->profile['currentlevel'] == 'sbasic') {
                                    ?>
                                    <br><div class='l_note_go_class' style='font-size: 10px'>(<?php echo get_string("btn_name_go_class_sbasic", "local_tpelearning"); ?>)</div>
                                <?php
                                }
                            } else {
                                echo get_string("btn_name_go_class_teacher", "local_tpelearning");
                            };
                            ?>
                        </span>
                    </a>
                    <div class="hidden e_value_time_change_btn_livesession">
                    <?php echo $bbb_info->time_countdown - $bbb_info->value_time_change_btn; ?>
                    </div>
                <?php
            }
        }
        ?>
        </div>

        <?php
        $string = ob_get_clean();
        return $string;
    }

    /**
     * Hiển thị giao diện nút Vào Lớp của lớp nhóm thảo luận
     * @param type $sc_info
     * @return type
     */
    function view_button_join_class_bbb_sc($sc_info) {
        global $USER;
        ob_start();
        if ($sc_info->timeavailable < $sc_info->time_now) {
            ?>
            <div class="hidden e_time_conversationroom_reload_page"><?php echo $sc_info->timedue - $sc_info->time_now ?></div>
            <a class="e_join_class" href="<?php echo $sc_info->link_join_class ?>" bbbid="<?php echo $sc_info->bbb_id ?>" courseid="<?php echo $sc_info->course ?>" data-url="<?php echo $sc_info->link_log ?>">
                <span class="btn_goto_crv" ><?php echo get_string("btn_name_go_class_teacher", "local_tpelearning"); ?></span>
            </a>
            <?php
        } else {
            if ($sc_info->time_countdown < $sc_info->value_time_change_btn) {
                ?>
                <div class="hidden e_time_conversationroom_reload_page"><?php echo $sc_info->timedue - $sc_info->time_now ?></div>
                <a class="e_join_class" href="<?php echo $sc_info->link_join_class ?>" bbbid="<?php echo $sc_info->bbb_id ?>" courseid="<?php echo $sc_info->course ?>" data-url="<?php echo $sc_info->link_log ?>">
                    <span class="btn_goto_crv" ><?php echo get_string("btn_name_go_class_teacher", "local_tpelearning"); ?>
                    </span>
                </a>
            <?php } else { ?>
                <div class="hidden e_data_url_goto_class"> <?php echo $sc_info->link_join_class; ?>
                </div>
                <div class="hidden e_value_time_change_btn_conversationroom"><?php echo $sc_info->time_countdown - $sc_info->value_time_change_btn; ?></div>
                <a class="hidden e_btn_goto_vcr" href="#" style="cursor: default;" bbbid="<?php echo $sc_info->bbb_id ?>" courseid="<?php echo $sc_info->course; ?>" data-url="<?php echo $sc_info->link_log ?>">
                    <span class="btn_goto_crv" style="cursor: default;background: #9A9696;"><?php echo get_string("btn_name_go_class_teacher", "local_tpelearning"); ?></span>
                </a>  
                <?php
            }
        }
        $string = ob_get_clean();
        return $string;
    }

    /**
     * Hiển thị giao diện download các tài liệu liên quan của lớp BBB
     * @param type $bbb_info
     * @return type
     */
    function view_document_bbb($bbb_info, $role) {
        ob_start();
        ?>
        <div class="right_field">
            <a href="<?php echo $bbb_info->link_down_document ?>" target="_blank" class="btn_download e_btn_download_document e_join_class" bbbid="<?php echo $bbb_info->bbb_id ?>" courseid="<?php echo isset($bbb_info->course) ? $bbb_info->course : 2 ?>" data-url="<?php echo $bbb_info->link_log ?>">
            <?php echo get_string("down_document", "local_tpelearning"); ?>
            </a>
            <a href="<?php echo $bbb_info->link_down_plan ?>" target="_blank" class="btn_download e_btn_download_document e_join_class" bbbid="<?php echo $bbb_info->bbb_id ?>" courseid="<?php echo isset($bbb_info->course) ? $bbb_info->course : 2 ?>" data-url="<?php echo $bbb_info->link_log ?>">
            <?php echo get_string("down_lesson_plan", "local_tpelearning"); ?>
            </a>

                <?php
                if ($role == 'teacher' || $role == 'povh') {
                    if (isset($bbb_info->video_warmup) && $bbb_info->video_warmup) {
                        ?>
                    <a href="<?php echo $bbb_info->link_video_warmup ?>" class="btn_download e_popup">
                    <?php echo get_string("video_warmup", "local_tpelearning"); ?>
                    </a>
                <?php
            }
        }
        ?>
        </div>
        <?php
        $string = ob_get_clean();
        return $string;
    }

    /**
     * Hiển thị giao diện download các tài liệu liên quan của lớp nhóm thảo luận
     * @param type $sc_info
     * @return type
     */
    function view_document_bbb_sc($sc_info, $role) {
        global $USER;
        ob_start();
        ?>
        <a class="btn_download_topic e_btn_download_document e_join_class" target="_blank" href="<?php echo $sc_info->link_down_document ?>" bbbid="<?php echo $sc_info->bbb_id ?>" courseid="<?php echo $sc_info->course ?>" data-url="<?php echo $sc_info->link_log ?>">
        <?php echo get_string("down_document_sc", "local_tpelearning"); ?>
        </a>
        <?php if (isset($USER->profile['currentlevel']) && $USER->profile['currentlevel'] == 'sbasic') { ?>
            <a class="btn_download_topic e_btn_download_document" target="_blank" href="<?php echo $sc_info->link_down_homework ?>">
                <?php echo get_string("down_document_sc_2", "local_tpelearning"); ?>
            </a>
        <?php } ?>

        <?php if ($role == 'povh') { ?>
            <a class="btn_download_topic e_btn_download_document e_join_class" target="_blank" href="<?php echo $sc_info->link_down_plan ?>" bbbid="<?php echo $sc_info->bbb_id ?>" courseid="<?php echo $sc_info->course ?>" data-url="<?php echo $sc_info->link_log ?>">
            <?php echo get_string("down_lesson_plan", "local_tpelearning"); ?>
            </a>
            <?php
        }

        $string = ob_get_clean();
        return $string;
    }

    /**
     * Hiển thị giao diện các nút chức năng bổ trợ cho lớp BBB (xem hướng dẫn, các lỗi thường gặp, tải teamviwer)
     * @global type $CFG
     * @return type
     */
    function view_support_bbb() {
        global $CFG, $USER, $SESSION;
        $tpeBBB = new TpeBigBlueButton();
        $support_info = array(
            'link_tech_support' => $tpeBBB->getLinkVcr("LMS", 'OTHER', 0, 'TechSupportNewbie'), //link vào lớp kiểm tra kĩ thuật
            'link_faq_vcr' => $CFG->wwwroot . '/local/tpelearning/faqs_vcr.php', //link các câu hỏi thường gặp
            'link_guide_vcr' => $CFG->wwwroot . '/local/tpelearning/guide_vcr.php', //link hướng dẫn sử dụng
            'link_down_teamviewer' => 'http://download.teamviewer.com/download/TeamViewerQS_vi.exe', //link down teamviewer
        );
        ob_start();
        ?>
        <ul class="action_livesession action_livesession_<?php echo isset($SESSION->lang) ? $SESSION->lang : $USER->lang ?>">
            <li>
                <a href="<?php echo $support_info['link_tech_support']; ?>"><?php echo get_string("room_tech_support", "local_tpelearning"); ?></a>
            </li><li class="e_popup" href="<?php echo $support_info['link_guide_vcr'] ?>">
                <a>
        <?php echo get_string("guide_vcr", "local_tpelearning"); ?>
                </a>
            </li><li class="e_popup" href="<?php echo $support_info['link_faq_vcr'] ?>">
                <a>
        <?php echo get_string("faqs_vcr", "local_tpelearning"); ?>
                </a>
            </li><li>
                <a href="<?php echo $support_info['link_down_teamviewer'] ?>" target="_blank">
        <?php echo get_string("down_teamviewer", "local_tpelearning"); ?>
                </a>
            </li>
        </ul>
        <?php
        $string = ob_get_clean();
        return $string;
    }

    /**
     * Hiển thị giao diện cho phần thông tin block lịch học của học viên
     * @param type $arr_block_hour
     * @return type
     */
    function view_col_hour($arr_block_hour) {
        global $CFG;
        $link_block_hour = $CFG->wwwroot . '/local/tpelearning/save_block_hour.php';
        $courseid = 2;
        $hour_now = date('H', time());
        ob_start();
        ?>
        <ul class="col_hour e_col_hour">
            <?php
            for ($i = 0; $i < 24; $i++) {
                $li_option = array();
                $notify = "";
                if ($i < 8) {
                    $li_option = "style='cursor: default'";
                } else {
                    $current_hour = date('H');
                    $class = '';
                    $class_hour_check = ($i >= $hour_now) ? "e_hour_check" : "";
                    if (isset($arr_block_hour[$i])) {
                        $class = ($arr_block_hour[$i]->status == 1) ? 'hour_select' : 'hour_field';
                        if ($i == $current_hour) {
                            $li_option = "data-url='" . $link_block_hour . "' class='" . $class_hour_check . " hour_current " . $class . "' id=" . $arr_block_hour[$i]->id . " status=" . $arr_block_hour[$i]->status . " hour=" . $i . " courseid=" . $arr_block_hour[$i]->courseid;
                        } else {
                            $li_option = "data-url='" . $link_block_hour . "' class='" . $class_hour_check . " " . $class . "' id=" . $arr_block_hour[$i]->id . " status=" . $arr_block_hour[$i]->status . " hour=" . $i . " courseid=" . $arr_block_hour[$i]->courseid;
                        }
                        $notify = get_string("note_not_hour_study", "local_tpelearning");
                    } else {
                        if ($i == $current_hour) {
                            $li_option = "data-url='" . $link_block_hour . "' class='" . $class_hour_check . " hour_field hour_current' id='0' status=1 hour=" . $i . " courseid=" . $courseid;
                        } else {
                            $li_option = "data-url='" . $link_block_hour . "' class='" . $class_hour_check . " hour_field' id='0' hour=" . $i . " status=1 courseid=" . $courseid;
                        }
                        $notify = get_string("note_hour_study", "local_tpelearning");
                    }
                }
                ?>
                <li <?php echo $li_option; ?>><?php echo (0 <= $i && $i < 10) ? '0' . $i : $i; ?>h
                <?php if ($notify && $class_hour_check != "") { ?>
                        <span class="e_notify"><?php echo $notify; ?></span>
            <?php } ?>

                </li>
        <?php } ?>
        </ul>
        <?php
        $string = ob_get_clean();
        return $string;
    }

    /**
     * Hiển thị giao diện thông tin về lịch dạy theo giờ trong ngày của giáo viên
     * @return type
     */
    function view_col_hour_teacher($arr_block_hour) {
        ob_start();
        ?>
        <ul class="col_hour e_col_hour" id="i_tooltip_bbb">
            <?php
            for ($i = 0; $i < 24; $i++) {
                $li_option = array();
                $notify = "";
                if ($i < 8) {
                    ?>
                    <li><?php echo (0 <= $i && $i < 10) ? '0' . $i : $i; ?>h</li>
                    <?php
                } else {
                    $current_hour = date('H');
                    $class = ($i == $current_hour) ? 'hour_current' : '';
                    if (isset($arr_block_hour[$i])) {
                        $class_tooltip = ($i > $current_hour) ? 'tooltip_incomeming' . $class : 'tooltip_wrap' . $class;
                        $class_tooltip = ($i == $current_hour) ? 'tooltip_current' : $class_tooltip;
                        ?>

                        <li class="e_tooltip <?php
                    echo $class_tooltip;
                    echo " " . strtolower($arr_block_hour[$i]->type_class);
                    echo " hour_field_teacher";
                    ?>"><?php echo (0 <= $i && $i < 10) ? '0' . $i : $i; ?>h
                            <div class="info_tooltip_bbb e_info_tooltip_bbb">
                                <div>Chủ đề: <?php echo $arr_block_hour[$i]->subject ?></div>
                                <div>Bắt đầu: <?php echo date('H:i d/m/Y', $arr_block_hour[$i]->timeavailable) ?></div>
                                <div>Loại lớp: <?php echo $arr_block_hour[$i]->type_class ?></div>
                                <div>Level: <?php echo $arr_block_hour[$i]->level_class ?></div>
                                <div><a target="_blank" href="<?php echo $arr_block_hour[$i]->link_down_document; ?>">Xem đề cương môn học</a></div>
                                <div><a target="_blank" href="<?php echo $arr_block_hour[$i]->link_down_document; ?>">Xem lesson plan</a></div>
                                <div><a target="_blank" href="<?php echo $arr_block_hour[$i]->link_video_warmup; ?>" class="e_popup" >Xem video warmup</a></div>
                            </div>
                        </li>
                        <?php
                        } else {
                            ?>
                        <li class="<?php
                            echo $class;
                            echo " hour_field_teacher";
                            ?>"><?php echo (0 <= $i && $i < 10) ? '0' . $i : $i; ?>h</li>
                        <?php
                    }
                }
                ?>

        <?php } ?>
        </ul>
        <?php
        $string = ob_get_clean();
        return $string;
    }

    /**
     * Hiển thị giao diện thông tin về các lớp bbb mở theo giờ trong ngày của povh 
     * @return type
     */
    function view_col_hour_povh($arr_block_hour) {
        ob_start();
        ?>
        <ul class="col_hour e_col_hour">
            <?php
            for ($i = 0; $i < 24; $i++) {
                if ($i < 8) {
                    ?>
                    <li><?php echo (0 <= $i && $i < 10) ? '0' . $i : $i; ?>h</li>
                    <?php
                } else {
                    $current_hour = date('H');
                    $class = ($i == $current_hour) ? 'hour_current' : 'hour_field_povh';
                    if (isset($arr_block_hour[$i]) && count($arr_block_hour[$i])) {
                        ?>
                        <li class="<?php echo $class; ?>"><?php echo (0 <= $i && $i < 10) ? '0' . $i : $i; ?>h
                            <div class="count_bbb">(<?php echo count($arr_block_hour[$i]) ?>)</div>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li class="<?php echo $class; ?>"><?php echo (0 <= $i && $i < 10) ? '0' . $i : $i; ?>h</li>
                    <?php
                }
            }
        }
        ?>
        </ul>
        <?php
        $string = ob_get_clean();
        return $string;
    }

}
