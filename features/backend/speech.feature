Feature: Event speaker speech
  As an admin
  I should be able to manage event speaker speech
  In order to see speaker speech

Background:
  Given following "Event":
    | ref    | title     | description                | startDate  | endDate    | venue              | email               | host              |
    | event  | My event  | My another awesome event!  | 2014-10-10 | 2014-10-12 | Burj Khalifa Tower | eventator@email.com | http://event.com  |
    | event2 | His event | His another awesome event! | 2014-11-15 | 2014-11-16 | Kuala-lumpur Tower | eventator@gmail.com | http://event2.com |
  And following "EventTranslation":
    | locale |
    | ru_RU  |
  And following "Speaker":
    | ref   | firstName | lastName  | Company          | email | homepage           | twitter     |
    | phill | Phill     | Pilow     | Reseach Supplier |       |                    |             |
    | alex  | Alex      | Demchenko | KnpLabs          |       | http://451f.com.ua | pilouanic   |
    | natan | Natan     | Posseo    | Seraphim         |       |                    | natanposseo |
  And following "SpeakerTranslation":
    | locale |
    | ru_RU  |
  And following "Speech":
    | ref      | speaker | title               | description                           | slide | video | language |
    | symfony  | alex    | symfony propagation | world symfony expansion               |       |       | ru       |
    | php      | phill   | php servers piece   | php most popular language             |       |       | en       |
    | doctrine | phill   | doctrine must have  | what you docrtine project should have |       |       | en       |
  And following "SpeechTranslation":
    | locale |
    | ru_RU  |

Scenario: Admin should have access to the speaker speech
  Given I am sign in as admin
   When I follow "Speeches"
   Then I should see "Event speeches"
    And I should see "symfony propagation"
    And I should see "Phill Pilow"
    And I should see "doctrine must have"

Scenario: Admin should able to add event speaker speech
  Given I am sign in as admin
   When I follow "Speeches"
    And I follow "Add speech"
   Then I should see "Add speech"
   When I select "Natan Posseo" from "Speaker"
    And I fill in "Title" with "Symfony for the eco Earth"
    And I fill in "Description" with "Save energy with php projects base on Symfony"
    And I select "English" from "Speech language"
    And I press "Add"
   Then I should see "Speech Symfony for the eco Earth updated."
    And I should see "Natan Posseo"
    And I should see "en / Symfony for the eco Earth"
    And I should not see "Save energy with php projects base on Symfony"

Scenario: Admin should not able to add event speaker without speaker and title
  Given I am sign in as admin
   When I follow "Speeches"
    And I follow "Add speech"
    And I fill in "Description" with "Driver project under the hood"
    And I press "Add"
   Then I should see "Add speech"
    And the "Description" field should contain "Driver project under the hood"
   When I fill in "Title" with "Deriver drive with Symfony"
    And I press "Add"
   Then I should not see "Speech Deriver drive with Symfony updated."
    And I should see "Add speech"
    And the "Title" field should contain "Deriver drive with Symfony"

Scenario: Admin should able to update event speaker speech
  Given I am sign in as admin
   When I follow "Speeches"
    And I follow "php servers piece"
   Then I should see "Edit speech"
   When I fill in "Title" with "php as de facto standard"
    And I fill in "Slide" with "https://speakerdeck.com"
    And I press "Update"
   Then I should see "Speech php as de facto standard updated."
    And I should see "php as de facto standard"
    And I should not see "speakerdeck.com"

Scenario: Admin should able to delete event speaker speech
  Given I am sign in as admin
   When I follow "Speeches"
    And I delete "3" record of "Speech"
   Then I should see "Speech deleted."
    And I should not see "doctrine must have"
    And I should see "symfony propagation"
