#!/usr/bin/env zsh

version=`defaults read $(pwd)/workflow/info version`
mkdir dist
cd workflow
zip -r "../dist/alfred-kirby-search-$version.alfredworkflow" . -x ./prefs.plist
