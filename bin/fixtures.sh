#!/usr/bin/env bash

ROOT=`pwd`
rm "$ROOT/backlog.sqlite"

# Project management
"$ROOT/backlog" b:project:create "Old Republic"
"$ROOT/backlog" b:project:create "New Order"

"$ROOT/backlog" b:person:add "Darth Vader"
"$ROOT/backlog" b:person:add "Anakin Skywalker"
"$ROOT/backlog" b:person:add "Leia Organa"
"$ROOT/backlog" b:person:add "Luke Skywalker"
"$ROOT/backlog" b:person:add "Mace Windu"
"$ROOT/backlog" b:person:add "Yoda"
"$ROOT/backlog" b:person:add "TK-421"
"$ROOT/backlog" b:person:add "The Emperor"
"$ROOT/backlog" b:person:add "Count Dooku"
"$ROOT/backlog" b:person:add "Bobba Fett"
"$ROOT/backlog" b:person:add "Jabba The Hutt"
"$ROOT/backlog" b:person:add "Han Solo"

"$ROOT/backlog" b:person:list

"$ROOT/backlog" b:team:add "The Empire"
"$ROOT/backlog" b:team:add "Rebel Alliance"
"$ROOT/backlog" b:team:add "The Siths"
"$ROOT/backlog" b:team:add "The Crime Syndicate"
"$ROOT/backlog" b:team:add "Jedi"

"$ROOT/backlog" b:team:join "Darth Vader" "The Empire"
"$ROOT/backlog" b:team:join "TK-421" "The Empire"
"$ROOT/backlog" b:team:join "The Emperor" "The Empire"

"$ROOT/backlog" b:team:join "Leia Organa" "Rebel Alliance"
"$ROOT/backlog" b:team:join "Luke Skywalker" "Rebel Alliance"
"$ROOT/backlog" b:team:join "Yoda" "Rebel Alliance"
"$ROOT/backlog" b:team:join "Han Solo" "Rebel Alliance"

"$ROOT/backlog" b:team:join "Darth Vader" "The Siths"
"$ROOT/backlog" b:team:join "The Emperor" "The Siths"
"$ROOT/backlog" b:team:join "Count Dooku" "The Siths"

"$ROOT/backlog" b:team:join "Bobba Fett" "The Crime Syndicate"
"$ROOT/backlog" b:team:join "Jabba The Hutt" "The Crime Syndicate"
"$ROOT/backlog" b:team:join "Han Solo" "The Crime Syndicate"

"$ROOT/backlog" b:team:join "Anakin Skywalker" "Jedi"
"$ROOT/backlog" b:team:join "Luke Skywalker" "Jedi"
"$ROOT/backlog" b:team:join "Leia Organa" "Jedi"
"$ROOT/backlog" b:team:join "Darth Vader" "Jedi"
"$ROOT/backlog" b:team:join "Mace Windu" "Jedi"
"$ROOT/backlog" b:team:join "Yoda" "Jedi"
"$ROOT/backlog" b:team:join "Count Dooku" "Jedi"

"$ROOT/backlog" b:team:list

# Sprint management
oldRepublic="Old Republic"
newOrder="New Order"
"$ROOT/backlog" b:sprint:add "Episode 1" "$oldRepublic"
"$ROOT/backlog" b:sprint:add "Episode 2" "$oldRepublic"
"$ROOT/backlog" b:sprint:add "Episode 3" "$oldRepublic"
"$ROOT/backlog" b:sprint:add "Episode 1" "$newOrder"
"$ROOT/backlog" b:sprint:add "Episode 2" "$newOrder"
"$ROOT/backlog" b:sprint:add "Episode 3" "$newOrder"

# Old republic
"$ROOT/backlog" b:sprint:join "Episode 1" "Anakin Skywalker" 99 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "Episode 1" "Mace Windu" 99 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "Episode 1" "Yoda" 99 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "Episode 1" "Count Dooku" 99 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "Episode 1" "Jabba The Hutt" 99 "$oldRepublic"

"$ROOT/backlog" b:sprint:join "Episode 2" "Anakin Skywalker" 99 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "Episode 2" "Mace Windu" 99 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "Episode 2" "Yoda" 99 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "Episode 2" "Count Dooku" 99 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "Episode 2" "Bobba Fett" 99 "$oldRepublic"

"$ROOT/backlog" b:sprint:join "Episode 3" "Darth Vader" 99 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "Episode 3" "Mace Windu" 99 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "Episode 3" "Yoda" 99 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "Episode 3" "Count Dooku" 99 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "Episode 3" "Bobba Fett" 99 "$oldRepublic"

# New Order
"$ROOT/backlog" b:sprint:join "Episode 1" "Darth Vader" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 1" "Leia Organa" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 1" "Luke Skywalker" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 1" "Yoda" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 1" "TK-421" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 1" "The Emperor" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 1" "Bobba Fett" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 1" "Han Solo" 99 "$newOrder"

"$ROOT/backlog" b:sprint:join "Episode 2" "Darth Vader" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 2" "Leia Organa" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 2" "Luke Skywalker" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 2" "Yoda" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 2" "The Emperor" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 2" "Bobba Fett" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 2" "Han Solo" 99 "$newOrder"

"$ROOT/backlog" b:sprint:join "Episode 3" "Darth Vader" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 3" "Leia Organa" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 3" "Luke Skywalker" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 3" "Yoda" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 3" "The Emperor" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 3" "Bobba Fett" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 3" "Jabba The Hutt" 99 "$newOrder"
"$ROOT/backlog" b:sprint:join "Episode 3" "Han Solo" 99 "$newOrder"

# Pending
# todo add Episode 8 as pending

# Started
# todo add Episode 7 as started

# Closed
# todo add Rogue one as close

# todo Archived
"$ROOT/backlog" b:sprint:start --accept-suggestion -- "Episode 1" "$oldRepublic"
"$ROOT/backlog" b:sprint:close "Episode 1" 33 "$oldRepublic"
"$ROOT/backlog" b:sprint:start --accept-suggestion -- "Episode 2" "$oldRepublic"
"$ROOT/backlog" b:sprint:close "Episode 2" 44 "$oldRepublic"
"$ROOT/backlog" b:sprint:start --accept-suggestion -- "Episode 3" "$oldRepublic"
"$ROOT/backlog" b:sprint:close "Episode 3" 55 "$oldRepublic"
"$ROOT/backlog" b:sprint:start --accept-suggestion -- "Episode 1" "$newOrder"
"$ROOT/backlog" b:sprint:close "Episode 1" 66 "$newOrder"
"$ROOT/backlog" b:sprint:start --accept-suggestion -- "Episode 2" "$newOrder"
"$ROOT/backlog" b:sprint:close "Episode 2" 77 "$newOrder"
"$ROOT/backlog" b:sprint:start --accept-suggestion -- "Episode 3" "$newOrder"
"$ROOT/backlog" b:sprint:close "Episode 3" 88 "$newOrder"

"$ROOT/backlog" b:sprint:list

