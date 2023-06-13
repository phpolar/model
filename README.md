<p align="center">
    <img width="240" src="./phpolar.svg" />
</p>

# PHPolar Model

## Provides support for configuring the properties of an object for validation, formatting, and storage.

[![Coverage Status](https://coveralls.io/repos/github/phpolar/model/badge.svg?branch=main)](https://coveralls.io/repos/github/phpolar/model/badge.svg?branch=main) [![Latest Stable Version](http://poser.pugx.org/phpolar/model/v)][def] [![Total Downloads](http://poser.pugx.org/phpolar/model/downloads)][def] [![Latest Unstable Version](http://poser.pugx.org/phpolar/model/v/unstable)][def] [![License](http://poser.pugx.org/phpolar/model/license)][def] [![PHP Version Require](http://poser.pugx.org/phpolar/model/require/php)][def]

### Example 1

```php
<!DOCTYPE html>
<?php

namespace MyApp;

use Phpolar\Phpolar\FormControlTypes;

/**
 * @var PersonForm
 */
$view = $this;
?>
<html>
    // ...
    <body style="text-align:center">
        <h1><?= $view->title ?></h1>
        <div class="container">
            <form action="<?= $view->action ?>" method="post">
                <?php foreach ($view as $propName => $propVal): ?>
                    <label><?= $view->getLabel($propName) ?></label>
                    <?php switch ($view->getFormControlType($propName)): ?>
                        <?php case FormControlTypes::Input: ?>
                            <input type="text" value="<?= $propVal ?>" />
                        <?php case FormControlTypes::Select: ?>
                            <select>
                                <?php foreach ($propVal as $name => $item): ?>
                                    <option value="<?= $item ?>"><?= $name ?></option>
                                <?php endforeach ?>
                            </select>
                    <?php endswitch ?>
                <?php endforeach ?>
            </form>
        </div>
    </body>
</html>
```

### Use Attributes to Configure Models

```php
use Phpolar\Phpolar\AbstractModel;

class Person extends AbstractModel
{
    public string $title = "Person Form";

    public string $action = "somewhere";

    #[MaxLength(20)]
    public string $firstName;

    #[MaxLength(20)]
    public string $lastName;

    #[Column("Residential Address")]
    #[Label("Residential Address")]
    #[MaxLength(200)]
    public string $address1;

    #[Column("Business Address")]
    #[Label("Business Address")]
    #[MaxLength(200)]
    public string $address2;
}
```

### Thresholds

|Source Code Size|Memory Usage|
|----------------|------------|
|     19 kB    |   108 kB   |

[def]: https://packagist.org/packages/phpolar/model
