Feature: Manage my project sprints
  In order to know my velocity
  As a connected user
  I need to manage the sprints

  Scenario: Creating a sprint with valid data from the dashboard
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And I am at url "/"
    When I click the "Create sprint" submit button in form "#project-project-1 form[name=create_sprint]" with data:
    | create_sprint[team] |
    | team-1              |
    Then I should be at url "/sprint/{UUID}"
    And I should see the flash message "The sprint was successfully created."

  Scenario: Show a pending sprint information from the dashboard
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And The team "team 1" has a pending sprint with id "pending-sprint" for project "project-1"
    And I am at url "/"
    When I click on link "Sprint 1" inside selector "#project-project-1"
    Then I should be at url "/sprint/pending-sprint"
    And The selector "#sprint-pending-sprint" should contains the text:
  """
Sprint 1
  """

  Scenario: Show the copyright and version of the app
    Given I am at url "/"
    Then The selector "footer" should contains the text:
  """
Yannick Voyer
  """
    And The selector "footer" should contains the text:
  """
2.0.0-rc1
  """

  Scenario: Starting a sprint from the dashboard
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And I have a person named "Member 1"
    And The member "member-1" is part of team "team-1"
    And The team "team 1" has a pending sprint with id "started-sprint" for project "project-1"
    And The member "member-1" is committed to pending sprint "started-sprint" for 10 man days
    And I am at url "/"
    When I click the "Start sprint" submit button in form "#project-project-1 form" with data:
      | start_sprint[velocity] | _method |
      | 12                     | PUT     |
    Then I should be at url "/sprint/started-sprint"
    And I should see the flash message "The sprint was started with a velocity of 12."

  Scenario: Starting a sprint without velocity
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And I have a person named "Member 1"
    And The member "member-1" is part of team "team-1"
    And The team "team 1" has a pending sprint with id "started-sprint" for project "project-1"
    And The member "member-1" is committed to pending sprint "started-sprint" for 10 man days
    And I am at url "/"
    When I click the "Start sprint" submit button in form "#project-project-1 form" with data:
      | start_sprint[velocity] | _method |
      |                        | PUT     |
    Then I should be at url "/sprint/started-sprint"
    And I should see the flash message "The estimated velocity should not be blank."

  Scenario: Starting a sprint with velocity lower than 1
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And I have a person named "Member 1"
    And The member "member-1" is part of team "team-1"
    And The team "team 1" has a pending sprint with id "started-sprint" for project "project-1"
    And The member "member-1" is committed to pending sprint "started-sprint" for 10 man days
    And I am at url "/"
    When I click the "Start sprint" submit button in form "#project-project-1 form" with data:
      | start_sprint[velocity] | _method |
      | 0                      | PUT     |
    Then I should be at url "/sprint/started-sprint"
    And I should see the flash message "The estimated velocity should be greater than 0."

  Scenario: Starting a sprint without commitments should not be possible from dashboard
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And The team "team 1" has a pending sprint with id "started-sprint" for project "project-1"
    When I am at url "/"
    Then The selector "#project-project-1" should not contains the text "Start sprint"

  Scenario: Starting a sprint without commitments should not be possible from sprint view
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And The team "team 1" has a pending sprint with id "started-sprint" for project "project-1"
    When I am at url "/sprint/started-sprint"
    Then The selector "#sprint-started-sprint" should not contains the text "Start sprint"

  Scenario: Starting a sprint from the sprint view
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And I have a person named "Member 1"
    And The team "team 1" has a pending sprint with id "started-sprint" for project "project-1"
    And The member "member-1" is committed to pending sprint "started-sprint" for 10 man days
    And I am at url "/sprint/started-sprint"
    When I click the "Start sprint" submit button in form "#sprint-started-sprint form" with data:
      | start_sprint[velocity] | _method |
      | 43                     | PUT     |
    Then I should be at url "/sprint/started-sprint"
    And I should see the flash message "The sprint was started with a velocity of 43."

  Scenario: Committing members to a sprint from the sprint management page
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And I have a person named "Member 1"
    And The team "Team 1" has a pending sprint with id "started-sprint" for project "project-1"
    And The team "Team 1" has the member "Member 1"
    And I am at url "/sprint/started-sprint"
    When I click the "Commit" submit button in form "#commitment-member-1 form" with data:
      | commitment[memberId] | commitment[manDays] |
      | member-1             | 44                  |
    Then I should be at url "/sprint/started-sprint"
    And I should see the flash message 'The member with id "member-1" is now commited to the sprint for 44 man days.'
    And The selector "li.focus-factor" should contains the text:
  """
70%
  """

  Scenario: Committing members to a sprint with invalid data
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And I have a person named "Member 1"
    And The team "Team 1" has a pending sprint with id "started-sprint" for project "project-1"
    And The team "Team 1" has the member "Member 1"
    And I am at url "/sprint/started-sprint"
    When I click the "Commit" submit button in form "#commitment-member-1 form" with data:
      | commitment[memberId] | commitment[manDays] |
      | member-1             |                     |
    Then I should be at url "/sprint/started-sprint"
    And I should see the flash message "The commitment's man days should not be blank."
    When I click the "Commit" submit button in form "#commitment-member-1 form" with data:
      | commitment[memberId] | commitment[manDays] |
      | member-1             | 0                   |
    Then I should be at url "/sprint/started-sprint"
    And I should see the flash message "The commitment's man days should be greater than 0."

  Scenario: Ending a sprint from the dashboard
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And I have a person named "Member 1"
    And The member "member-1" is part of team "team-1"
    And The team "team 1" has a pending sprint with id "ending-sprint" for project "project-1"
    And The member "member-1" is committed to pending sprint "ending-sprint" for 10 man days
    And The sprint "ending-sprint" is started with an estimated velocity of 15
    And I am at url "/"
    When I click the "Close sprint" submit button in form "#project-project-1 form" with data:
      | close_sprint[velocity] | _method |
      | 20                     | PATCH   |
    Then I should be at url "/sprint/ending-sprint"
    And I should see the flash message "The sprint was ended with a velocity of 20."
    And The selector "li.focus-factor" should contains the text:
  """
133%
  """

  Scenario: Ending a sprint from the dashboard with no value
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And I have a person named "Member 1"
    And The member "member-1" is part of team "team-1"
    And The team "team 1" has a pending sprint with id "ending-sprint" for project "project-1"
    And The member "member-1" is committed to pending sprint "ending-sprint" for 10 man days
    And The sprint "ending-sprint" is started with an estimated velocity of 5
    And I am at url "/"
    When I click the "Close sprint" submit button in form "#project-project-1 form" with data:
      | close_sprint[velocity] | _method |
      |                        | PATCH   |
    Then I should be at url "/sprint/ending-sprint"
    And I should see the flash message "The actual velocity should not be blank."

  Scenario: Ending a sprint from the dashboard with a value of 0
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And I have a person named "Member 1"
    And The member "member-1" is part of team "team-1"
    And The team "team 1" has a pending sprint with id "ending-sprint" for project "project-1"
    And The member "member-1" is committed to pending sprint "ending-sprint" for 10 man days
    And The sprint "ending-sprint" is started with an estimated velocity of 5
    And I am at url "/"
    When I click the "Close sprint" submit button in form "#project-project-1 form" with data:
      | close_sprint[velocity] | _method |
      | 0                      | PATCH   |
    Then I should be at url "/sprint/ending-sprint"
    And I should see the flash message "The actual velocity should be greater than 0."

  Scenario: Ending a sprint from the sprint view
    Given I have a project named "Project 1"
    And I have a team named "Team 1"
    And I have a person named "Member 1"
    And The member "member-1" is part of team "team-1"
    And The team "team 1" has a pending sprint with id "ending-sprint" for project "project-1"
    And The member "member-1" is committed to pending sprint "ending-sprint" for 10 man days
    And The sprint "ending-sprint" is started with an estimated velocity of 5
    And I am at url "/sprint/ending-sprint"
    When I click the "Close sprint" submit button in form "#sprint-ending-sprint form" with data:
      | close_sprint[velocity] | _method |
      | 10                     | PATCH   |
    Then I should be at url "/sprint/ending-sprint"
    And I should see the flash message "The sprint was ended with a velocity of 10."
    And The selector ".focus-factor" should contains the text:
  """
200%
  """

  Scenario: Show the sprint members from the sprint view
    Given I have a project named "Project 1"
    And I have a team named "Team id"
    And I have a person named "Member 1"
    And I have a person named "Member 2"
    And The member "member-1" is part of team "team-id"
    And The member "member-2" is part of team "team-id"
    And The team "Team id" has a pending sprint with id "sprint-id" for project "project-1"
    And I am at url "/sprint/sprint-id"
    When I click on link "2" inside selector "#sprint-sprint-id .members-count"
    Then I should be at url "/team/team-id?tab=members"
