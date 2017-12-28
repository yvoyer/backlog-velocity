#!/usr/bin/env bash

ROOT=`pwd`

echo "#########################"
echo "# Symlink mapping files #"
echo "#########################"

SRC_MAPPING="$ROOT/src/Agile/Infrastructure/Persistence/Doctrine/Resources/mappings"
DEST_MAPPING="$ROOT/src/Bundle/BacklogBundle/Resources/config/mappings"
/bin/ln -sf "$SRC_MAPPING/Star.BacklogVelocity.Agile.Domain.Model.PersonModel.dcm.xml" "$DEST_MAPPING/PersonModel.orm.xml"
/bin/ln -sf "$SRC_MAPPING/Star.BacklogVelocity.Agile.Domain.Model.ProjectAggregate.dcm.xml" "$DEST_MAPPING/ProjectAggregate.orm.xml"
/bin/ln -sf "$SRC_MAPPING/Star.BacklogVelocity.Agile.Domain.Model.SprintCommitment.dcm.xml" "$DEST_MAPPING/SprintCommitment.orm.xml"
/bin/ln -sf "$SRC_MAPPING/Star.BacklogVelocity.Agile.Domain.Model.SprintModel.dcm.xml" "$DEST_MAPPING/SprintModel.orm.xml"
/bin/ln -sf "$SRC_MAPPING/Star.BacklogVelocity.Agile.Domain.Model.TeamMemberModel.dcm.xml" "$DEST_MAPPING/TeamMemberModel.orm.xml"
/bin/ln -sf "$SRC_MAPPING/Star.BacklogVelocity.Agile.Domain.Model.TeamModel.dcm.xml" "$DEST_MAPPING/TeamModel.orm.xml"

bin/console doctrine:migrations:migrate -n

echo "Release complete"
