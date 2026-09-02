# SCHOOL Deployer

## Runtime

- URL: `https://school.heartf.com/__deploy/`
- Repository: `Taiju-h/school`
- Branch: `main`
- Production path: `/home/heartf/heartf.com/public_html/school`
- Runtime directory: `/home/heartf/heartf.com/public_html/school/__deploy`
- Runtime state: `/home/heartf/.school-deployer`

The runtime `__deploy` directory is intentionally ignored by Git. The tracked
source lives in `.deploy/school`, so rolling the application back does not
remove the deployer itself.

## Initial installation

```bash
cd /home/heartf/heartf.com/public_html/school
git pull --ff-only origin main
chmod 700 .deploy/install-school-deployer.sh
.deploy/install-school-deployer.sh taiju
```

The installer asks for a Basic authentication password. Do not store that
password in Git.

## Safety rules

- Opening the page performs no fetch, merge, reset, or deployment.
- All state-changing actions use POST and a session CSRF token.
- Deploy uses `fetch` followed by `merge --ff-only origin/main`.
- Deploy and rollback stop when tracked local changes exist.
- Rollback moves production back exactly one commit and can be repeated.
- The deployer runtime remains available after rollback.
- Every successful deploy and rollback records time, user, IP, and commit IDs.
- Basic authentication is mandatory. The application refuses access if the
  web server does not supply an authenticated user.
- Once an IP is added to the allowlist, deploy and rollback are limited to
  registered IPs. Diff inspection and IP maintenance remain available to
  authenticated users.
