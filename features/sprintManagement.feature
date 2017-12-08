Feature: Manage my project sprints
  In order to know my velocity
  As a connected user
  I need to manage the sprints

  Background:
    Given I have a project named 'Project 1'

  Scenario: Creating a sprint with valid data from the dashboard
    Given I am at url '/'
    When I submit the form '#project-project-1-create_sprint' with data:
    ||
    Then I should be at url '/sprint/{UUID}'
    And I should see the flash message "The sprint was successfully created."

  Scenario: Show a pending sprint information from the dashboard
    Given The project 'project-1' has a pending sprint with id 'pending-sprint'
    And I am at url '/'
    When I click on link 'Manage Sprint' inside selector '#project-project-1'
    Then I should be at url '/sprint/pending-sprint'
    And The selector '#sprint-pending-sprint' should contains the text:
  """
Sprint 1
  """

  Scenario: Show the copyright and version of the app
    Given I am at url '/'
    Then The selector 'footer' should contains the text:
  """
2017 Yannick Voyer
  """
    And The selector 'footer' should contains the text:
  """
2.0.0-beta
  """

  Scenario: Starting a sprint from the dashboard
    Given The project 'project-1' has a pending sprint with id 'started-sprint'
    And The member 'm1' is committed to pending sprint 'started-sprint' for 10 man days
    And I am at url '/'
    When I submit the form '#sprint-started-sprint-start' with data:
      | velocity | _method |
      | 12       | PUT     |
    Then I should be at url '/sprint/started-sprint'
    And I should see the flash message "The sprint was started with a velocity of 12."
    And The selector 'main' should contains the text:
  """
Sprint 1 Started
  """

  Scenario: Commiting members to a sprint from the sprint management page
    Given The project 'project-1' has a pending sprint with id 'started-sprint'
    And The project "project-1" has a team named "Team 1"
    And The team "Team 1" has the member "Member 1"
    And I am at url '/'
    And I click on link 'Manage Sprint' inside selector '#project-project-1'
    And I am at url '/'
    When I submit the form '#sprint-started-sprint-start' with data:
      | member_id | mandays |
      | member-1  | 44      |
    Then I should be at url '/'
    And I should see the flash message "The member is now commited to the sprint for 44 man days."

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
