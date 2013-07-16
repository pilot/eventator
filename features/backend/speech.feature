Feature: Event speaker speech
  As an admin
  I should be able to manage event speaker speech
  In order to see speaker speech

Background:
  Given following "Event":
    | ref   | title    | description               | startDate  | endDate    | venue              |
    | event | My event | My another awesome event! | 2014-10-10 | 2014-10-12 | Burj Khalifa Tower |
  And following "EventTranslation":
    | locale |
    | ru_RU  |
  And following "Speaker":
    | ref   | firstName | lastName  | Organization     | email | homepage           | twitter     |
    | phill | Phill     | Pilow     | Reseach Supplier |       |                    |             |
    | alex  | Alex      | Demchenko | KnpLabs          |       | http://451f.com.ua | pilouanic   |
    | natan | Natan     | Posseo    | Seraphim         |       |                    | natanposseo |
  And following "SpeakerTranslation":
    | locale |
    | ru_RU  |
  And following "Speech":
    | ref      | speaker | title               | description                           | slide | video |
    | symfony  | alex    | symfony propagation | world symfony expansion               |       |       |
    | php      | phill   | php servers piece   | php most popular language             |       |       |
    | doctrine | phill   | doctrine must have  | what you docrtine project should have |       |       |
  And following "SpeechTranslation":
    | locale |
    | ru_RU  |

@wip
Scenario: Admin should have access to the speaker speech
  Given I am sign in as admin
   When I follow "Speeches"
   Then I should see "Event speeches"
    And I should see "symfony propagation"
    And I should see "Phill Pilow"
    And I should ses "doctrine must have"

@wip
Scenario: Admin should able to add event speaker speech
  Given I am sign in as admin
   When I follow "Speeches"
    And I follow "Add speech"
   Then I should see "Add new speech"
   When I select "Natan Posseo" from "Speaker"
    And I fill in "Title" with "Symfony for the eco Earth"
    And I fill in "Description" with "Save energy with php projects base on Symfony"
    And I press "Add"
   Then I should see "Event speaker speech updated."
    And I should see "Natan Posseo"
    And I should see "Symfony for the eco Earth"
    And I should not see "Save energy with php projects base on Symfony"

@wip
Scenario: Admin should not able to add event speaker without speaker and title
  Given I am sign in as admin
   When I follow "Speeches"
    And I follow "Add speech"
    And I fill in "Description" with "Driver project under the hood"
    And I press "Add"
   Then I should not see "Event speaker speech updated."
    And I should see "Add new speech"
    And the "Description" field should contain "Driver project under the hood"
   When I fill in "Title" with "Deriver drive with Symfony"
    And I press "Add"
   Then I should not see "Event speaker speech updated."
    And I should see "Add new speech"
    And the "Title" field should contain "Deriver drive with Symfony"

@wip
Scenario: Admin should able to update event speaker speech
  Given I am sign in as admin
   When I follow "Speeches"
    And I edit "2" record of "Speech"
   Then I should see "Edit speech"
   When I fill in "Title" with "php as de facto standard"
    And I fill in "slide" with "https://speakerdeck.com"
    And I press "Update"
   Then I should see "Event speaker speech updated."
    And I should see "php as de facto standard"
    And I should not see "speakerdeck.com"

@wip
Scenario: Admin should able to delete event speaker speech
  Given I am sign in as admin
   When I follow "Speeches"
    And I delete "3" record of "Speaker"
   Then I should see "Event speaker speech deleted."
    And I should not see "doctrine must have"
    And I should see "symfony propagation"
