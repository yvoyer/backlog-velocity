# Release notes

## 2.0.0

*Goal*: Add Web UI and refactor of infrastructure.

* [#131](https://github.com/yvoyer/backlog-velocity/issues/131) - Rename estimated velocity to planned velocity
* [#129](https://github.com/yvoyer/backlog-velocity/issues/129) - Optimize SQL queries
* [#123](https://github.com/yvoyer/backlog-velocity/issues/123) - Dates to sprint footer
* [#120](https://github.com/yvoyer/backlog-velocity/issues/120) - Add live save when setting commitment of members
* [#124](https://github.com/yvoyer/backlog-velocity/issues/124) - Add links to members list of team
* [#111](https://github.com/yvoyer/backlog-velocity/issues/111) - List all sprints of team
* [#106](https://github.com/yvoyer/backlog-velocity/issues/106) - Create schema using migrations
* [#103](https://github.com/yvoyer/backlog-velocity/issues/106) - Remove old concepts
* [#91](https://github.com/yvoyer/backlog-velocity/issues/91)- Move all resources to Bundle
* [#87](https://github.com/yvoyer/backlog-velocity/issues/87)- Use migration to manage DB
* [#88](https://github.com/yvoyer/backlog-velocity/issues/88)- Plug all command using the SF container
* [#95](https://github.com/yvoyer/backlog-velocity/issues/95)- Register command and query without the need to update the config.yml
* [#93](https://github.com/yvoyer/backlog-velocity/issues/93)- Move all code to the Hexagonal architecture pattern
* [#86](https://github.com/yvoyer/backlog-velocity/issues/86)- Fix all issues of profiler
* [#97](https://github.com/yvoyer/backlog-velocity/issues/97)- Closing a sprint in the UI
* [#90](https://github.com/yvoyer/backlog-velocity/issues/90)- Include calculator to container
* [#89](https://github.com/yvoyer/backlog-velocity/issues/89)- Enable PHP 7.1 strict mode
* [#85](https://github.com/yvoyer/backlog-velocity/issues/85)- Add sprint creation in UI
* [#81](https://github.com/yvoyer/backlog-velocity/issues/81)- Add Project Dashboard
* [#77](https://github.com/yvoyer/backlog-velocity/issues/77)- Add Bootstrap integration
* [#79](https://github.com/yvoyer/backlog-velocity/issues/79)- Upgrade PHPUnit to version 6+
* [#70](https://github.com/yvoyer/backlog-velocity/issues/70)- Install Symfony
* [#74](https://github.com/yvoyer/backlog-velocity/issues/74)- Add vagrant setup to project

## 1.0.0

* Add a person.
* List all persons.
* Create a new sprint for the team.
* Stop a sprint.
* Join a team member to a sprint.
* List all available sprints.
* Start a sprint.
* Add a team.
* Link a person to a team.
* List the teams.

## Procedure for release

Copy this template in the PR description.

```
* [ ] Create a branch names `release/X.Y.Z` (with optional `rc-X` when applicable)
* [ ] Edit [Release notes](RELEASE-NOTES.md): `git log --oneline PREVIOUS_VERSION..HEAD`
* [ ] Add list of PR and issues related to release
* [ ] Add short description of release
* [ ] Update [Application Version](https://github.com/yvoyer/backlog-velocity/blob/master/src/Cli/BacklogApplication.php#L23)
* [ ] Make sure all the bugs discovered in QA are fixed
* [ ] Do Pull Request
* [ ] Upon `merge-squash`, tag commit to `X.Y.Z`
```
