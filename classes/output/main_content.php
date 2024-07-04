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
 * Main Content
 *
 * @package    block_viewstudents
 * @copyright  2024 Tresipunt (http://tresipunt.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_viewstudents\output;

use block_viewstudents\tools;
use dml_exception;
use renderable;
use renderer_base;
use stdClass;
use templatable;

/**
 * Main Content
 *
 * @package    block_viewstudents
 * @copyright  2024 Tresipunt (http://tresipunt.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class main_content implements renderable, templatable {

    /** @var stdClass Course */
    protected $course;

    /**
     * constructor.
     *
     * @param int $courseid
     * @throws dml_exception
     */
    public function __construct(int $courseid) {
        $this->course = get_course($courseid);
    }

    /**
     * Export for Template.
     *
     * @param renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output): stdClass {
        $data = new stdClass();
        $data->students = tools::get_students($this->course);
        return $data;
    }

}