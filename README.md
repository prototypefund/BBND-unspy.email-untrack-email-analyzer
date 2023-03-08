# Unspy.Email: Analyzer

Part of the unspy.email suite: Analyzes emails for user tracking links.
(The "untrack_email" prefix is for legacy reasons.)

## What it does

The analyzer component...
- parses the email
- checks DKIM signature
- extracts headers
- extracts link and image URLs
- among these, guesses unsubscribe URLs
- queries all CName domain aliases of all found domains
- matches URLs for well-known analytics patterns
- matches URLs for well-known provider patterns (see [docs/matchers.md](docs/matchers.md)), adding matched unsubscribe URLs
- queries all URLs (except guessed or matched unsubscribe URLs) for redirections (async for speed, throttled to not get banned)
- extracts the mailing list (via heuristic)
- extracts a *verdict* from all that results

The result data structure was balanced to be generic enough to serve for further analyses, and also structured in a way to allow displaying it without too much further processing.

For more details see \Geeks4change\UntrackEmailAnalyzer\Analyzer\Analyzer and \Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\FullResultWrapper.

## Usage

See \Drupal\untrack_email_storage\UntrackEmailProcessor::process() in the geeks4chane/untrack_email_storage package.

## Development
For technical docs see [docs/README.md](docs/README.md).

This package is meant to be used as analyzer engine in untrack_email_storage.
For installation and usage see that one:
https://gitlab.com/geeks4change/untrack.email/untrack_email_storage
