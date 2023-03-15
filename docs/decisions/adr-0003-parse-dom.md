# Parse dom
Status: Accepted
## Summary
While parsing email,
to parse the HTML DOM,
we decided to use masterminds/html5 package,
to have more robust parsing,
accepting another dependency.
## Context
How parse HTML DOM? Apart from native ext-dom, it's:
- https://stackoverflow.com/questions/3577641/how-do-you-parse-and-process-html-xml-in-php
- https://packagist.org/packages/ivopetkov/html5-dom-document-php
- https://packagist.org/packages/masterminds/html5

The packages promise more robust parsing.
Nice that the packages dom parsing result are type-compatible with native.

## Decision
Use masterminds/html5.

## Consequences
Another dependency, but hopefully less fragile.
