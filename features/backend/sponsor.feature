Feature: Event sponsor
  As an admin
  I should be able to manage event sponsors
  In order to see that my event sponsors is happy

Background:
  Given following "Event":
    | ref   | title    | description               | startDate  | endDate    | venue              | email               |
    | event | My event | My another awesome event! | 2014-10-10 | 2014-10-12 | Burj Khalifa Tower | eventator@email.com |
  And following "EventTranslation":
    | locale |
    | ru_RU  |
  And following "Sponsor":
    | ref   | company          | description             | homepage            | type |
    | nasa  | Reseach Supplier | NASA research center    | http://nasa.gov.us  | 1    |
    | alex  | KnpLabs          | Happy awesome developer | http://knplabs.com  | 2    |
  And following "SponsorTranslation":
    | locale |
    | ru_RU  |

Scenario: Admin should have access to the sponsors management
  Given I am sign in as admin
   When I follow "Sponsors"
   Then I should see "Event sponsors"
    And I should see "Reseach Supplier"
    And I should see "Gold"
    And I should see "KnpLabs"

Scenario: Admin should able to add event sponsor
  Given I am sign in as admin
   When I follow "Sponsors"
    And I follow "Add sponsor"
   Then I should see "Add sponsor"
   When I fill in "Company" with "Magento"
    And I fill in "Description" with "e-commerce company"
    And I fill in "Homepage" with "https://www.magentocommerce.com"
    And I select "Silver" from "Type"
    And I press "Add"
   Then I should see "Sponsor Magento updated."
    And I should see "Reseach Supplier"
    And I should see "Magento"
    And I should see "Silver"
    And I should not see "e-commerce company"

Scenario: Admin should not able to add event sponsor without company name
  Given I am sign in as admin
   When I follow "Sponsors"
    And I follow "Add sponsor"
    And I fill in "Description" with "No name company"
    And I press "Add"
   Then I should not see "Reseach Supplier"
    And I should see "Add sponsor"
    And the "Description" field should contain "No name company"

Scenario: Admin should able to update event sponsor
  Given I am sign in as admin
   When I follow "Sponsors"
    And I follow "Reseach Supplier"
   Then I should see "Edit sponsor"
   When I fill in "Company" with "Global Reseach Center"
    And I fill in "Homepage" with "http://451f.com.ua"
    And I press "Update"
   Then I should see "Sponsor Global Reseach Center updated."
    And I should see "KnpLabs"
    And I should not see "NASA research center"

Scenario: Admin should able to delete event sponsor
  Given I am sign in as admin
   When I follow "Sponsors"
    And I delete "1" record of "Sponsor"
   Then I should see "Sponsor deleted."
    And I should not see "Reseach Supplier"
    And I should see "KnpLabs"
