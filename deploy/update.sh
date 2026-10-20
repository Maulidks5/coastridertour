#!/usr/bin/env bash

set -Eeuo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BRANCH="${DEPLOY_BRANCH:-main}"

cd "${PROJECT_ROOT}"

if [[ ! -d .git ]]; then
    echo "This directory is not a Git repository: ${PROJECT_ROOT}" >&2
    exit 1
fi

if [[ -n "$(git status --porcelain --untracked-files=no)" ]]; then
    echo "Deployment stopped because the server has tracked local changes." >&2
    echo "Review them with: git status" >&2
    exit 1
fi

echo "Updating ${BRANCH} from origin..."
git fetch origin "${BRANCH}"
git checkout "${BRANCH}"
git merge --ff-only "origin/${BRANCH}"

exec /bin/bash "${PROJECT_ROOT}/deploy/cpanel-deploy.sh"
