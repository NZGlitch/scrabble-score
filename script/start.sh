#!/bin/bash

SERVICE_NAME="web"

echo "🔍 Checking for running containers..."
RUNNING_CONTAINER=$(docker ps -q --filter "name=${SERVICE_NAME}")

if [ -n "$RUNNING_CONTAINER" ]; then
  echo "🛑 Stopping running container..."
  docker-compose down
else
  echo "✅ No running container found."
fi

echo "🔨 Rebuilding the image..."
docker-compose build

echo "🚀 Starting the container..."
docker-compose -f docker-compose.yml up -d
