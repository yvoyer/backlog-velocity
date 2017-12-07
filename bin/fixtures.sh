#!/usr/bin/env bash

ROOT=`pwd`
rm "$ROOT/backlog.sqlite"

# Project management
oldRepublic="Old Republic"
newOrder="New Order"
firstOrder="First Order"
standalone="Standalone"
"$ROOT/backlog" b:project:create "$oldRepublic"
"$ROOT/backlog" b:project:create "$newOrder"
"$ROOT/backlog" b:project:create "$standalone"
"$ROOT/backlog" b:project:create "$firstOrder"

# Persons
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
"$ROOT/backlog" b:person:add "Jyn Erso"
"$ROOT/backlog" b:person:add "Gallen Erso"
"$ROOT/backlog" b:person:add "Director Krenick"
"$ROOT/backlog" b:person:add "Finn"
"$ROOT/backlog" b:person:add "Rey"
"$ROOT/backlog" b:person:add "Kylo Ren"

"$ROOT/backlog" b:person:list

# Teams
empire="The Empire"
rebelAlliance="Rebel Alliance"
siths="The Siths"
criminals="The Crime Syndicate"
jedis="Jedi"
"$ROOT/backlog" b:team:add "$empire" --project="$newOrder"
"$ROOT/backlog" b:team:add "$rebelAlliance" --project="$newOrder"
"$ROOT/backlog" b:team:add "$siths" --project="$oldRepublic"
"$ROOT/backlog" b:team:add "$criminals" --project="$newOrder"
"$ROOT/backlog" b:team:add "$jedis" --project="$newOrder"

# Assign person to team
"$ROOT/backlog" b:team:join "Darth Vader" "$empire"
"$ROOT/backlog" b:team:join "TK-421" "$empire"
"$ROOT/backlog" b:team:join "The Emperor" "$empire"

"$ROOT/backlog" b:team:join "Leia Organa" "$rebelAlliance"
"$ROOT/backlog" b:team:join "Luke Skywalker" "$rebelAlliance"
"$ROOT/backlog" b:team:join "Yoda" "$rebelAlliance"
"$ROOT/backlog" b:team:join "Han Solo" "$rebelAlliance"

"$ROOT/backlog" b:team:join "Darth Vader" "$siths"
"$ROOT/backlog" b:team:join "The Emperor" "$siths"
"$ROOT/backlog" b:team:join "Count Dooku" "$siths"

"$ROOT/backlog" b:team:join "Bobba Fett" "$criminals"
"$ROOT/backlog" b:team:join "Jabba The Hutt" "$criminals"
"$ROOT/backlog" b:team:join "Han Solo" "$criminals"

"$ROOT/backlog" b:team:join "Anakin Skywalker" "$jedis"
"$ROOT/backlog" b:team:join "Luke Skywalker" "$jedis"
"$ROOT/backlog" b:team:join "Leia Organa" "$jedis"
"$ROOT/backlog" b:team:join "Darth Vader" "$jedis"
"$ROOT/backlog" b:team:join "Mace Windu" "$jedis"
"$ROOT/backlog" b:team:join "Yoda" "$jedis"
"$ROOT/backlog" b:team:join "Count Dooku" "$jedis"

"$ROOT/backlog" b:team:join "Director Krenick" "$empire"
"$ROOT/backlog" b:team:join "Gallen Erso" "$empire"
"$ROOT/backlog" b:team:join "Gallen Erso" "$rebelAlliance"
"$ROOT/backlog" b:team:join "Jyn Erso" "$rebelAlliance"

"$ROOT/backlog" b:team:join "Kylo Ren" "$empire"
"$ROOT/backlog" b:team:join "Rey" "$empire"
"$ROOT/backlog" b:team:join "Finn" "$empire"
"$ROOT/backlog" b:team:join "Finn" "$rebelAlliance"

"$ROOT/backlog" b:team:list

