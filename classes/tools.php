<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Tools.
 *
 * @package    block_viewstudents
 * @copyright  2024 Tresipunt (http://tresipunt.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_viewstudents;


use coding_exception;
use course_enrolment_manager;
use dml_exception;
use moodle_exception;
use moodle_url;
use stdClass;
use user_picture;
use core_user;

global $CFG;
require_once($CFG->dirroot . '/enrol/locallib.php');

/**
 * Tools.
 *
 * @package    block_viewstudents
 * @copyright  2024 Tresipunt (http://tresipunt.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tools  {

    /**
     * Get Students.
     *
     * @param stdClass $course
     * @return array
     * @throws dml_exception
     * @throws coding_exception
     * @throws moodle_exception
     */
    public static function get_students(stdClass $course): array {
        global $PAGE, $DB;
        $students = [];
        $role = $DB->get_record('role', ['shortname' => 'student']);

        if ($role) {
            $enrolmanager = new course_enrolment_manager($PAGE, $course, null, $role->id);
            $users = $enrolmanager->get_users('u.lastname', 'ASC', 0, 0);
            foreach ($users as $user) {
                $user = core_user::get_user($user->id);
                $userpicture = new user_picture($user);
                $userpicture->size = 1;

                $url = new moodle_url('/user/view.php', [
                    'id' => $user->id, 'course' => $course->id
                ]);

                $student = new stdClass();
                $student->picture = $userpicture->get_url($PAGE)->out(false);
                $student->fullname = fullname($user);
                $student->url = $url->out(false);;
                $student->lastaccess = self::last_access($user->lastaccess);
                $student->iscurrent = self::is_connected($user);
                $students[] = $student;
            }
        }

        return $students;
    }

    /**
     * Last Access.
     *
     * @param int|null $lastaccess
     * @return string
     * @throws coding_exception
     */
    public static function last_access(int $lastaccess = null): string {
        if (empty($lastaccess)) {
            return get_string('never', 'calendar');
        } else {
            return get_string('ago', 'message', format_time(time() - $lastaccess));
        }

    }

    /**
     * Is Connected?
     *
     * @param stdClass $user
     * @return string
     */
    public static function is_connected(stdClass $user): string {
        $now = time();
        $lastaccess = isset($user->lastaccess) ? $user->lastaccess: 0;
        return $now - (int)$lastaccess < 1800;
    }

}
