Feature: Show the estimated velocity
  In order to add a sprint
  As a system user
  I want to see the estimated velocity for my new sprint

Scenario: Create the first team's sprint
  Given The following users are part of "The Empire" team
    | name    | man-days |
    | TK-421  |    10    |
    | Vader   |    7     |
    | Sidious |    5     |
    | Tarkin  |    8     |
  When I create the "Capture Luke" sprint with a length of 10 days
  Then I should have an estimated velocity of 21 story points