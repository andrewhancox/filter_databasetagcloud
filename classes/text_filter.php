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

namespace filter_databasetagcloud;

use block_databasetags;

class text_filter extends \core_filters\text_filter {
    #[\Override]
    public function filter($text, array $options = []) {
        global $CFG;
        require_once("$CFG->dirroot/blocks/moodleblock.class.php");
        require_once("$CFG->dirroot/blocks/databasetags/block_databasetags.php");

        $matches = [];
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

                $tags = block_databasetags::get_tags([$fieldid]);
                $cloud = block_databasetags::tag_print_cloud($tags);

                $text = str_replace($match, $cloud, $text);
            }
        }

        return $text;
    }
}
