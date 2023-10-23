@block @block_sponsors @javascript @_file_upload
Feature: Adding and configuring Sponsors block
  In order to have the Sponsors block used
  As a admin
  I need to add the Sponsors block to the front page

  Scenario: Adding Sponsors block and I change the image, this should result in the new image being displayed.
    Given I log in as "admin"
    And I am on site homepage
    And I turn editing mode on
    And I add the "Sponsors" block
    And I configure the "Sponsors" block
    Then I should see "Title"
    And I set the field "Title" to "My sponsor"
    Then I should see "Column numbers"
    And I set the field "Column numbers" to "2 Columns"
    Then I should see "Organisation 1 name"
    Then I should see "Organisation 1 link"
    Then I should see "Organisation 1 logo"
    Given I set the field "Organisation 1 name" to "First Sponsor"
    Given I set the field "Organisation 1 link" to "http://www.sponsor1.fr"
    And I upload "blocks/sponsors/tests/fixtures/bookmark-new.png" file to "Organisation 1 logo" filemanager
    And I press "Save changes"
    Then the image at "First Sponsor" "block_sponsors > Sponsor Image" should be identical to "blocks/sponsors/tests/fixtures/bookmark-new.png"
    And I configure the "My sponsor" block
    And I delete "bookmark-new.png" from "Organisation 1 logo" filemanager
    And I upload "blocks/sponsors/tests/fixtures/document-edit.png" file to "Organisation 1 logo" filemanager
    And I press "Save changes"
    Then the image at "First Sponsor" "block_sponsors > Sponsor Image" should be identical to "blocks/sponsors/tests/fixtures/document-edit.png"

  Scenario: Adding Sponsors block and several images
    Given I log in as "admin"
    And I am on site homepage
    And I turn editing mode on
    And I add the "Sponsors" block
    And "block_sponsors" "block" should exist
    Then "Sponsors" "block" should exist
    And I configure the "Sponsors" block
    Then I should see "Title"
    And I set the field "Title" to "My sponsor"
    Then I should see "Column numbers"
    And I set the field "Column numbers" to "2 Columns"
    Then I should see "Organisation 1 name"
    Then I should see "Organisation 1 link"
    Then I should see "Organisation 1 logo"
    And I press "Add 1 more organisations"
    Given I set the field "Organisation 1 name" to "First Sponsor"
    Given I set the field "Organisation 1 link" to "http://www.sponsor1.fr"
    Given I set the field "Organisation 2 name" to "Second Sponsor"
    Given I set the field "Organisation 2 link" to "http://www.sponsor2.fr"
    # Issue here (xpath node is not visible and it should be visible)
    # Seems to be due to the fact that we have several filemanagers on the same page and the second one opened
    # does not appear to behat as visible. A workaround here is to save them in sequence.
    And I upload "blocks/sponsors/tests/fixtures/bookmark-new.png" file to "Organisation 1 logo" filemanager
    And I press "Save changes"
    Then the image at "First Sponsor" "block_sponsors > Sponsor Image" should be identical to "blocks/sponsors/tests/fixtures/bookmark-new.png"
    And I configure the "My sponsor" block
    And I upload "blocks/sponsors/tests/fixtures/document-edit.png" file to "Organisation 2 logo" filemanager
    And I press "Save changes"
    Then the image at "Second Sponsor" "block_sponsors > Sponsor Image" should be identical to "blocks/sponsors/tests/fixtures/document-edit.png"
