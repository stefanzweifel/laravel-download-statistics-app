#!/bin/sh -l
set -eu

#  Setup Laravel App
cp .env.example .env
php artisan key:generate

# Fetch download numbers for last month
php artisan app:fetch-downloads

# Create new HTML Export
php artisan export
