#!/bin/sh
# Script utilisé par git filter-branch pour injecter public/app.css à chaque commit.
# Les chunks sont lus depuis scripts/css-chunks (ou depuis un chemin absolu hors repo).
CHUNKS="${CHUNKS_DIR:-C:/xampp/htdocs/whatsaround-nail/scripts/css-chunks}"
mkdir -p public
printf "" > public/app.css
[ -f resources/views/layouts/app.blade.php ] && cat "$CHUNKS/01-base.css" >> public/app.css
[ -f resources/views/welcome.blade.php ] && cat "$CHUNKS/02-events.css" >> public/app.css
[ -f resources/views/evenement/consulter.blade.php ] && cat "$CHUNKS/03-consulter.css" >> public/app.css
[ -f resources/views/evenement/creer.blade.php ] && cat "$CHUNKS/04-creer.css" >> public/app.css
[ -f resources/views/user/profil.blade.php ] && cat "$CHUNKS/05-profil.css" >> public/app.css
[ -f resources/views/evenement/participants.blade.php ] && cat "$CHUNKS/06-participants.css" >> public/app.css
true
