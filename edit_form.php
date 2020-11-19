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

        // Title of the block.
        $mform->addElement('text', 'config_title', get_string('config:title', 'block_sponsors'));
        $mform->setDefault('config_title', get_string('title', 'block_sponsors'));
        $mform->setType('config_title', PARAM_TEXT);

        $mform->addElement('advcheckbox', 'config_showtitle', get_string('config:showtitle', 'block_sponsors'));
        $mform->setDefault('config_showtitle', true);

        $columnsarray = [
            block_sponsors::COL_NUMBER_AUTO => get_string('columns:plural', 'block_sponsors', 'auto'),
            '12' => get_string('column', 'block_sponsors', 1),
            '6' => get_string('columns:plural', 'block_sponsors', 2),
            '4' => get_string('columns:plural', 'block_sponsors', 3),
            '3' => get_string('columns:plural', 'block_sponsors', 4),
            '2' => get_string('columns:plural', 'block_sponsors', 6),
            '1' => get_string('columns:plural', 'block_sponsors', 12)
        ];
        $mform->addElement('select',
            'config_columns',
            get_string('config:columns', 'block_sponsors'),
            $columnsarray
        );
        $mform->setDefault('config_columns', block_sponsors::COL_NUMBER_AUTO);

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
            get_string('config:orglogos', 'block_sponsors'),
            null,
            array('subdirs' => 0, 'maxbytes' => FILE_AREA_MAX_BYTES_UNLIMITED, 'maxfiles' => 1,
                'context' => $this->block->context)
        );
        $repeatedoptions['config_orglogos']['type'] = PARAM_RAW;

        $numborgs = $this->get_current_repeats();
        $this->repeat_elements($repeatarray, $numborgs,
            $repeatedoptions,
            'orgs_repeats', 'orgs_add_fields', 1,
            get_string('addmoreorgs', 'block_sponsors')
        );
    }

    /**
     * Set for data
     *
     * @param array|stdClass $defaults
     * @throws coding_exception
     */
    public function set_data($defaults) {
        parent::set_data($defaults);
        // Restore filemanager fields.
        // This is a bit of a hack working around the issues of the block.
        // When using set_data, we set the file data to the real file as it reads it
        // from the block config,
        // not the draft manager file. This can be rectified by a second call to set_data.
        // We try to get the previously submitted file.
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $filefields = new stdClass();
            $numborgs = $this->get_current_repeats();
            require_sesskey(); // This is because we don't use file_get_submitted_draft_itemid.
            for ($index = 0; $index < $numborgs; $index++) {
                $fieldname = 'config_orglogos';
                $filefields->{$fieldname}[$index] = array();
                // Here we could try to use the file_get_submitted_draft_itemid, but it expects to have an itemid defined
                // Which is not what we have right now, we just have a flat list.
                $param = optional_param_array($fieldname, 0, PARAM_INT);
                $draftitemid = $param[$index];
                if (!empty($param[$index])) {
                    $draftitemid = $param[$index];
                }
                file_prepare_draft_area($draftitemid,
                    $this->block->context->id,
                    'block_sponsors',
                    'images',
                    $index, // Index is the logo index.
                    array('subdirs' => 0, 'maxbytes' => FILE_AREA_MAX_BYTES_UNLIMITED, 'maxfiles' => 1,
                        'context' => $this->block->context));

                $filefields->{$fieldname}[$index] = $draftitemid;
            }
            moodleform::set_data($filefields);
        }
    }

    /**
     * Get number of repeats
     */
    protected function get_current_repeats() {
        $numborgs = empty($this->block->config->orgnames) ? 1 : count($this->block->config->orgnames);
        $numborgs = max($numborgs, empty($this->block->config->orglinks) ? 1 : count($this->block->config->orglinks));
        return max($numborgs, empty($this->block->config->orglogos) ? 1 : count($this->block->config->orglogos));
    }
}
