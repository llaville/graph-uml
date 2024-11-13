#!/usr/bin/env bash

SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )

ASSETS_IMAGE_DIR="docs/assets/images"

php $SCRIPT_DIR/../examples/graphviz.php application $ASSETS_IMAGE_DIR svg 1
php $SCRIPT_DIR/../examples/graphviz.php formatter $ASSETS_IMAGE_DIR svg 1
php $SCRIPT_DIR/../examples/graphviz.php generator $ASSETS_IMAGE_DIR svg 1
php $SCRIPT_DIR/../examples/graphviz.php multiple-classes $ASSETS_IMAGE_DIR svg 1
php $SCRIPT_DIR/../examples/graphviz.php php-extensions $ASSETS_IMAGE_DIR svg 1
php $SCRIPT_DIR/../examples/graphviz.php single-class $ASSETS_IMAGE_DIR svg 1
php $SCRIPT_DIR/../examples/graphviz.php without-elements $ASSETS_IMAGE_DIR svg 1
