# Prevent Bans
Status: Accepted
## Summary
While resolving redirects,
facing that our IP got banned,
we decided to throttle requests and make them ordinary,
gaining a working redirect resolver,
accepting some more effort.

## Context
## Decision
* Resolve redirects but prevent bans by
* - throttling
* - use GET
* - add headers
