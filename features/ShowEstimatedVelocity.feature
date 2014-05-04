Feature: Calculate the estimated velocity
  As a system user
  I want to see the estimated velocity for my new sprint
  So that I can plan the stories to add in it.

Background:
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

Scenario: Team has no previous sprints
  Given The following users are part of "The Empire" team
    | name              | man-days |
    | TK-421            |    15    |
    | Darth Vader       |    13    |
    | Darth Sidious     |    15    |
    | Grand Moff Tarkin |    7     |
  When The "The Empire" team create the "Capture Luke" sprint
  And  Start the sprint "Capture Luke" with a length of 15 days
  Then The "Capture Luke" sprint should have an estimated velocity of 35 story points

Scenario: The Team has 1 closed previous sprint
  Given The following users are part of "The Empire" team
    | name               | man-days |
    | TK-421             |    10    |
    | Darth Vader        |    15    |
    | Darth Sidious      |    10    |
    | Grand Moff Tarkin  |    15    |
  And The team "The Empire" already closed the following sprints
    | name    | man-days | estimated | actual |
    | Sprint1 |    45    |    20     |   18   |
  When The "The Empire" team create the "Conquer planet" sprint
  And  Start the sprint "Conquer planet" with a length of 15 days
  Then The "Conquer planet" sprint should have an estimated velocity of 20 story points
