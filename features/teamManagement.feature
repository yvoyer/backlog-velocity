Feature: Manage a team
  As a member of the application
  In order create sprint
  I need to manage my teams

  Scenario: Create a team available for all projects
    Given I am at url "/"
    When I click on link "New team" inside selector "nav"
    And I should be at url "/team"
    When I click the "Create team" submit button in form "form[name=team]" with data:
      | team[name] |
      | My team    |
    Then I should be at url "/team/{UUID}"
    And I should see the flash message 'The team with name "My team" was successfully created'

  Scenario: Create a team with name that already exists
    Given I have a team named 'My team'
    Given I am at url "/"
    When I click on link "New team" inside selector "nav"
    And I should be at url "/team"
    When I click the "Create team" submit button in form "form[name=team]" with data:
      | team[name] |
      | My team    |
    Then I should be at url "/team"
    And The selector 'form[name="team"]' should contains the text:
  """
The team with name 'My team' already exists.
  """

  Scenario: Create a team with empty name
    Given I am at url "/"
    When I click on link "New team" inside selector "nav"
    And I should be at url "/team"
    When I click the "Create team" submit button in form "form[name=team]" with data:
      | team[name] |
      |            |
    Then I should be at url "/team"
    And The selector 'form[name="team"]' should contains the text:
  """
The team name should not be blank.
  """

  Scenario: Create a team with too short name
    Given I am at url "/"
    When I click on link "New team" inside selector "nav"
    And I should be at url "/team"
    When I click the "Create team" submit button in form "form[name=team]" with data:
      | team[name] |
      | s          |
    Then I should be at url "/team"
    And The selector 'form[name="team"]' should contains the text:
  """
The team name is too short. It should have 3 characters or more.
  """

  Scenario: Show teams I created
    Given I have a team named "No members team"
    And I have a person named "Member one"
    And I have a person named "Member two"
    And I have a person named "Member three"
    And I have a team named "One member team"
    And The member "member-one" is part of team "one-member-team"
    And The member "member-two" is part of team "one-member-team"
    And I have a team named "Three members team"
    And The member "member-one" is part of team "three-members-team"
    And The member "member-two" is part of team "three-members-team"
    And The member "member-three" is part of team "three-members-team"
    Given I am at url "/"
    When I click on link "My teams" inside selector "nav"
    Then I should be at url "/my-teams"
    And The selector "#my-teams li:nth-child(2) .badge" should contains the text:
  """
0
  """
    And The selector "#my-teams li:nth-child(2) a" should contains the text:
  """
No members team
  """
    And The selector "#my-teams li:last-child .badge" should contains the text:
  """
3
  """
    And The selector "#my-teams li:last-child a" should contains the text:
  """
Three members team
  """

  Scenario: Go to team information page from the my teams page
    Given I have a team named "My team"
    And I am at url "/my-teams"
    When I click on link "My team" inside selector "#my-teams li:nth-child(2)"
    Then I should be at url "/team/my-team"
    And The selector "#team-show h1" should contains the text:
  """
My team
  """

  Scenario: Go to team details tab from the team page
    Given I have a team named "My team"
    And I am at url "/team/my-team"
    When I click on link "Details" inside selector "#team-show .nav-tabs"
    Then I should be at url "/team/my-team"
    And The selector "#team-details h2" should contains the text:
  """
Team details
  """

  Scenario: Show header in sprint list of team
    Given I have a team named "My team"
    And I have a project named "My project"
    And The team "my-team" has a pending sprint with id "my-sprint" for project "My project"
    When I am at url "/team/my-team"
    And I click on link "Sprints" inside selector "#team-show .nav-tabs"
    Then The selector "#team-sprints h2" should contains the text:
  """
Past and present sprints of team
  """
    And The selector "#team-sprints li:nth-child(2)" should contains the text:
  """
Sprint
  """
    And The selector "#team-sprints li:nth-child(2)" should contains the text:
  """
Estimated velocity
  """
    And The selector "#team-sprints li:nth-child(2)" should contains the text:
  """
Actual velocity
  """
    And The selector "#team-sprints li:nth-child(2)" should contains the text:
  """
Status
  """

  Scenario: List pending sprints of a team
    Given I have a team named "My team"
    And I have a project named "My project"
    And The team "my-team" has a pending sprint with id "my-sprint" for project "My project"
    When I am at url "/team/my-team"
    And I click on link "Sprints" inside selector "#team-show .nav-tabs"
    Then I should be at url "/team/my-team?tab=sprints"
    And The selector "#team-sprints li:nth-child(3)" should contains the text:
  """
Sprint 1
  """

  Scenario: List team members of a team
    Given I have a team named "My team"
    And I have a person named "Member one"
    And I have a person named "Member two"
    And I have a person named "Member three"
    And The member "member-one" is part of team "my-team"
    And The member "member-two" is part of team "my-team"
    And The member "member-three" is part of team "my-team"
    When I am at url "/team/my-team"
    And I click on link "Team members" inside selector "#team-show .nav-tabs"
    Then I should be at url "/team/my-team?tab=members"
    And The selector "#team-members h2" should contains the text:
  """
Members of team
  """
    And The selector "#team-members li:nth-child(2)" should contains the text:
  """
Member one
  """
    And The selector "#team-members li:nth-child(3)" should contains the text:
  """
Member three
  """
    And The selector "#team-members li:last-child" should contains the text:
  """
Member two
  """

#  Scenario: Rename a team with valid name
#    Given I have a team named "My team"
#    And I am at url "/team/my-team"
#    When I click the "Create team" submit button in form "form[name=team]" with data:
#      | team[name] |
#      | New team   |
#    Then I should be at url "/team/new-team"
#    Then The selector "main" should contains the text:
#  """
#No members team (0 members)
#  """
#
#  Scenario: Rename a team with empty name
#    Given I have a team named "My team"
#    And I am at url "/team/my-team"
#    When I click the "Create team" submit button in form "form[name=team]" with data:
#      | team[name] |
#      | New team   |
#    Then I should be at url "/team/new-team"
#    And The selector 'form[name="team"]' should contains the text:
#  """
#The team name is too short. It should have 3 characters or more.
#  """
#
#  Scenario: Rename a team with too short name
#    Given I have a team named "My team"
#    And I am at url "/team/my-team"
#    When I click the "Create team" submit button in form "form[name=team]" with data:
#      | team[name] |
#      | New team   |
#    Then I should be at url "/team/new-team"
#    And The selector 'form[name="team"]' should contains the text:
#  """
#The team name is too short. It should have 3 characters or more.
#  """
#
#  Scenario: Rename a team with already existing name
#    Given I have a team named "My team"
#    And I am at url "/team/my-team"
#    When I click the "Create team" submit button in form "form[name=team]" with data:
#      | team[name] |
#      | New team   |
#    Then I should be at url "/team/new-team"
#    And The selector 'form[name="team"]' should contains the text:
#  """
#The team name is too short. It should have 3 characters or more.
#  """
#
#  Scenario: Rename a team with same name
#    Given I have a team named "My team"
#    And I am at url "/team/my-team"
#    When I click the "Create team" submit button in form "form[name=team]" with data:
#      | team[name] |
#      | New team   |
#    Then I should be at url "/team/new-team"
#    And The selector 'form[name="team"]' should contains the text:
#  """
#The team name is too short. It should have 3 characters or more.
#  """

  # todo Scenario: Show members of teams I own
  # todo Scenario: Show teams I am part of
  # todo Scenario: Remove another member from my team
  # todo Scenario: Remove another member from a team I do not own
