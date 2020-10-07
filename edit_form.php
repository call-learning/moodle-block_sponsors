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
 * Edit Form
 *
 * @package   block_sponsors
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class block_sponsors_edit_form
 *
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_sponsors_edit_form extends block_edit_form {

    /**
     * Form definition
     *
     * @param object $mform
     * @throws coding_exception
     */
    protected function specific_definition($mform) {

        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('advcheckbox', 'config_showtitle', get_string('config:showtitle', 'block_sponsors'));
        $mform->setDefault('config_showtitle', true);


        $columnsarray = [
            '6' => '2',
            '4' => '3',
            '3' => '4',
            '2' => '6'
        ];
        $mform->addElement('select',
            'config_columns',
            get_string('config:columns', 'block_sponsors'),
            $columnsarray
        );
        $mform->setDefault('config_columns', 6);

        $repeatarray = array();
        $repeatedoptions = array();

        $repeatarray[] = $mform->createElement('text',
            'config_orgnames',
            get_string('config:orgnames', 'block_sponsors')
        );
        $repeatedoptions['config_orgnames']['type'] = PARAM_TEXT;

        $repeatarray[] = $mform->createElement('url',
            'config_orglinks',
            get_string('config:orglinks', 'block_sponsors')
        );
        $repeatedoptions['config_orglinks']['type'] = PARAM_URL;
        $repeatarray[] = $mform->createElement('filemanager',
            'config_orglogos',
            get_string('config:orglogos', 'block_sponsors')
        );
        $repeatedoptions['config_orglogos']['type'] = PARAM_RAW;

        $numborgs = empty($this->block->config->orgnames) ? 1 : count($this->block->config->orgnames);
        $numborgs = max($numborgs, empty($this->block->config->orglinks) ? 1 : count($this->block->config->orglinks));
        $numborgs = max($numborgs, empty($this->block->config->orglogos) ? 1 : count($this->block->config->orglogos));
        $this->repeat_elements($repeatarray, $numborgs,
            $repeatedoptions,
            'orgs_repeats', 'orgs_add_fields', 1,
            get_string('addmoreorgs', 'block_sponsors')
        );
    }
}
