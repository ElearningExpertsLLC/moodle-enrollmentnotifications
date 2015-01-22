<?php


defined('MOODLE_INTERNAL') || die();

/**
 * Admin menu link for future configuration settings
 * function local_enrollmentnotifications_extends_settings_navigation(settings_navigation &$settings, $node) {
 * $root = $settings->get('root');
 * if ($root !== false && has_capability('moodle/site:config', context_system::instance())) {
 * $admintools = $root->create(
 * get_string('local_enrollmentnotifications_menu', 'local_enrollmentnotifications'),
 * new moodle_url('/local/enrollmentnotifications/view.php'),
 * navigation_node::TYPE_SETTING,
 * null,
 * null,
 * new pix_icon('i/settings', '')
 * );
 * $root->add_node($admintools);
 * }
 * }
 */

function local_enrollmentnotifications_user_enrolled($eventdata) {
    global $DB, $CFG;

    // check if the enrollment is a result of uploading/importing completion records
    if (preg_match("/totara\/completionimport/i", $_SERVER['SCRIPT_FILENAME'] )) {
        // don't send enrollment email when uploading/importing completions
        return;
    }

    if ($eventdata->enrol != 'manual' && $eventdata->enrol != 'cohort') {
        return;
    }

    $course = $DB->get_record('course', array('id' => $eventdata->courseid));

    // Efficiently fetch multiple user IDs. For big audiences, it could be 100s.
    if (isset($eventdata->userids)) {
        $users = $DB->get_records_list('user', 'id', array_keys($eventdata->userids));
    } else {
        $users = array($eventdata->userid => $DB->get_record('user', array('id' => $eventdata->userid)));
    }

    /**
     * Do not send notification if user has RPL completion record - filter those out
     * Notifications should not be sent to RPL completions, so the code below to filter RPL completion has been completely commented
     * if (isset($CFG->enrollmentnotifications_suppressrpl) && $CFG->enrollmentnotifications_suppressrpl) {
     * $userlist = array_keys($users);
     * list($sql, $sqlparams) = $DB->get_in_or_equal($userlist);
     * $sqlparams[] = $course->id;
     * $filteredusers = $DB->get_records_sql("SELECT userid FROM {course_completions} WHERE userid $sql AND course = ? AND (status = 75 OR status = 50)", $sqlparams);
     *
     * foreach ($filteredusers as $filtereduser) {
     * unset($users[$filtereduser->userid]);
     * }
     * }
     */

     foreach ($users as $user) {
        $removefromlist = false;
        // Check for more than 1 enrollment.
        if (count(local_enrollmentnotifications_user_all_enrollments($user->id, $course->id)) > 1) {
           $removefromlist = true;
        }
        // Check for completed and completed via RPL.
        $ccparams = array('userid' => $user->id,'course'  => $course->id);
        $ccompletion = new completion_completion($ccparams);
        if ($ccompletion->status == COMPLETION_STATUS_COMPLETE || $ccompletion->status == COMPLETION_STATUS_COMPLETEVIARPL) {
           $removefromlist = true;
        }
        // Check for F2F notification suppression.
        if ($CFG->suppressf2fnotification) {
           $removefromlist = true;
        }
        if ($removefromlist) {
            unset($users[$user->id]);
        }
    }
    if ($users) {
        local_enrollmentnotifications_email($course, $users);
    }
}

function local_enrollmentnotifications_email($course, $users) {
    global $CFG;

    $context = context_course::instance($course->id);

    $a = new stdClass();
    $a->coursename = format_string($course->fullname, true, array('context' => $context));
    $a->courseurl = "$CFG->wwwroot/course/view.php?id=$course->id";
    $strmgr = get_string_manager();

    $rusers = array();
    if (!empty($CFG->coursecontact)) {
        $croles = explode(',', $CFG->coursecontact);
        list($sort, $sortparams) = users_order_by_sql('u');
        $rusers = get_role_users($croles, $context, true, '', 'r.sortorder ASC, ' . $sort, null, '', '', '', '', $sortparams);
    }
    if ($rusers) {
        $contact = reset($rusers);
    } else {
        $contact = generate_email_supportuser();
    }

    foreach ($users as $user) {
        $a->profileurl = "$CFG->wwwroot/user/view.php?id=$user->id&course=$course->id";

        $messagetext = $strmgr->get_string('welcometocoursetext', 'local_enrollmentnotifications', $a, $user->lang);
        $messagehtml = text_to_html($messagetext, null, false, true);

        $subject = $strmgr->get_string('welcometocourse', 'local_enrollmentnotifications', format_string($course->fullname, true, array('context' => $context)), $user->lang);
        $subject = str_replace('&amp;', '&', $subject);

        email_to_user($user, $contact, $subject, $messagetext, $messagehtml);
    }
}
// check for multiple enrollements for a user in a course.
function local_enrollmentnotifications_user_all_enrollments($userid, $courseid){
     global $DB;
     $sqlparams['userid'] = $userid;
     $sqlparams['courseid'] = $courseid;
     $sql = "SELECT e.enrol, e.courseid, ue.enrolid, ue.userid
            FROM {user_enrolments} ue
            INNER JOIN {enrol} e ON e.id = ue.enrolid
            WHERE ue.userid = :userid
            AND e.courseid = :courseid";
    return $DB->get_records_sql($sql, $sqlparams);
}