Feature: Call for papers
  As an user
  I should be able to participate in conference
  In order to be a conference speaker

@wip
Scenario: User should able call for papers
  Given I am on "Homepage"
   When I follow "Call for papers"
    And I fill in "Full Name" with "Alex Demchenko"
    And I fill in "Title" with "Another awesome speech"
    And I fill in "Description" with "symfony propagation"
    And I should see "after party"
   Then I should see "Thanks for your request. You will get response asap."