# Sprint management
episodeOne="Episode 1"
episodeTwo="Episode 2"
episodeThree="Episode 3"
episodeSeven="Episode 7"
episodeEight="Episode 8"
rogueOne="Rogue One"
hanSoloStory="Han Solo chronicles"
"$ROOT/backlog" b:sprint:add "$episodeOne" "$oldRepublic"
"$ROOT/backlog" b:sprint:add "$episodeTwo" "$oldRepublic"
"$ROOT/backlog" b:sprint:add "$episodeThree" "$oldRepublic"
"$ROOT/backlog" b:sprint:add "$episodeOne" "$newOrder"
"$ROOT/backlog" b:sprint:add "$episodeTwo" "$newOrder"
"$ROOT/backlog" b:sprint:add "$episodeThree" "$newOrder"
"$ROOT/backlog" b:sprint:add "$rogueOne" "$standalone"
"$ROOT/backlog" b:sprint:add "$hanSoloStory" "$standalone"
"$ROOT/backlog" b:sprint:add "$episodeSeven" "$firstOrder"
"$ROOT/backlog" b:sprint:add "$episodeEight" "$firstOrder"

# Old republic
"$ROOT/backlog" b:sprint:join "$episodeOne" "Anakin Skywalker" 9 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "$episodeOne" "Mace Windu" 4 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "$episodeOne" "Yoda" 5 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "$episodeOne" "Count Dooku" 1 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "$episodeOne" "Jabba The Hutt" 1 "$oldRepublic"

"$ROOT/backlog" b:sprint:join "$episodeTwo" "Anakin Skywalker" 10 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "$episodeTwo" "Mace Windu" 3 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "$episodeTwo" "Yoda" 2 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "$episodeTwo" "Count Dooku" 1 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "$episodeTwo" "Bobba Fett" 2 "$oldRepublic"

"$ROOT/backlog" b:sprint:join "$episodeThree" "Darth Vader" 1 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "$episodeThree" "Mace Windu" 6 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "$episodeThree" "Yoda" 4 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "$episodeThree" "Count Dooku" 2 "$oldRepublic"
"$ROOT/backlog" b:sprint:join "$episodeThree" "Bobba Fett" 1 "$oldRepublic"

# New Order
"$ROOT/backlog" b:sprint:join "$episodeOne" "Darth Vader" 9 "$newOrder"
"$ROOT/backlog" b:sprint:join "$episodeOne" "Leia Organa" 8 "$newOrder"
"$ROOT/backlog" b:sprint:join "$episodeOne" "Luke Skywalker" 8 "$newOrder"
"$ROOT/backlog" b:sprint:join "$episodeOne" "TK-421" 1 "$newOrder"
"$ROOT/backlog" b:sprint:join "$episodeOne" "Han Solo" 8 "$newOrder"

"$ROOT/backlog" b:sprint:join "$episodeTwo" "Darth Vader" 8 "$newOrder"
"$ROOT/backlog" b:sprint:join "$episodeTwo" "Leia Organa" 8 "$newOrder"
"$ROOT/backlog" b:sprint:join "$episodeTwo" "Luke Skywalker" 8 "$newOrder"
"$ROOT/backlog" b:sprint:join "$episodeTwo" "Yoda" 3 "$newOrder"
"$ROOT/backlog" b:sprint:join "$episodeTwo" "The Emperor" 1 "$newOrder"
"$ROOT/backlog" b:sprint:join "$episodeTwo" "Bobba Fett" 2 "$newOrder"
"$ROOT/backlog" b:sprint:join "$episodeTwo" "Han Solo" 8 "$newOrder"

"$ROOT/backlog" b:sprint:join "$episodeThree" "Darth Vader" 9 "$newOrder"
"$ROOT/backlog" b:sprint:join "$episodeThree" "Leia Organa" 8 "$newOrder"
"$ROOT/backlog" b:sprint:join "$episodeThree" "Luke Skywalker" 8 "$newOrder"
"$ROOT/backlog" b:sprint:join "$episodeThree" "Yoda" 3 "$newOrder"
"$ROOT/backlog" b:sprint:join "$episodeThree" "The Emperor" 4 "$newOrder"
"$ROOT/backlog" b:sprint:join "$episodeThree" "Bobba Fett" 2 "$newOrder"
"$ROOT/backlog" b:sprint:join "$episodeThree" "Jabba The Hutt" 1 "$newOrder"
"$ROOT/backlog" b:sprint:join "$episodeThree" "Han Solo" 8 "$newOrder"

