Feature:
  As a console user
  I want to add a new team to the system
  So that I can assign members and sprints.

  Scenario: Create a valid team
    Given I have 0 team in the repository
    And I enter 'Team-name' as the team name
    When I launch the command 'b:t:a'
    Then I should see the message 'Team created'
    And I should have 1 team in the repository

#  Scenario: Create an existing team
#    Given I have the following teams in the repository | name | | Team-name |
#    When I launch the command 'b:t:a'
#    And I enter 'Team-name' as the team's name
#    Then I should see the message 'Team already exists'
#    And I should have 1 team in the repository
#
#  Scenario: Create an invalid team
#    Given I have 0 team in the repository
#    When I launch the command 'b:t:a'
#    And I enter '' as the team's name
#    Then I should see the message 'Team name is invalid'
#    And I should have 0 team in the repository
