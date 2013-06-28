Feature: Event settings
  As an admin
  I should be able to edit event settings
  In order to update my event description

Background:
  Given following "Event":
    | ref   | title    | description               | startDate  | endDate    | venue              |
    | event | My event | My another awesome event! | 2014-10-10 | 2014-10-12 | Burj Khalifa Tower |
  And following "EventTranslation":
    | locale |
    | ru_RU  |

Scenario: Admin should have access to the settings manage
  Given I am sign in as admin
   When I follow "Settings"
   Then I should see "Event settings"

Scenario: Admin should have able to update event settings
  Given I am sign in as admin
   When I follow "Settings"
    And I fill in "Brief Description" with "Awesome event"
    And I select "United Arab Emirates" from "Country"
    And I fill in "State" with "Dubai"
    And I fill in "City" with "Dubai"
    And I fill in "Twitter" with "myevent"
    And I press "Update"
   Then I should see "Event settings updated."
    And I should see "Awesome event"
    And the "City" field should contain "Dubai"

# @javascript
# Scenario: Admin should have able to update Russian event settings
#   Given I am sign in as admin
#    When I follow "Settings"
#     And I follow "ru"
#     And I fill in "title" with "Мое событие"
#     And I press "Update"
#    Then I should see "Event settings updated."
#    When I follow "ru"
#    Then the "title" field should contain "Мое событие"
