
up:
  docker compose \
    -f compose.yaml \
    -f compose.override.yaml \
    up \
    --detach

logs:
  docker compose \
    -f compose.yaml \
    -f compose.override.yaml \
    logs \
    --follow

down:
  docker compose \
    -f compose.yaml \
    -f compose.override.yaml \
    down

sf-up:
  symfony server:start

sf-down:
  symfony server:stop