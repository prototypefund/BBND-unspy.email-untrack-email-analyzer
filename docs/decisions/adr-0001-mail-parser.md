# ADR: Parse Emails
Status: Accepted
## Summary
While writing email analyzer, 
needing an email parser,
we decided to use zbateson/mail-mime-parser,
to have a ready-made package,
accepting that its a bit dated.
## Context
Some research:
- https://github.com/php-mime-mail-parser/php-mime-mail-parser
- https://stackoverflow.com/questions/4721410/best-way-to-handle-email-parsing-decoding-in-php
- https://github.com/daniele-occhipinti/php-email-parser
  - Dead
- https://packagist.org/packages/zbateson/mail-mime-parser
  - https://github.com/zbateson/mail-mime-parser
  - https://mail-mime-parser.org/
- https://sigparser.com/developers/email-parsing/parse-raw-email/
- https://gist.github.com/tylermakin/d820f65eb3c9dd98d58721c7fb1939a8
- https://www.w3.org/Protocols/rfc1341/7_2_Multipart.html

## Decision
Use zbateson/mail-mime-parser, as we see no reasonable alternative.
## Consequences
There might be some pain with it being dated.
