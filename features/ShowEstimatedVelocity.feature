Feature: Calculate the estimated velocity
  As a system user
  I want to see the estimated velocity for my new sprint
  So that I can plan the stories to add in it.

Scenario: Team has no previous sprints
  Given The following users are part of "The Empire" team
    | name    | man-days |
    | TK-421  |    15    |
    | Vader   |    13    |
    | Sidious |    15    |
    | Tarkin  |    7     |
  When I create the "Capture Luke" sprint with a length of 15 days
  Then The team available man days should be 50 man days
  And  I should have an estimated velocity of 35 story points

Scenario: The Team has 1 closed previous sprint
  Given The following users are part of "The Empire" team
    | name    | man-days |
    | TK-421  |    10    |
    | Vader   |    15    |
    | Sidious |    10    |
    | Tarkin  |    15    |
  And The team already closed the following sprints
    | name    | man-days | estimated | actual |
    | Sprint1 |    45    |    20     |   18   |
  When I create the "Conquer planet" sprint with a length of 15 days
  Then The team available man days should be 50 man days
  And  I should have an estimated velocity of 20 story points
