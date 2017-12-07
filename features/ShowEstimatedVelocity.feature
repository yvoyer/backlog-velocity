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
  And The following teams are registered for project 'Capture Luke'
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
  Given The sprint "Find Luke and Leia in the Death Star" is created in the "Capture-Luke" project
  And The user "TK-421" is committed to the sprint "Find Luke and Leia in the Death Star" of project "Capture-Luke" with 15 man days
  And The user "Darth Vader" is committed to the sprint "Find Luke and Leia in the Death Star" of project "Capture-Luke" with 13 man days
  And The user "Darth Sidious" is committed to the sprint "Find Luke and Leia in the Death Star" of project "Capture-Luke" with 15 man days
  And The user "Grand Moff Tarkin" is committed to the sprint "Find Luke and Leia in the Death Star" of project "Capture-Luke" with 7 man days
  When The sprint "Find Luke and Leia in the Death Star" of project "Capture-Luke" is started with an estimated velocity of 0 story points
  Then The sprint "Find Luke and Leia in the Death Star" of project "Capture-Luke" should have an estimated velocity of 35 story points

Scenario: The Team has closed previous sprint
  Given The sprint "Sprint 1" is created in the "Conquer planet" project
  And The sprint "Sprint 2" is created in the "Conquer planet" project
  And The sprint "Sprint 3" is created in the "Conquer planet" project
  And The sprint "Sprint 4" is created in the "Conquer planet" project
  # Sprint 1: 20 / 50 = .4
  And The sprint "Sprint 1" of project "Conquer planet" is closed with a total of 50 man days, an estimate of 35 SP, actual of 20 SP, focus of 40
  # Sprint 2: 40 / 50 = .8
  And The sprint "Sprint 2" of project "Conquer planet" is closed with a total of 50 man days, an estimate of 20 SP, actual of 40 SP, focus of 80
  # Sprint 3: 25 / 50 = .5
  And The sprint "Sprint 3" of project "Conquer planet" is closed with a total of 50 man days, an estimate of 40 SP, actual of 25 SP, focus of 50
  #  | name     | actual-focus | past-focus-avg        |
  #  | Sprint 1 | 20 / 50 = .4 | avg(.4) = .4          |
  #  | Sprint 2 | 40 / 50 = .8 | avg(.4, .8) = .6      |
  #  | Sprint 3 | 25 / 50 = .5 | avg(.4, .8, .5) = .57 |
  When The user "TK-421" is committed to the started sprint "Sprint 4" of project "Conquer planet" with 50 man days
  Then The sprint "Sprint 4" of project "Conquer planet" should have an estimated velocity of 28 story points
