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
 * Prints a particular instance of test
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_test
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace test with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... test instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('test', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $test  = $DB->get_record('test', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $test  = $DB->get_record('test', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $test->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('test', $test->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_test\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $test);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/test/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($test->name));
$PAGE->set_heading(format_string($course->fullname));

/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('test-'.$somevar);
 */

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($test->intro) {
    echo $OUTPUT->box(format_module_intro('test', $test, $cm->id), 'generalbox mod_introbox', 'testintro');
}

echo "ciao";

// Replace the following lines with you own code.
echo $OUTPUT->heading('Yay! It works!');

// Finish the page.
echo $OUTPUT->footer();
