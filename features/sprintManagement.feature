Feature: Manage my project sprints
  In order to know my velocity
  As a connected user
  I need to manage the sprints

  Background:
    Given I have a project named 'Project 1'

  Scenario: Creating a sprint with valid data
    Given I am at url '/'
    When I submit the form '#project-project-1-create_sprint' with data:
    ||
    Then I should be at url '/sprint/{UUID}'
    And I should see the flash message "The sprint was successfully created."

#  Scenario: Starting a sprint from the dashboard
#    Given The test is not implemented yet
#    When I click on link 'Create sprint' inside selector '#project-project-1'
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
