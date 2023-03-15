# Use colleciton helper classes
Status: Accepted
## Summary
While processing all data,
wanting array manipulation better than native (mainly map and filter),
we decided to leverage loophp/collection, but only for processing,
to get cleaner code,
accepting a dependency.

## Context

### Criteria
- Array, iterable, etc
- Immutables
- Functional methods (possibly lazy via iterators)
- No bloat
- 
### StackOverflow
No help.
- https://stackoverflow.com/questions/14880681/collection-of-objects-of-an-specific-class-in-php
- https://stackoverflow.com/questions/1580223/java-like-collections-in-php
- https://stackoverflow.com/questions/48139090/outputting-data-using-a-collection-class-php
- https://stackoverflow.com/questions/8070362/need-advice-for-object-oriented-design-a-collection-of-items

### Finalists
- Cake https://packagist.org/packages/cakephp/collection
  - https://book.cakephp.org/4/en/core-libraries/collections.html 
  - Nice, has lazy mapping, functional methods
- https://packagist.org/packages/aimeos/map
  - Good documentation, lots of functional methods, no showstoppers
  - No lazy mapping.
  - 130k installs, looks alive
- https://github.com/loophp/collection
  - Looks modern and solid.

### Others
- https://packagist.org/packages/qaribou/immutable.php
  - Looks small and solid
- https://www.php.net/manual/en/spl.datastructures.php
  - Nope.
- https://packagist.org/packages/phpcollection/phpcollection
  - http://jmsyst.com/libs/php-collection
  - Only basic methods. But also sets.
- https://packagist.org/packages/doctrine/collections
  - https://www.doctrine-project.org/projects/doctrine-collections/en/latest/index.html
  - Looks solid, all we need.
  - NOT serializable: https://www.doctrine-project.org/projects/doctrine-collections/en/latest/serialization.html#serialization
- https://packagist.org/packages/ramsey/collection
  - https://github.com/ramsey/collection
  - Looks quite heavy. "Inspired by java collecitons".
- Yii https://www.yiiframework.com/doc/api/1.1/CList
    - Nice, but too heavy loaded with events, behaviors, etc.
- https://packagist.org/packages/danielgsims/php-collections
    - Not much usage and docs.

### Another round, 3
- https://packagist.org/packages/brezgalov/php-typed-collection
  - Simple, no array access
- https://github.com/nXu/typed-collection
  - For laravel. nice.
- https://github.com/danielgsims/php-collections
  - Nice.
