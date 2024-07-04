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
 * Enrol External.
 *
 * @package    block_viewstudents
 * @copyright  2024 Tresipunt (http://tresipunt.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_viewstudents\external;

use dml_exception;
use external_api;
use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;
use invalid_parameter_exception;
use mod_jokeofday\models\joke;
use mod_jokeofday\models\score;
use moodle_exception;
use stdClass;

global $CFG;
require_once($CFG->libdir . '/externallib.php');

class enrol_external extends external_api {

    /**
     * Joke Parameters.
     *
     * @return external_function_parameters
     */
    public static function enrol_user_parameters(): external_function_parameters {
        return new external_function_parameters(
                [
                        'email' => new external_value(PARAM_TEXT, 'User Email'),
                        'courseid' => new external_value(PARAM_INT, 'Course ID'),
                        'firstname' => new external_value(PARAM_TEXT, 'First Name'),
                        'lastname' => new external_value(PARAM_TEXT, 'Last Name'),
                ]
        );
    }

    /**
     * Score
     *
     * @param string $email
     * @param int $courseid
     * @param string $firstname
     * @param string $lastname
     * @return array
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws moodle_exception
     */
    public static function enrol_user(string $email, int $courseid, string $firstname, string $lastname): array {
        global $DB;
        $params = self::validate_parameters(
                self::enrol_user_parameters(), [
                        'email' => $email,
                        'courseid' => $courseid,
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                ]
        );

        $error = '';

        $data = new stdClass();

        try {
            $course = get_course($params['courseid']);
            $data->courseid = $course->id;

            $user = $DB->get_record('user', ['email' => $email]);
            if ($user) {
                // Matriculamos al usuario.
                $data->userid = $user->id;
                $data->action = 'update';

                // TODO. Matriculación.
            } else {
                // Creamos al usuario.
                $data->userid = 3434;
                $data->action = 'create';
                // TODO. Crear el usuario.


                // TODO. Matricular al usuario.
            }
            $success = true;
        } catch (moodle_exception $e) {
            $error = $e->getMessage();
            $success = false;
        }

        return [
                'success' => $success,
                'error' => $error,
                'data' => $data
        ];

    }

    /**
     * Score Returns.
     *
     * @return external_single_structure
     */
    public static function enrol_user_returns(): external_single_structure {
        return new external_single_structure(
               [
                       'success' =>  new external_value(PARAM_BOOL, 'Was it a success?'),
                       'error' =>  new external_value(PARAM_TEXT, 'Error message'),
                       'data' =>  new external_single_structure(
                               [
                                       'courseid' => new external_value(PARAM_INT, 'Course ID'),
                                       'userid' => new external_value(PARAM_INT, 'User ID'),
                                       'action' => new external_value(PARAM_TEXT, 'Create/Update'),
                               ]
                       ),
               ]
        );
    }



}

