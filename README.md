# ORM filters

Define filters on a `FilterManager` to apply request or array values to a database query:

```php
use Nip\Records\Filters\Column\BasicFilter;
use Nip\Records\Filters\FilterManager;

$manager = new FilterManager();
$manager->addFilter((new BasicFilter())->setField('status')->setDbName('status'));
$manager->setRequest($request);
$manager->filterQuery($query);
```

Requests may be Symfony HTTP Foundation requests (including `Nip\Http\Request`), ByTIC PSR requests, or any PSR-7 server request. Values are read from attributes, then query parameters, then form parameters (Symfony) or the parsed body (PSR-7). Valid request values are cleaned before use; absent or invalid values are inactive.

Use `$manager->createSession(['status' => 'active'])` for an independent session with explicit values, then pass its name as the second argument to `filterQuery()`. Array values are used directly rather than processed as request input.

The default session is created on first use. To replace it after changing requests, call `$manager->createSession($request, FilterManager::DEFAULT_SESSION)`.
