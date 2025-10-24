#!/bin/sh
set -e

# Ensure Angular CLI is available
if ! command -v ng >/dev/null 2>&1; then
  npm i -g @angular/cli@latest
fi

cd /usr/src/app

# If no Angular workspace, scaffold one into current directory
if [ ! -f angular.json ]; then
  npx -y @angular/cli@latest new custoflow-frontend \
    --directory . \
    --skip-git \
    --style scss \
    --routing \
    --package-manager npm \
    --force
fi

# Ensure dependencies are installed
if [ -f package.json ]; then
  if [ -f package-lock.json ]; then
    npm ci || npm install
  else
    npm install
  fi
fi

# Add Angular Material if not already present (non-interactive)
if [ -f package.json ] && ! grep -q "@angular/material" package.json 2>/dev/null; then
  npx -y ng add @angular/material --skip-confirmation --defaults
fi

# Start Angular dev server bound to 0.0.0.0 for Docker
npm run start -- --host 0.0.0.0 --port 4200 --poll 2000


