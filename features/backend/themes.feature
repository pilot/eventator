@backend
Feature: Theme settings
    In order to update themes settings
    As an admin
    I should be able to edit themes settings

Background:
    Given following "Event":
        | ref    | title          | description                | startDate        | endDate          | venue              | email               | host                      |
        | event  | My event       | My another awesome event!  | 2016-03-01 10:00 | 2016-03-01 18:00 | Burj Khalifa Tower | eventator@email.com | http://localhost:8000     |
        | event2 | My other event | My other awesome event!    | 2016-03-01 10:00 | 2016-03-01 18:00 | Burj Khalifa Tower | eventator@gmail.com | http://eventator.loc:8080 |
        | event3 | His event      | His another awesome event! | 2016-04-01 10:00 | 2016-04-01 18:00 | Kuala-lumpur Tower | eventator@gmail.com | http://event.com          |
        | event4 | Local event    | My local awesome event!    | 2017-14-11 12:26 | 2017-14-11 12:27 | Kuala-lumpur Tower | eventator@gmail.com | http://eventator.loc      |
    And following "EventTranslation":
        | event  | locale |
        | event  | ru_RU  |
        | event  | de_DE  |
        | event2 | ru_RU  |
        | event2 | de_DE  |
        | event3 | ru_RU  |
        | event4 | ru_RU  |
    And following "Organizer":
        | ref        | title         | description                    | isActive | events               |
        | organizer  | My organizer  | My another awesome organizer!  | 1        | event,event2,event3  |
        | organizer2 | His organizer | His another awesome organizer! | 1        | event2,event3,event4 |
    And following "OrganizerTranslation":
        | organizer   | locale |
        | organizer   | ru_RU  |
        | organizer2  | de_DE  |
    And following "Speech":
        | ref     | title                | description                            | language | event        |
        | speech  | symfony propagation  | world symfony expansion                | ru       | event        |
        | speech2 | php servers piece    | php most popular language              | en       | event        |
        | speech3 | doctrine must have   | what you docrtine project should have  | en       | event        |
        | speech4 | symfony propagation2 | world symfony expansion2               | en       | event2       |
        | speech5 | php servers piece2   | php most popular language2             | en       | event2       |
        | speech6 | doctrine must have2  | what you docrtine project should have2 | en       | event2       |
        | speech7 | symfony propagation2 | world symfony expansion2               | en       | event3       |
        | speech8 | php servers piece2   | php most popular language2             | en       | event3       |
        | speech9 | doctrine must have2  | what you docrtine project should have2 | en       | event3       |
        | speech0 | doctrine must have2  | what you docrtine project should have2 | en       | event4       |

    And following "SpeechTranslation":
        | speech   | locale |
        | speech   | ru_RU  |
        | speech2  | de_DE  |
        | speech3  | de_DE  |
        | speech4  | ru_RU  |
        | speech5  | de_DE  |
        | speech6  | de_DE  |
        | speech7  | ru_RU  |
        | speech8  | de_DE  |
        | speech9  | de_DE  |
        | speech0  | de_DE  |
    And following "Program":
        | ref             | title               | isTopic | isActive | startDate         | endDate           | events       | speech  |
        | program         | keynote             | 1       | 1        | 2016-03-01 10:00  | 2016-03-01 10:30  | event        |         |
        | program2        | alex_symfony        | 0       | 1        | 2016-03-01 10:30  | 2016-03-01 11:30  | event        | speech  |
        | program3        | phil_php            | 0       | 1        | 2016-03-01 11:30  | 2016-03-01 12:30  | event        | speech2 |
        | program4        | coffee1             | 1       | 1        | 2016-03-01 12:30  | 2016-03-01 13:00  | event        |         |
        | program5        | phil_doctrine       | 0       | 1        | 2016-03-01 13:00  | 2016-03-01 14:30  | event        | speech3 |
        | program6        | end_keynote         | 1       | 1        | 2016-03-01 14:30  | 2016-03-01 15:00  | event        |         |
        | program7        | after_party         | 1       | 1        | 2016-03-01 15:00  | 2016-03-01 18:00  | event        |         |
        | program8        | keynote             | 1       | 1        | 2016-04-01 10:00  | 2016-04-01 10:30  | event2       |         |
        | program9        | alex_symfony        | 0       | 1        | 2016-04-01 10:30  | 2016-04-01 11:30  | event2       | speech4 |
        | program10       | phil_php            | 0       | 1        | 2016-04-01 11:30  | 2016-04-01 12:30  | event2       | speech5 |
        | program11       | coffee1             | 1       | 1        | 2016-04-01 12:30  | 2016-04-01 13:00  | event2       |         |
        | program12       | phil_doctrine       | 0       | 1        | 2016-04-01 13:00  | 2016-04-01 14:30  | event2       | speech6 |
        | program13       | end_keynote         | 1       | 1        | 2016-04-01 14:30  | 2016-04-01 15:00  | event2       |         |
        | program14       | after_party         | 1       | 1        | 2016-04-01 15:00  | 2016-04-01 18:00  | event2       |         |
        | program15       | keynote             | 1       | 1        | 2016-04-01 10:00  | 2016-04-01 10:30  | event3       |         |
        | program16       | alex_symfony        | 0       | 1        | 2016-04-01 10:30  | 2016-04-01 11:30  | event3       | speech7 |
        | program17       | phil_php            | 0       | 1        | 2016-04-01 11:30  | 2016-04-01 12:30  | event3       | speech8 |
        | program18       | coffee1             | 1       | 1        | 2016-04-01 12:30  | 2016-04-01 13:00  | event3       |         |
        | program19       | phil_doctrine       | 0       | 1        | 2016-04-01 13:00  | 2016-04-01 14:30  | event3       | speech9 |
        | program20       | end_keynote         | 1       | 1        | 2016-04-01 14:30  | 2016-04-01 15:00  | event3       |         |
        | program21       | after_party         | 1       | 1        | 2016-04-01 15:00  | 2016-04-01 18:00  | event3       |         |
        | program22       | after_party         | 1       | 1        | 2016-04-01 15:00  | 2016-04-01 18:00  | event4       |         |
    And following "ProgramTranslation":
        | program   | locale |
        | program   | ru_RU  |
        | program2  | de_DE  |
        | program3  | de_DE  |
        | program4  | de_DE  |
        | program5  | de_DE  |
        | program6  | de_DE  |
        | program7  | de_DE  |
        | program8  | de_DE  |
        | program9  | de_DE  |
        | program10 | de_DE  |
        | program11 | de_DE  |
        | program12 | de_DE  |
        | program13 | de_DE  |
        | program14 | de_DE  |
        | program15 | de_DE  |
        | program16 | de_DE  |
        | program17 | de_DE  |
        | program18 | de_DE  |
        | program19 | de_DE  |
        | program20 | de_DE  |
        | program21 | de_DE  |
        | program22 | de_DE  |
    And following "Speaker":
        | ref      | firstName | lastName  | Company          | email | homepage           | twitter                     | events                     | speeches                                        |
        | speaker  | Phill     | Pilow     | Reseach Supplier |       |                    |                             | event,event2,event3        | speech2,speech3,speech5,speech6,speech8,speech9 |
        | speaker2 | Alex      | Demchenko | KnpLabs          |       | http://451f.com.ua | https://twitter.com/twitter | event,event2,event3,event4 | speech,speech4,speech7,speech0                  |
    And following "SpeakerTranslation":
        | speaker   | locale |
        | speaker   | ru_RU  |
        | speaker2  | de_DE  |
    And following "Sponsor":
        | ref      | company          | description             | homepage            | type | isActive | events              |
        | sponsor  | Reseach Supplier | NASA research center    | http://nasa.gov.us  | 1    | 1        | event,event2,event3 |
        | sponsor2 | KnpLabs          | Happy awesome developer | http://knplabs.com  | 2    | 1        | event,event4        |
    And following "SponsorTranslation":
        | sponsor   | locale |
        | sponsor   | de_DE  |
        | sponsor2  | de_DE  |
    And following "Theme":
        | title  | is_active |
        | test   | 1         |
        | test2  | 1         |

