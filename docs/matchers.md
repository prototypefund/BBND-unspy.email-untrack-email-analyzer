# Provider Matchers

A ProviderMatcher implements \Geeks4change\UntrackEmailAnalyzer\Matcher\MatcherInterface with methods
- matchHeader(HeaderItem $item): ?HeaderItemMatch
- matchUrl(UrlItem $urlItem): ?ProviderMatch

Its responsibility is to match
- Headers that are specific for a provider (and only these)
- URLs that are specific for user tracking (and only these) for a provider

Provider is usually a company that provides sending mails to a list of recipients, adding user tracking links, and managing the tracking database. But it may also be a wordpress plugin that does the same job. For reference, see e.g. the Mailchimp matcher.

# Contributing via MRs
## The Overall Picture
A provider matcher must match user tracking ("spying") URLs, and only these. Having a long has in a URL is not enough. It may be the has of the sender, or the list, and if only these items are in a links, it's campaign tracking, not user tracking, and perfectly fine for our purposes.
Which brings us to this authoring workflow:

## Contributing List Emails
- Prepare two throw-away email accounts (these email addresses will remain in the source code forever, so do not use personal accounts)
- Subscribe to one newsletter list with both email addresses
- Once you receive a newsletter issue, save them from both accounts to your disk (do NOT copy-paste it, that is likely to make it invalid)
- Research the provider (see below) and take its name in lowercase as {provider}. Example: mailchimp.
- If the provider is not yet in the "_todo" list, save it with the same pattern as the other examples:
  - Add the mails with filename `src/Matcher/_todo/{provider}/examples/{date}-{sender}.u{recipient}.eml` where
    - {provider} is the all-lowercased name of the provider, like "sendinblue"
    - {date} is the date the newsletter was sent, in the form yyyy-mm-dd
    - {sender} is the all-lowercased name of the newslette sender, with dashes instead of spaces, like "trusted-shops"
- Finally submit a MR (Gitlab Merge Request)

## Authoring and Contributing a Matcher
- Take a provider from the _todo list, and its 2 mails
- You can use the debug/test command in this repository to extract URLs of both mails
- Only look at URLs that differ between the mails
- Copy over the structure of one of the existing matchers, and add matching logic for significant headers and URLs
- Use the same namespace pattern, otherwise your matcher won't get recognized (debug/test is your friend here too)
- Use the existing matching helpers, if needed improve them, do not roll your own, it won't get accepted
- Add a test email to a test subdirectory (usually copy over one of the examples), and leverage debug/test to generate the expected result
- Finally submit a MR (Gitlab Merge Request)
