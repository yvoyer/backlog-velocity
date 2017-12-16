Feature: Manage my project sprints
  In order to know my velocity
  As a connected user
  I need to manage the sprints

  Scenario: Creating a sprint with valid data from the dashboard
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And I am at url "/"
    When I submit the form "#project-project-1 form[name=create_sprint]" with data:
    | create_sprint[team] |
    | team-1              |
    Then I should be at url "/sprint/{UUID}"
    And I should see the flash message "The sprint was successfully created."

  Scenario: Show a pending sprint information from the dashboard
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And The team "team 1" has a pending sprint with id "pending-sprint" for project "project-1"
    And I am at url "/"
    When I click on link "Manage Sprint" inside selector "#project-project-1"
    Then I should be at url "/sprint/pending-sprint"
    And The selector "#sprint-pending-sprint" should contains the text:
  """
Sprint 1
  """

  Scenario: Show the copyright and version of the app
    Given I am at url "/"
    Then The selector "footer" should contains the text:
  """
2017 Yannick Voyer
  """
    And The selector "footer" should contains the text:
  """
2.0.0-beta
  """

  Scenario: Starting a sprint from the dashboard
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And The team "team 1" has a pending sprint with id "started-sprint" for project "project-1"
    And The member "m1" is committed to pending sprint "started-sprint" for 10 man days
    And I am at url "/"
    When I submit the form "#sprint-started-sprint-start" with data:
      | velocity | _method |
      | 12       | PUT     |
    Then I should be at url "/sprint/started-sprint"
    And I should see the flash message "The sprint was started with a velocity of 12."

  Scenario: Committing members to a sprint from the sprint management page
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And I have a person named "Member 1"
    And The team "Team 1" has a pending sprint with id "started-sprint" for project "project-1"
    And The team "Team 1" has the member "Member 1"
    And I am at url "/sprint/started-sprint"
    When I submit the form "#commitment-member-1 form" with data:
      | commitment[memberId] | commitment[manDays] |
      | member-1             | 44                  |
    Then I should be at url "/sprint/started-sprint"
    And I should see the flash message 'The member with id "member-1" is now commited to the sprint for 44 man days.'

  Scenario: Committing members to a sprint with invalid data
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And I have a person named "Member 1"
    And The team "Team 1" has a pending sprint with id "started-sprint" for project "project-1"
    And The team "Team 1" has the member "Member 1"
    And I am at url "/sprint/started-sprint"
    When I submit the form "#commitment-member-1 form" with data:
      | commitment[memberId] | commitment[manDays] |
      | member-1             |                     |
    Then I should be at url "/sprint/started-sprint"
    And The selector "#commitment-member-1 form" should contains the text:
  """
TODO The man days should be integer
  """

  Scenario: Ending a sprint from the dashboard
    Given The test is not implemented yet

#    Given The test is not implemented yet
#
#  Scenario: Show a ended sprint information from the dashboard
#    Given The test is not implemented yet
#
#  Scenario: Starting a sprint from the project view
#    Given The test is not implemented yet
#    # Commit members
#
#  Scenario: Ending a sprint from the project view
#    Given The test is not implemented yet
#
#  Scenario: Show a pending sprint information from the project view
#    Given The test is not implemented yet
#
#  Scenario: Show a started sprint information from the project view
#    Given The test is not implemented yet
#
#  Scenario: Show a ended sprint information from the project view
#    Given The test is not implemented yet
#
#  Scenario: Should not start sprint when no commitments exists
#    todo show no commitment message, hide start button
#    Given The test is not implemented yet
