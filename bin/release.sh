#!/usr/bin/env bash

ROOT=`pwd`

echo "#########################"
echo "# Symlink mapping files #"
echo "#########################"

SRC_MAPPING="../../../../../../plugin/Star/Plugin/Doctrine/Resources/config/doctrine"
DEST_MAPPING="$ROOT/src/Application/BacklogBundle/Resources/config/mappings"
/bin/ln -sf "$SRC_MAPPING/Star.Component.Sprint.Model.PersonModel.dcm.xml" "$DEST_MAPPING/PersonModel.orm.xml"
/bin/ln -sf "$SRC_MAPPING/Star.Component.Sprint.Model.ProjectAggregate.dcm.xml" "$DEST_MAPPING/ProjectAggregate.orm.xml"
/bin/ln -sf "$SRC_MAPPING/Star.Component.Sprint.Model.SprintCommitment.dcm.xml" "$DEST_MAPPING/SprintCommitment.orm.xml"
/bin/ln -sf "$SRC_MAPPING/Star.Component.Sprint.Model.SprintModel.dcm.xml" "$DEST_MAPPING/SprintModel.orm.xml"
/bin/ln -sf "$SRC_MAPPING/Star.Component.Sprint.Model.TeamMemberModel.dcm.xml" "$DEST_MAPPING/TeamMemberModel.orm.xml"
/bin/ln -sf "$SRC_MAPPING/Star.Component.Sprint.Model.TeamModel.dcm.xml" "$DEST_MAPPING/TeamModel.orm.xml"

echo "Release complete"
