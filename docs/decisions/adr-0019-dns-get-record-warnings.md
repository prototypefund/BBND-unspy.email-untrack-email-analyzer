# Handling DNS warnings
Status: Accepted
## Summary
While resolving domains,
facing some dns_get_record warnings,
we decided that we can not do anything sensible about them.
## Context

https://dustri.org/b/a-short-tale-on-phps-dns_get_record.html

PHP Warning:  dns_get_record(): A temporary server error occurred. in /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/phpmailer/dkimvalidator/src/Validator.php on line 284

Some servers do not serve "any" requests. 
https://bugs.php.net/bug.php?id=73149
But that's not the case here.

Turns out that sometimes an empty domain comes in.

Nonetheless: How to catch a warning:
https://stackoverflow.com/questions/1241728/can-i-try-catch-a-warning