# Standalone
"$ROOT/backlog" b:sprint:join "$rogueOne" "Darth Vader" 1 "$standalone"
"$ROOT/backlog" b:sprint:join "$rogueOne" "Leia Organa" 1 "$standalone"
"$ROOT/backlog" b:sprint:join "$rogueOne" "Jyn Erso" 8 "$standalone"
"$ROOT/backlog" b:sprint:join "$rogueOne" "Gallen Erso" 3 "$standalone"
"$ROOT/backlog" b:sprint:join "$rogueOne" "Director Krenick" 8 "$standalone"

"$ROOT/backlog" b:sprint:join "$hanSoloStory" "Han Solo" 8 "$standalone"

# Started
"$ROOT/backlog" b:sprint:join "$episodeSeven" "Han Solo" 5 "$firstOrder"
"$ROOT/backlog" b:sprint:join "$episodeSeven" "Leia Organa" 3 "$firstOrder"
"$ROOT/backlog" b:sprint:join "$episodeSeven" "Finn" 8 "$firstOrder"
"$ROOT/backlog" b:sprint:join "$episodeSeven" "Rey" 8 "$firstOrder"
"$ROOT/backlog" b:sprint:join "$episodeSeven" "Kylo Ren" 5 "$firstOrder"
"$ROOT/backlog" b:sprint:start --accept-suggestion -- "$episodeSeven" "$firstOrder"
"$ROOT/backlog" b:sprint:close "$episodeSeven" 25 "$firstOrder"

# Pending
"$ROOT/backlog" b:sprint:join "$episodeEight" "Leia Organa" 3 "$firstOrder"
"$ROOT/backlog" b:sprint:join "$episodeEight" "Finn" 8 "$firstOrder"
"$ROOT/backlog" b:sprint:join "$episodeEight" "Rey" 8 "$firstOrder"
"$ROOT/backlog" b:sprint:join "$episodeEight" "Kylo Ren" 8 "$firstOrder"
"$ROOT/backlog" b:sprint:start --accept-suggestion -- "$episodeEight" "$firstOrder"

# Closed
"$ROOT/backlog" b:sprint:start --accept-suggestion -- "$rogueOne" "$standalone"
"$ROOT/backlog" b:sprint:close "$rogueOne" 15 "$standalone"

# todo Archived
"$ROOT/backlog" b:sprint:start --accept-suggestion -- "$episodeOne" "$oldRepublic"
"$ROOT/backlog" b:sprint:close "$episodeOne" 17 "$oldRepublic"
"$ROOT/backlog" b:sprint:start --accept-suggestion -- "$episodeTwo" "$oldRepublic"
"$ROOT/backlog" b:sprint:close "$episodeTwo" 16 "$oldRepublic"
"$ROOT/backlog" b:sprint:start --accept-suggestion -- "$episodeThree" "$oldRepublic"
"$ROOT/backlog" b:sprint:close "$episodeThree" 18 "$oldRepublic"
"$ROOT/backlog" b:sprint:start --accept-suggestion -- "$episodeOne" "$newOrder"
"$ROOT/backlog" b:sprint:close "$episodeOne" 30 "$newOrder"
"$ROOT/backlog" b:sprint:start --accept-suggestion -- "$episodeTwo" "$newOrder"
"$ROOT/backlog" b:sprint:close "$episodeTwo" 44 "$newOrder"
"$ROOT/backlog" b:sprint:start --accept-suggestion -- "$episodeThree" "$newOrder"
"$ROOT/backlog" b:sprint:close "$episodeThree" 37 "$newOrder"

"$ROOT/backlog" b:sprint:list

