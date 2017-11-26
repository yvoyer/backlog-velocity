Feature: Manage my project sprints
  In order to know my velocity
  As a connected user
  I need to manage the sprints

  Background:
    Given I have a project named 'Project 1'


  Scenario: Creating a sprint with valid data
    Given I am at url '/'
    When I click on link 'Create Sprint' inside selector '#project-project-1'
    And I submit the form '#sprint-create' with data:
    | name           |
    | Pending sprint |
    Then I should be at url '/sprint/pending-sprint'
    And I should see the message "Sprint 'Pending sprint' was created successfully."

#  Scenario: Starting a sprint from the dashboard
#    Given The test is not implemented yet
#    # Commit members
#
#  Scenario: Ending a sprint from the dashboard
#    Given The test is not implemented yet
#
#  Scenario: Show a pending sprint information from the dashboard
#    Given The test is not implemented yet
#
#  Scenario: Show a started sprint information from the dashboard
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
