Feature: Event program
  As an admin
  I should be able to manage event program
  In order to update my event program

Background:
  Given following "Event":
    | ref   | title    | description               | startDate  | endDate    | venue              |
    | event | My event | My another awesome event! | 2014-10-10 | 2014-10-12 | Burj Khalifa Tower |
  And following "EventTranslation":
    | locale |
    | ru_RU  |
  And following "Speaker":
    | ref   | firstName | lastName  | Company          | email | homepage           | twitter   |
    | phill | Phill     | Pilow     | Reseach Supplier |       |                    |           |
    | alex  | Alex      | Demchenko | KnpLabs          |       | http://451f.com.ua | pilouanic |
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
  And following "Program":
    | ref          | speech  | title       | isTopic | startDate        | endDate          |
    | keynote      |         | keynote     | 1       | 2014-10-10 10:00 | 2014-10-10 10:30 |
    | alex_symfony | symfony |             | 0       | 2014-10-10 10:30 | 2014-10-10 11:00 |
    | coffee1      |         | coffee      | 1       | 2014-10-10 11:00 | 2014-10-10 12:00 |
    | phil_php     | php     |             | 0       | 2014-10-10 12:00 | 2014-10-10 12:30 |
    | end_keynote  |         | keynote     | 1       | 2014-10-10 13:30 | 2014-10-10 14:00 |
    | after_party  |         | after party | 1       | 2014-10-10 14:00 | 2014-10-10 18:00 |
  And following "ProgramTranslation":
    | locale |
    | ru_RU  |

Scenario: Admin should have access to the program management
  Given I am sign in as admin
   When I follow "Schedule"
   Then I should see "Event schedule"
    And I should see "symfony propagation"
    And I should see "after party"

Scenario: Admin should able to add event topic program record
  Given I am sign in as admin
   When I follow "Schedule"
    And I follow "Add an entry"
   Then I should see "Add program entry"
   When I fill in "Title" with "Coffee Break"
    And I check "Topic"
    And I fill in "Start Time" with "10/10/2014 12:30"
    And I fill in "End Time" with "10/10/2014 13:00"
    And I press "Add"
   Then I should see "Program updated."
    And I should see "Coffee Break"
    And I should see "October 10, 2014 12:30 - 13:00"

@wip
Scenario: Admin should able to add event regular program record
  Given I am sign in as admin
   When I follow "Program"
    And I follow "Add record"
   Then I should see "Add new program record"
   When I select "doctrine must have" from "Speech"
    And I fill in "Start Date" with "2014-10-10 13:00"
    And I fill in "End Date" with "2014-10-10 13:30"
    And I press "Add"
   Then I should see "Program updated."
    And I should see "doctrine must have"
    And I should see "2014-10-10 13:30"

@wip
Scenario: Admin should able to update event program record
  Given I am sign in as admin
   When I follow "Program"
    And I edit "1" record of "Program"
   Then I should see "Edit program record"
   When I fill in "Title" with "Registration"
    And I press "Update"
   Then I should see "Program updated."
    And I should see "Registration"

@wip
Scenario: Admin should able to delete event program record
  Given I am sign in as admin
   When I follow "Program"
    And I delete "3" record of "Program"
   Then I should see "Event program record deleted."
    And I should not see "coffee"
