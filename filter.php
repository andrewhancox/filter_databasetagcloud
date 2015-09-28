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
 * @package    filter_databasetagcloud
 * @copyright  2015 onwards Andrew Hancox (andrewdchancox@googlemail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Activity name filtering
 */
class filter_databasetagcloud extends moodle_text_filter {
    public function filter($text, array $options = array()) {
        global $CFG;
        require_once("$CFG->dirroot/blocks/databasetags/block_databasetags.php");

        $matches = array();
        preg_match_all('/databasetagcloud_field_[0-9]+/', $text, $matches);

        foreach ($matches as $matchlist) {
            foreach ($matchlist as $match) {
                if (empty($match)) {
                    continue;
                }

                $fieldid = substr($match, 23);
                $fieldid = (int)$fieldid;

                if (!isset($fieldid)) {
                    return $text;
                }

                $tags = block_databasetags::get_tags(array($fieldid));
                $cloud = block_databasetags::tag_print_cloud($tags);

                $text = str_replace($match, $cloud, $text);
            }
        }

        return $text;
    }
}