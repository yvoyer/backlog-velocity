Feature: Calculate the estimated velocity
  As a system user
  I want to see the estimated velocity for my new sprint
  So that I can plan the stories to add in it.

Background:
  Given The project 'Capture Luke' is created
  Given The following persons are registered
    | name |
    | TK-421 |
    | Darth Vader |
    | Darth Sidious |
    | Grand Moff Tarkin |
    | Luke Skywalker |
    | Han Solo |
    | Leia Skywalker |
    | Jabba The Hutt |
    | Boba Fett |
    | Lando Calrisian |
  And The following teams are registered
    | name |
    | The Empire |
    | The Rebel Alliance |
    | The Siths |
    | The Crime Syndicate |
#  And The following sprints are registered
#    | sprint-name |
#    | TK-421        |
#    | The Rebel Alliance |
#    | The Siths |
#    | The Crime Syndicate |

Scenario: Project has no previous sprints
  Given The sprint "Find Luke and Leia in the Death Star" is created in the "Capture-Luke" project
  And The following users are part of team "The Empire"
    | name              |
    | TK-421            |
    | Darth Vader       |
    | Darth Sidious     |
    | Grand Moff Tarkin |
  And The following users are committing to the sprint "Find Luke and Leia in the Death Star"
    | name              | man-days |
    | TK-421            |    15    |
    | Darth Vader       |    13    |
    | Darth Sidious     |    15    |
    | Grand Moff Tarkin |    7     |
  When The sprint "Find Luke and Leia in the Death Star" is started with an estimated velocity of 0 story points
  Then The sprint "Find Luke and Leia in the Death Star" should have an estimated velocity of 35 story points

Scenario: The Team has 1 closed previous sprint
  Given The sprint "Conquer Hoth" is created in the "Conquer planet" project
  And The following users are part of team "The Empire"
    | name              |
    | TK-421            |
    | Darth Vader       |
    | Darth Sidious     |
    | Grand Moff Tarkin |
  And The following users are committing to the sprint "Conquer Hoth"
    | name               | man-days |
    | TK-421             |    10    |
    | Darth Vader        |    15    |
    | Darth Sidious      |    10    |
    | Grand Moff Tarkin  |    15    |
  And The team "The Empire" already closed the following sprints
    | name    | man-days | estimated | actual | actual-focus | past-focus-avg        |
    | Sprint1 |    50    |    35     |   20   | 20 / 50 = .4 | avg(.4) = .4          |
    | Sprint2 |    50    |    20     |   40   | 40 / 50 = .8 | avg(.4, .8) = .6      |
    | Sprint3 |    50    |    40     |   25   | 25 / 50 = .5 | avg(.4, .8, .5) = .57 |
  When The sprint "Conquer Hoth" is started with an estimated velocity of 0 story points
  Then The sprint "Conquer Hoth" should have an estimated velocity of 28 story points
