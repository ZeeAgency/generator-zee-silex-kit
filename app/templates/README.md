#TODO

##Setup
---

- Create a virtualHost with `DocumentRoot` pointing to
```
<repository-path>/public
```

- Get [Composer](http://getcomposer.org/) (if not installed globally)
```
curl -sS getcomposer.org/installer | php
```

- Install dependencies
```
composer install
```

- Create a new database and fill the "post-install" parameters with credentials, database name...

- Update the database
```
./bin/doctrine orm:schema-tool:update --force
```


##Doctrine
---
TODO


##Tests with PHPUnit
---
TODO
