Feature: Event speakers
  As an admin
  I should be able to manage event speakers
  In order to see my event speakers

Background:
  Given following "Event":
    | ref   | title    | description               | startDate  | endDate    | venue              |
    | event | My event | My another awesome event! | 2014-10-10 | 2014-10-12 | Burj Khalifa Tower |
  And following "EventTranslation":
    | locale |
    | ru_RU  |
  And following "Speaker":
    | ref   | firstName | lastName  | Organization     | email | homepage           | twitter   |
    | phill | Phill     | Pilow     | Reseach Supplier |       |                    |           |  
    | alex  | Alex      | Demchenko | KnpLabs          |       | http://451f.com.ua | pilouanic |
  And following "SpeakerTranslation":
    | locale |
    | ru_RU  |

Scenario: Admin should have access to the speakers management
  Given I am sign in as admin
   When I follow "Speakers"
   Then I should see "Event speakers"
    And I should see "Alex Demchenko"
    And I should see "Phill Pilow"
    And I should ses "KnpLabs"

Scenario: Admin should able to add event speaker
  Given I am sign in as admin
   When I follow "Speakers"
    And I follow "Add speaker"
   Then I should see "Add new speaker"
   When I fill in "First Name" with "Natan"
    And I fill in "Last Name" with "Posseo"
    And I fill in "Organization" with "Seraphim"
    And I fill in "Twitter" with "natanposseo"
    And I press "Add"
   Then I should see "Event speaker updated."
    And I should see "Natan Posseo"
    And I should see "Seraphim"
    And I should not see "natanposseo"

Scenario: Admin should not able to add event speaker without first and last name
  Given I am sign in as admin
   When I follow "Speakers"
    And I follow "Add speaker"
    And I fill in "Last Name" with "Georgian"
    And I press "Add"
   Then I should not see "Event speaker updated."
    And I should see "Add new speaker"
    And the "Last Name" field should contain "Georgian"

Scenario: Admin should able to update event speaker
  Given I am sign in as admin
   When I follow "Speakers"
    And I edit "1" record of "Speaker"
   Then I should see "Edit speaker"
   When I fill in "Organization" with "NASSA Reseach Center"
    And I fill in "homepage" with "http://nassa.gov.us"
    And I press "Update"
   Then I should see "Event speaker updated."
    And I should see "NASSA Reseach Center"
    And I should not see "nassa.gov.us"

Scenario: Admin should able to delete event speaker
  Given I am sign in as admin
   When I follow "Speakers"
    And I delete "3" record of "Speaker"
   Then I should see "Event speaker deleted."
    And I should not see "Natan Posseo"
    And I should see "Alex Demchenko"
