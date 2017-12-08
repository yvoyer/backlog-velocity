Feature: Managing project resources
  In order to allocate the ressources to sprints
  As a connected user
  I need to manage projects and teams

  Scenario: Create a project with valid data
    Given I am at url "/"
    When I click on link "New project" inside selector "nav"
    And I should be at url "/project"
    And I submit the form "form[name=project]" with data:
      | project[name] |
      | My Project    |
    Then I should be at url "/project/{UUID}"
    And I should see the flash message 'The project with name "My Project" was successfully created'

  Scenario: Create a project with name that already exists
    Given I have a project named 'My Project'
    Given I am at url "/"
    When I click on link "New project" inside selector "nav"
    And I should be at url "/project"
    And I submit the form "form[name=project]" with data:
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
    And I submit the form "form[name=project]" with data:
      | project[name] |
      |               |
    Then I should be at url "/project"
    And The selector 'form[name="project"]' should contains the text:
  """
The project name should not be blank.
  """


