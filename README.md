# Laravel Action Log

erfpl908

## Methods

```php
// used within Subjects
function logAction($action_name, $meta_key = null, $meta_value = null, $extra = null)

// global usage
function action_event($subject, $action_name)
function action_event($subject, $action_name, $meta_key = null, $meta_value = null, $extra = null)
```

## Usage

```php
action_event($registrant, 'packages.change', null, $registrant->packages, null);

// is equivalent to:
$registrant->logAction('packages.change', null, $registrant->packages, null);
```


## Tracking relationship changes

Changes made directly onto the model or to its BelongsTo relationships, will be logged automatically given that those relationship fields are on that same table. (e.g: status_code_id, person_id).
To track relationship changes, we have to do this:

  ### HasMany

Log related model ids inside `$meta_value` AND optionally take snapshot of all those models inside `$extra`

```php
$related_keys_name = $registrant->packages()->getRelated()->getKeyName(); // usually just 'id'
$related_ids = $registrant->packages->pluck($related_keys_name); // Collection of [2, 3, 5]

action_event($registrant, 'packages.change', null, $related_ids, array(
    'data' => $registrant->packages->toArray()
));
```

### BelongsToMany

Same logic, except we want to track pivot too.

```php
$related_keys_name = $registrant->tours()->getRelated()->getKeyName(); // 'id'
$related_ids = $registrant->tours->pluck($related_keys_name); // Collection of [1, 3, 4]

action_event($registrant, 'tours.change', null, $related_ids, array(
    'data' => $registrant->tours->toArray(),
    'pivot' => $registrant->tours->pluck('pivot')
));
```



