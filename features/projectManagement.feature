Feature: Managing project resources
  In order to allocate the ressources to sprints
  As a connected user
  I need to manage projects and teams

  Scenario: Create a project with valid data
    Given I am at url "/"
    When I click on link "New project" inside selector "nav"
    And I should be at url "/project"
    When I click the "Create project" submit button in form "form[name=project]" with data:
      | project[name] |
      | My Project    |
    Then I should be at url "/project/{UUID}"
    And I should see the flash message 'The project with name "My Project" was successfully created'

  Scenario: Create a project with name that already exists
    Given I have a project named 'My Project'
    Given I am at url "/"
    When I click on link "New project" inside selector "nav"
    And I should be at url "/project"
    When I click the "Create project" submit button in form "form[name=project]" with data:
      | project[name] |
      | My Project    |
    Then I should be at url "/project"
    And The selector 'form[name="project"]' should contains the text:
  """
The project with name My Project already exists.
  """

  Scenario: Create a project with empty name
    Given I am at url "/"
    When I click on link "New project" inside selector "nav"
    And I should be at url "/project"
    When I click the "Create project" submit button in form "form[name=project]" with data:
      | project[name] |
      |               |
    Then I should be at url "/project"
    And The selector 'form[name="project"]' should contains the text:
  """
The project name should not be blank.
  """

  Scenario: Create a project with too short name
    Given I am at url "/"
    When I click on link "New project" inside selector "nav"
    And I should be at url "/project"
    When I click the "Create project" submit button in form "form[name=project]" with data:
      | project[name] |
      | p             |
    Then I should be at url "/project"
    And The selector 'form[name="project"]' should contains the text:
  """
The project name is too short. It should have 3 characters or more.
  """

  Scenario: Click on project link in dashboard leads to project view
    Given I have a project named "Project 1"
    When I am at url "/"
    When I click on link "Project 1" inside selector "main"
    Then I should be at url "/project/project-1"
    And The selector "main" should contains the text:
  """
Project 1
  """
# todo enable
#    And The selector "main" should contains the text:
#  """
#There is no sprint created yet.
#  """

  Scenario: Click on home link leads to dashboard
    Given I have a project named "Project 1"
    When I am at url "/project/project-1"
    When I click on link "Home" inside selector "nav"
    Then I should be at url "/"
