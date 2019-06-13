workflow "New Monthly Build" {
  on = "schedule(15 1 1 * *)"
  resolves = [
    "auto-commit-monthly-build"
  ]
}

# Install composer dependencies
action "composer install" {
  uses = "MilesChou/composer-action@master"
  args = "install -q --no-ansi --no-interaction --no-scripts --no-suggest --no-progress --prefer-dist"
}

action "update-data" {
  needs = ["composer install"]
  uses = "./actions/update-data/"
}

action "auto-commit-monthly-build" {
  needs = ["update-data"]
  uses = "stefanzweifel/git-auto-commit-action@v1.0.0"
  secrets = ["GITHUB_TOKEN"]
  env = {
    COMMIT_MESSAGE = "Update Data Set"
    COMMIT_AUTHOR_EMAIL  = "hello@stefanzweifel.io"
    COMMIT_AUTHOR_NAME = "Stefan Zweifel"
  }
}