@javascript
Scenario: Admin should have access to the theme manage
    Given I am sign in as admin
    When I click "Themes"
    Then I wait for a form
    Then I should see "Add Theme"
    And I should see the row containing "1;test"
    And I should see the row containing "2;test1"
    When I click "Edit" on the row containing "1;test"
    Then I wait for a form
    Then I should see "test settings"

@javascript
Scenario: Admin should be able to add theme
    Given I am sign in as admin
    When I click "Themes"
    Then I wait for a form
    Then I should see "Add Theme"
    And I click "Add Theme"
    Then I wait for a form
    Then I should see "New theme settings"
    And I fill in "Title" with "Test theme"
    And I press "Upload file"
    Then I wait "10" seconds
    Then I should see " My Device "
    And I attach the file "test.css" to "fsp-fileUpload"
#    Then I wait "15" seconds
#    Then I should see "Edit Image"
    Then I wait "15" seconds
    And I click on the element with css selector ".fsp-button--primary"
    Then I wait "10" seconds
    And I press "Add"
    Then I wait for a form
    Then I should see "Theme Test theme added."
    Then I should see the row containing "3;Test theme"

@javascript
Scenario: Admin should be able to update theme settings
    Given I am sign in as admin
    When I click "Themes"
    Then I wait for a form
    Then I should see "Add Theme"
    And I should see the row containing "1;test"
    When I click "Edit" on the row containing "1;test"
    Then I wait for a form
    Then I should see "test settings"
    And I fill in "Title" with "testTest"
    And I press "Update"
    Then I wait for a form
    Then I should see "Theme testTest updated."
    Then I should see the row containing "1;testTest"

@javascript
Scenario: Admin should be able to delete the theme
    Given I am sign in as admin
    When I click "Themes"
    Then I wait for a form
    Then I should see "Add Theme"
    And I should see the row containing "1;testTest"
    And I should see the row containing "2;test2"
#    And I should see the row containing "3;Test theme"
    Then I delete the record with id "2"
    Then I wait for a form
    Then I should see "Theme deleted."
    Then I should not see the row containing "3;Test theme"

