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
 * Renderable
 *
 * @package   block_sponsors
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_sponsors\output;
defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;

/**
 * Class sponsors
 *
 * @package   block_sponsors
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class sponsors implements renderable, templatable {
    /**
     * @var array orgs
     */
    public $orgs = [];

    /**
     * @var int colnum
     */
    public $colspan;

    /**
     * sponsors constructor.
     * Retrieve all sponsors
     *
     * @param array $orgnames
     * @param array  $orglinks
     * @param array  $orglogos
     * @param int $blockcontextid
     * @param int $colspan
     * @throws \coding_exception
     * @throws \moodle_exception*
     */
    public function __construct($orgnames, $orglinks, $orglogos, $blockcontextid, $colspan) {
        $numborgs = empty($orgnames) ? 1 : count($orgnames);
        $numborgs = max($numborgs, empty($orglinks) ? 1 : count($orglinks));
        $numborgs = max($numborgs, empty($orglogos) ? 1 : count($orglogos));
        $fs = get_file_storage();

        for ($orgindex = 0; $orgindex < $numborgs; $orgindex++) {
            $neworg = new \stdClass();
            // At least an image.
            if (empty($orglogos[$orgindex])) {
                continue;
            }
            $allfiles = $fs->get_area_files($blockcontextid, 'block_sponsors', 'images', $orgindex);
            foreach ($allfiles as $file) {
                if ($file->is_valid_image()) {
                    $neworg->logourl = \moodle_url::make_pluginfile_url(
                        $file->get_contextid(),
                        $file->get_component(),
                        $file->get_filearea(),
                        $file->get_itemid(),
                        $file->get_filepath(),
                        $file->get_filename()
                    )->out();
                    break; // Take the first valid image.
                }
            }
            if (empty($neworg->logourl)) {
                continue; // No image found.
            }
            $neworg->link = !empty($orglinks[$orgindex]) ? (new \moodle_url($orglinks[$orgindex]))->out() : '';
            $neworg->name = !empty($orgnames[$orgindex]) ? $orgnames[$orgindex] : '';
                        $this->orgs[] = $neworg;
        }
        if ($colspan) {
            $this->colspan = $colspan;
        }
    }

    /**
     * Export the sponsors entity
     *
     * @param renderer_base $renderer
     * @return array|\stdClass
     */
    public function export_for_template(renderer_base $renderer) {
        $exportedvalue = [
            'orgs' => array_values((array) $this->orgs),
            'count' => count($this->orgs),
            'colspan' => $this->colspan
        ];
        return $exportedvalue;
    }
}