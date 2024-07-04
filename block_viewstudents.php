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
use block_viewstudents\output\main_content;


/**
 * Block View Students.
 *
 * @package    block_viewstudents
 * @copyright  2024 Tresipunt (http://tresipunt.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_viewstudents extends block_base {

    /**
     * Init.
     *
     * @throws coding_exception
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_viewstudents');
    }

    /**
     * Which page types this block may appear on.
     *
     * The information returned here is processed by the
     * {@link blocks_name_allowed_in_format()} function. Look there if you need
     * to know exactly how this works.
     *
     * Default case: everything except mod and tag.
     *
     * @return array page-type prefix => true/false.
     */
    function applicable_formats() {
        return [
            'course-view' => true,
            'site' => false,
            'mod' => false,
            'my' => false,
        ];
    }

    /**
     * Hide Header
     *
     * @return boolean
     */
    public function hide_header(): bool {
        return true;
    }

    /**
     * Get content.
     *
     * @return stdClass
     * @throws coding_exception
     * @throws dml_exception
     */
    public function get_content(): stdClass {
        global $COURSE;
        if (isset($this->content)) {
            return new stdClass();
        }
        $this->content = new stdClass();
        if (isloggedin() && !isguestuser() && isset($COURSE->id)) {
            $renderer = $this->page->get_renderer('block_viewstudents');
            $maincontent = new main_content($COURSE->id);
            $this->content->text = $renderer->render($maincontent);;
            $this->content->footer = '';
        }

        return $this->content;
    }


}