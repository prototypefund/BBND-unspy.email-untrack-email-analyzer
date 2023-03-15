# Parse and verify DKIM
Status: Accepted
## Summary
While parsing email,
we want to parse and verify DKIM signature,
we decided to use phpmailer/dkimvalidator,
to have a ready-made package,
accepting that it's ab it dated.
## Context
Some research:
- https://packagist.org/packages/angrychimp/php-dkim
- https://packagist.org/packages/pimlie/php-dkim
- 
- https://github.com/PHPMailer/DKIMValidator
- https://github.com/PHPMailer/DKIMValidator/issues/18
- https://github.com/PHPMailer/DKIMValidator/pull/17
- 
- https://symfony.com/doc/current/components/mime.html
- https://stackoverflow.com/questions/24627098/retrieve-and-parse-emails-from-pop3-or-imap-mailbox-in-symfony2
- https://symfony.com/blog/new-in-symfony-5-2-dkim-email-authentication
- https://symfony.com/doc/current/mailer.html#dkim-signer
- https://stackoverflow.com/questions/4313481/whats-the-difference-between-domainkey-signature-dkim-signature
- https://stackoverflow.com/questions/70120578/generate-eml-file-for-dkim-signatue
- https://github.com/JV-conseil-Internet-Consulting/dkim-php-mail-signature
- https://packagist.org/packages/angrychimp/php-dkim
- https://github.com/louisameline/php-mail-signature
- https://packagist.org/packages/simonschaufi/laravel-dkim
- https://packagist.org/packages/illuminate/mail
- https://github.com/simonschaufi/laravel-dkim
- https://www.validity.com/blog/how-to-explain-authenticated-received-chain-arc-in-plain-english/
- https://www.validity.com/blog/how-to-explain-authenticated-received-chain-arc-in-plain-english/

## Decision
Use phpmailer/dkimvalidator.
## Consequences
There might be some pain with it being dated.
