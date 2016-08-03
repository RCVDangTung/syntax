<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
date_default_timezone_set('Asia/Ho_Chi_Minh'); //cài đặt múi giờ
global $USER, $DB, $CFG;
$id = required_param('id', PARAM_INT);
if ($id) {
    $course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
} else {
    redirect($CFG->wwwroot, 'Tài khoản không thuộc khóa học nào cả', 10);
}
require_login($course); //kiểm tra login
$context = context_course::instance($course->id);
$contextid = $context->id;
$viewmanager = has_capability("local/tpelearning:viewmanager", $context);
if ($viewmanager) {
    if (isset($_POST['submit'])) {
        $where = " WHERE u.lastname like '%" . trim($_POST['teacheremail'], " ") . "%' or u.firstname like '%" . trim($_POST['teacheremail'], " ") . "%' or u.email like '%" . trim($_POST['teacheremail'], " ") . "%'";
    } else {
        $where = " WHERE t.created_id =" . $USER->id;
        // WHERE t.time_tag >=" . time()
    }
    $get_total_teacher = $DB->get_records_sql("SELECT count(id) as total from tag_teacher", array());
    ;
    foreach ($get_total_teacher as $key => $value) {
        $total_teacher = $value->total;
    }
    $perpage = 30;
    $page = isset($_GET['page']) ? $_GET['page'] : 0;
    if ($page < 0) {
        $page = 0;
    }
    $start = $page * $perpage;
    $limit = " LIMIT $start,$perpage";
    $teacher_list = $DB->get_records_sql("SELECT t.id,t.userid,t.status,t.created_date,u.email,u.firstname,u.lastname,t.time_tag,t.created_id from tag_teacher t LEFT JOIN mdl_user u on(u.id = t.userid)$where ORDER BY t.status DESC $limit", array());


    // TUNGND update trang thai chạy dùng cron_job 
    $time_minus = time() - (40 * 60);
    $where = " WHERE t.time_tag <= '" . time() . "' and t.created_id =" . $USER->id;
    $teacher_list_active = $DB->get_records_sql("SELECT t.id,t.userid,t.status,t.created_date,u.email,u.firstname,u.lastname,t.time_tag,t.created_id from tag_teacher t LEFT JOIN mdl_user u on(u.id = t.userid)$where ORDER BY t.status DESC $limit", array());
    $time_current = time();
    if ($teacher_list_active && count($teacher_list_active) > 0) {
        foreach ($teacher_list_active as $key_v => $value_v) {
            if (isset($value_v->time_tag) && $value_v->time_tag >= $time_minus) {
                $DB->execute("UPDATE tag_teacher SET status = 0,created_id = $value_v->created_id,time_tag = 0 where id = $value_v->id");
                // echo 'ok';
            }
        }
    }
    // die();
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <base href="">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title><?php echo get_string("hoc-truc-tuyen", "theme_topmito"); ?></title>
            <link rel="stylesheet" href="<?php echo $CFG->wwwroot ?>/local/tpelearning/externalfile/css/style.css">
            <link rel="stylesheet" href="<?php echo $CFG->wwwroot ?>/local/tpebbb/manager/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        </head>
        <body>
            <div id="header2">
                <header class="home">
                    <a href="<?php echo $CFG->wwwroot; ?>"><img class="logo" src="<?php echo $CFG->wwwroot; ?>/local/tpelearning/externalfile/images/logo.png" alt=""></a>
                    <form action="<?php echo $CFG->wwwroot; ?>/blocks/lessonsearch/index.php" class="search" method="post">
                        <input type="text" name="" value="" placeholder="<?php echo get_string("tpe_home_search_placeholder", "theme_topmito"); ?>">
                    </form>
                    <div class="logininfo">
                        <a href="<?php echo $CFG->wwwroot; ?>/user/tpeuser/tpe_profile.php#tab1"><img src="<?php
                            if ($USER->picture == 0) {
                                echo "externalfile/images/user.png";
                            } else {
                                $context1 = context_user::instance($USER->id);
                                $idpic = $context1->id;
                                echo $CFG->wwwroot . "/pluginfile.php/" . $idpic . "/user/icon/topmito/f2?rev=" . $USER->picture;
                            }
                            ?>

                                                                                                      " alt="<?php echo get_string("viewprofile", "moodle"); ?> <?php echo $USER->firstname . ' ' . $USER->lastname; ?>" title="<?php echo $USER->firstname . ' ' . $USER->lastname; ?>"></a>
                        <a title="<?php echo get_string("viewprofile", "moodle"); ?> <?php echo $USER->firstname . ' ' . $USER->lastname; ?>" href="<?php echo $CFG->wwwroot; ?>/user/tpeuser/tpe_profile.php#tab1"><?php echo $USER->firstname . ' ' . $USER->lastname; ?></a>
                        <div class="setting_link">
                            <ul id="menu_header">
                                <li style="font-family: arial;" class="icon_user"><?php echo get_string("tpepersonal", "theme_topmito"); ?></li>
                                <li class='item'><a href='#'><?php echo get_string("tpelang", "theme_topmito"); ?></a>
                                    <ul>
                                        <li><a href='<?php echo $CFG->wwwroot; ?>/local/tpelearning/index.php?id=2&lang=en' ><?php echo get_string("tpelangen", "theme_topmito"); ?></a></li>
                                        <li class=''><a href='<?php echo $CFG->wwwroot; ?>/local/tpelearning/index.php?id=2&lang=vi' ><?php echo get_string("tpelangvn", "theme_topmito"); ?></a></li>
                                        <li class='' ><a href='<?php echo $CFG->wwwroot; ?>/local/tpelearning/index.php?id=2&lang=th' ><?php echo get_string("tpelangth", "theme_topmito"); ?></a></li>
                                        <li class='' ><a href='<?php echo $CFG->wwwroot; ?>/local/tpelearning/index.php?id=2&lang=id' ><?php echo get_string("tpelangid", "theme_topmito"); ?></a></li>
                                    </ul>
                                </li>
                                <li class="item week_goal"><a href="#"><?php echo get_string("tpechangegoal", "theme_topmito"); ?></a>
                                    <ul class="week_goal_hour">
                                        <li class='e_select_goal' val="1">1 <?php echo get_string("tpetimegoal", "theme_topmito"); ?></li>
                                        <li class='e_select_goal' val="2">2 <?php echo get_string("tpetimegoal", "theme_topmito"); ?></li>
                                        <li class='e_select_goal' val="3">3 <?php echo get_string("tpetimegoal", "theme_topmito"); ?></li>
                                        <li class='e_select_goal' val="4">4 <?php echo get_string("tpetimegoal", "theme_topmito"); ?></li>
                                        <li class='e_select_goal' val="5">5 <?php echo get_string("tpetimegoal", "theme_topmito"); ?></li>
                                        <li class='e_select_goal' val="6">6 <?php echo get_string("tpetimegoal", "theme_topmito"); ?></li>
                                        <li class='e_select_goal' val="7">7 <?php echo get_string("tpetimegoal", "theme_topmito"); ?></li>
                                        <li class='e_select_goal' val="8">8 <?php echo get_string("tpetimegoal", "theme_topmito"); ?></li>
                                        <li class='e_select_goal' val="9">9 <?php echo get_string("tpetimegoal", "theme_topmito"); ?></li>
                                        <li class='e_select_goal' val="10">10 <?php echo get_string("tpetimegoal", "theme_topmito"); ?></li>
                                    </ul>
                                </li>
                                <li class='item'><a href="<?php echo $CFG->wwwroot; ?>/user/tpeuser/tpe_profile.php#tab1"><?php echo get_string("tpeeditprofile", "theme_topmito"); ?></a></li>
                                <li class="icon_support"><?php echo get_string("tpesupport", "theme_topmito"); ?></li>
                                <li class="item"><a href="#"><?php echo get_string("tpesupportcenter", "theme_topmito"); ?></a></li>
                                <li class="item"><a href="<?php echo $CFG->wwwroot; ?>/login/logout.php?sesskey=xxp46ey2Hp"><?php echo get_string("tpe_logout", "theme_topmito"); ?></a></li>
                            </ul>
                        </div>
                        <span class="setting_link_tmp"></span>
                    </div>
                </header>
            </div>
            <div class="clear"></div>
            <nav id="menu">
                <ul>
                    <li class="active"><a href="<?php echo $CFG->wwwroot; ?>/local/tpelearning/index.php?id=2"><?php echo get_string("title_livesesson", "local_tpelearning"); ?></a></li>
                    <?php
                    if ($viewmanager) {
                        $context = context_course::instance($CFG->tpe_config->courseid);
                        $roles = get_user_roles($context, $USER->id, true);
                        foreach ($roles as $value) {
                            $rolePOHC = $value->shortname;
                        }
                        if ($rolePOHC == 'pohc') {
                            ?>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/tpebbb/techroom.php?id=2"><?php echo get_string("techroom", "theme_topmito"); ?></a></li>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/tpebbb/manager/runningclass_fullhd.php?id=2"><?php echo get_string("cac-lop-dang-chay", "theme_topmito"); ?></a></li>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/tpebbb/manager/created_class.php?id=2"><?php echo get_string("cac-lop-da-mo", "theme_topmito"); ?></a></li>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/tpebbb/manager/view_teacher.php?type=teco260"><?php echo get_string("teco", "theme_topmito"); ?></a></li>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/tpebbb/manager/view_teacher.php?type=total_register_schedule"><?php echo get_string("student_type", "local_materialservice"); ?></a></li>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/logsservice/index.php"><?php echo get_string("logs", "theme_topmito"); ?></a></li>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/tagteacher/index.php?id=2">TagTeacher</a></li>
                            <?php
                        } else {
                            ?>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/materialservice/view.php?id=2"><?php echo get_string("mo-phong-hoc", "theme_topmito"); ?></a></li>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/logsservice/index.php"><?php echo get_string("logs", "theme_topmito"); ?></a></li>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/tpebbb/techroom.php?id=2"><?php echo get_string("techroom", "theme_topmito"); ?></a></li>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/tpebbb/manager/runningclass_fullhd.php?id=2"><?php echo get_string("cac-lop-dang-chay", "theme_topmito"); ?></a></li>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/tpebbb/manager/created_class.php?id=2"><?php echo get_string("cac-lop-da-mo", "theme_topmito"); ?></a></li>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/logsservice/users.php"><?php echo get_string("danh-sach-hoc-vien", "theme_topmito"); ?></a></li>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/tpebbb/manager/view_teacher.php?type=teco260"><?php echo get_string("teco", "theme_topmito"); ?></a></li>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/logsservice/povh.php"><?php echo get_string("log-truc-po", "theme_topmito"); ?></a></li>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/materialservice/orientation/view.php?courseid=2"><?php echo get_string("mo-lop-orien", "theme_topmito"); ?></a></li>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/userorientation/view.php"><?php echo get_string("tagori", "theme_topmito"); ?></a></li>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/useradobe/view.php">Tagadb</a></li>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/tpeadb/set_host_teacher.php">Sethost</a></li>
                            <li><a href="<?php echo $CFG->wwwroot; ?>/local/tpeadb/create_user_adobe.php">Useradb</a></li>
            <?php
        }
        ?>
                        <?php
                    }
                    ?>
                </ul>
            </nav>
            <main id="tag">
                <form action="" method="post">
                    <div class="col-md-12">
                        <div class="col-md-2">
                            <label class="label anhlh2" for="">Key word</label>
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" name="id" value="2">
                            <input id="teacheremail" type="text" name="teacheremail" value="<?php echo isset($_POST['teacheremail']) ? $_POST['teacheremail'] : ''; ?>" placeholder="Key word" class="form-control form-xs">
                        </div>
                        <div class="col-md-3">
                            <button name="submit" style="margin-top: 5px !important;width: 60px;" class="btn btn-success btn-xs">Search</button>
                        </div>
                    </div>  
                </form>
                <div class="col-md-12" style="margin: 10px 0px;">
                    <div class="col-md-2">
                        <label class="label anhlh2" for="">Teacher ID</label>   
                    </div>
                    <div class="col-md-3">
                        <input style="margin-top: 5px !important;" id="teacherid" type="text" name="" value="" placeholder="Teacher ID" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <p id="add" style="margin-top: 12px !important;width: 60px;" class="btn btn-danger btn-xs">Add</p>
                    </div>
                </div>
                <div class="col-md-12" style="margin: 10px 0px;">
                    <div class="col-md-2">
                        <label class="label anhlh2" for="">Thời gian tồn tại</label>   
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="timetag" name="time_tag">
                            <option value="15">15 phút</option>
                            <option value="30">30 phút</option>
                            <option value="40" selected="selected">40 phút</option>
                            <option value="45">45 phút</option>
                        </select>
                    </div>
                </div>
                <script>
                    $(document).ready(function () {
                        $('#add').click(function (event) {
                            var teacherid = $('#teacherid').val();
                            var timetag = $('#timetag').val();
                            var created_id = '<?php echo $USER->id; ?>';
                            var data = {"type": 1, "teacherid": teacherid, 'created_id': created_id, 'time_tag': timetag};
                            $.ajax({
                                type: "POST",
                                url: "<?php echo $CFG->wwwroot; ?>/local/tagteacher/ajax.php",
                                data: data,
                                success: function (result) {
                                    switch (result) {
                                        case '0':
                                            alert('Tài khoản này không thuộc loại tài khoản giáo viên !');
                                            break;
                                        case '1':
                                            alert('Tài khoản đã được gắn tag thành công !');
                                            window.location.href = "<?php echo $CFG->wwwroot; ?>/local/tagteacher/index.php?id=2";
                                            break;
                                        case '2':
                                            alert('Tài khoản đã được chuyển về trạng thái Active !');
                                            window.location.href = "<?php echo $CFG->wwwroot; ?>/local/tagteacher/index.php?id=2";
                                            break;
                                    }
                                },
                                async: false
                            });
                        });
                        $('.active').click(function (event) {
                            var id = $(this).attr('data-id');
                            var status = $(this).attr('data-status');
                            var created_id = '<?php echo $USER->id; ?>';
                            var timetag = $('#timetag').val();
                            var data = {"type": 2, "id": id, 'created_id': created_id, 'status': status, 'time_tag': timetag};
                            $.ajax({
                                type: "POST",
                                url: "<?php echo $CFG->wwwroot; ?>/local/tagteacher/ajax.php",
                                data: data,
                                success: function (result) {
                                    if (result == '0') {
                                        alert('Active tài khoản thành công !');
                                    } else {
                                        alert('Deactive tài khoản thành công !');
                                    }
                                    window.location.href = "<?php echo $CFG->wwwroot; ?>/local/tagteacher/index.php?id=2";
                                },
                                async: false
                            });
                        });
                        $('.delete').click(function (event) {
                            var r = confirm("Bạn có chắc chắn muốn xóa tag tài khoản này ?");
                            if (r) {
                                var id = $(this).attr('data-id');
                                var data = {"type": 3, "id": id};
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo $CFG->wwwroot; ?>/local/tagteacher/ajax.php",
                                    data: data,
                                    success: function (result) {
                                        alert('Xóa tag tài khoản thành công !');
                                        window.location.href = "<?php echo $CFG->wwwroot; ?>/local/tagteacher/index.php?id=2";
                                    },
                                    async: false
                                });
                            }
                        });
                    });
                </script>  
                <section id="list-teacher" class="container-fluid">
                    <table class="table table-striped">
                        <tr>
                            <th>Id</th>
                            <th>Userid</th>
                            <th>Fullname</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
    <?php
    $status = array('Deactive', 'Active');
    ?>
                        <?php foreach ($teacher_list as $key => $value): ?> 
                            <tr>
                                <td><?php echo $value->id; ?></td>
                                <td><?php echo $value->userid; ?></td>
                                <td><?php echo $value->firstname . ' ' . $value->lastname; ?></td>
                                <td><?php echo $value->email; ?></td>
                                <td><?php echo $status[$value->status]; ?></td>
                                <td>
                                    <button data-status="<?php echo $value->status; ?>" data-id="<?php echo $value->id; ?>" class="active <?php if ($value->status == 1) {
                        echo "hidden ";
                    } ?>btn btn-xs btn-success">Active</button>
                                    <button data-status="<?php echo $value->status; ?>" data-id="<?php echo $value->id; ?>" class="active <?php if ($value->status == 0) {
                        echo "hidden ";
                    } ?>btn btn-xs btn-primary">Deactive</button>
                                    <button data-id="<?php echo $value->id; ?>" class="delete btn btn-xs btn-danger">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                    <ul class="navi">
                        <?php
                        for ($i = 0; $i < $total_teacher / $perpage; $i++) {
                            $j = $i + 1;
                            echo "<li>";
                            if ($i == $page) {
                                echo "<strong>" . $j . "</strong>";
                            } else {
                                echo "<a href='" . $CFG->wwwroot . "/local/tagteacher/index.php?id=2&page=" . $i . "'>" . $j . "</a>";
                            }
                            echo "</li>";
                        }
                        ?>
                    </ul>
                </section>
            </main>
            <style>
                #list-teacher th,#list-teacher td{text-align: center;}
                #tag{background: #fff;padding: 20px 0px;margin: 10px auto;width: 960px;overflow: hidden;border-radius: 10px;}
                form{overflow: hidden;}
                label.anhlh2{line-height: 35px !important;font-size: 100% !important;color:#000 !important;}
                #list-teacher{padding-top: 30px;}
                #list-teacher button{width: 60px;}

                .navi{height:50px;padding-top: 10px;}
                .navi li{float: left;list-style: none;}
                .navi a,.navi strong{border:1px solid #27ae60;border-radius:15px;color:#000;display:block;float:left;font-size:14px;height:30px;line-height:30px;margin:0px 10px;text-align:center;width:30px;text-decoration: none;float: left;}
                .navi strong,.navi a:hover{background:#27ae60;border:1px solid #27ae60;color:#000;}
            </style>
            <script>
                $(document).ready(function () {
                    // TUNGND set 2 minute reload page update data
                    setInterval(function () {
                        // window.location.href = "<?php echo $CFG->wwwroot; ?>/local/tagteacher/index.php?id=2";
                        location.reload();
                    }, 30000) // milliseconds
                });
            </script>
        </body>
    </html>
    <?php
}
?>