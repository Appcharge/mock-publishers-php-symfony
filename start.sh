#!/bin/bash

# Check if the environment variables are set
if [[ -z "$KEY" ]]; then
  echo "ERROR: KEY environment variable not set."
  exit 1
fi

if [[ -z "$IV" ]]; then
  echo "ERROR: IV environment variable not set."
  exit 1
fi

if [[ -z "$FACEBOOK_APP_SECRET" ]]; then
  echo "ERROR: FACEBOOK_APP_SECRET environment variable not set."
  exit 1
fi

# Start the Symfony server with the environment variables
FACEBOOK_APP_SECRET="${FACEBOOK_APP_SECRET}" KEY="${KEY}" IV="${IV}" symfony server:start