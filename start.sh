#!/bin/sh

# Load .env first (default values)
if [ -f .env ]; then
  export $(grep -v '^#' .env | xargs)
fi

# Load .env.local if it exists (overrides values)
if [ -f .env.local ]; then
  export $(grep -v '^#' .env.local | xargs)
fi

php -S localhost:$APP_PORT
