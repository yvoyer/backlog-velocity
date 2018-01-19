Feature: Calculate the estimated velocity
  As a system user
  I want to see the estimated velocity for my new sprint
  So that I can plan the stories to add in it.

Background:
  Given The project 'Capture Luke' is created
  Given The project 'Conquer planet' is created
  Given The following persons are registered
    | name              |
    | TK-421            |
    | Darth Vader       |
    | Darth Sidious     |
    | Grand Moff Tarkin |
    | Luke Skywalker    |
    | Han Solo          |
    | Leia Skywalker    |
    | Jabba The Hutt    |
    | Boba Fett         |
    | Lando Calrisian   |
  And The following teams are registered
    | name                |
    | The Empire          |
    | The Rebel Alliance  |
    | The Siths           |
    | The Crime Syndicate |
  And The following users are part of team "The Empire"
    | name              |
    | TK-421            |
    | Darth Vader       |
    | Darth Sidious     |
    | Grand Moff Tarkin |

Scenario: Project has no previous sprints
  Given The sprint of team "The Empire" with name "Find Luke and Leia in the Death Star" is created in the "Capture-Luke" project
  And The user "TK-421" is committed to the sprint "Find Luke and Leia in the Death Star" of project "Capture-Luke" with 15 man days
  And The user "Darth Vader" is committed to the sprint "Find Luke and Leia in the Death Star" of project "Capture-Luke" with 13 man days
  And The user "Darth Sidious" is committed to the sprint "Find Luke and Leia in the Death Star" of project "Capture-Luke" with 15 man days
  And The user "Grand Moff Tarkin" is committed to the sprint "Find Luke and Leia in the Death Star" of project "Capture-Luke" with 7 man days
  When The sprint "Find Luke and Leia in the Death Star" of project "Capture-Luke" is started with an estimated velocity of 0 story points
  Then The sprint "Find Luke and Leia in the Death Star" of project "Capture-Luke" should have an estimated velocity of 35 story points

Scenario: The Team has closed previous sprint
  Given The sprint of team "The Empire" with name "Sprint 1" is created in the "Conquer planet" project
  And The sprint of team "The Empire" with name "Sprint 2" is created in the "Conquer planet" project
  And The sprint of team "The Empire" with name "Sprint 3" is created in the "Conquer planet" project
  And The sprint of team "The Empire" with name "Sprint 4" is created in the "Conquer planet" project
  # Sprint 1: 20 / 35 = .57
  And The sprint "Sprint 1" of project "Conquer planet" is closed with a total of 50 man days, an estimate of 35 SP, actual of 20 SP, focus of 57
  # Sprint 2: 40 / 20 = 2
  And The sprint "Sprint 2" of project "Conquer planet" is closed with a total of 50 man days, an estimate of 20 SP, actual of 40 SP, focus of 200
  # Sprint 3: 25 / 40 = .63
  And The sprint "Sprint 3" of project "Conquer planet" is closed with a total of 50 man days, an estimate of 40 SP, actual of 25 SP, focus of 62
  #  | name     | actual-focus | past-focus-avg          |
  #  | Sprint 1 | 57           | avg(.57) = .57          |
  #  | Sprint 2 | 200          | avg(.57, 2) = 1.57      |
  #  | Sprint 3 | 63           | avg(.57, 2, .62) = 1.06 |
  When The user "TK-421" is committed to the started sprint "Sprint 4" of project "Conquer planet" with 50 man days
  Then The sprint "Sprint 4" of project "Conquer planet" should have an estimated velocity of 53 story points
