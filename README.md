<p align="center">
    <img width="240" src="./phpolar.svg" alt="phpolar logo" />
</p>

# PHPolar Model

## Provides support for configuring the properties of an object for validation, formatting, and storage.

[![Coverage Status](https://coveralls.io/repos/github/phpolar/model/badge.svg?branch=main)](https://coveralls.io/repos/github/phpolar/model/badge.svg?branch=main) [![Latest Stable Version](https://poser.pugx.org/phpolar/model/v)](https://packagist.org/packages/phpolar/model) [![Total Downloads](https://poser.pugx.org/phpolar/model/downloads)](https://packagist.org/packages/phpolar/model) [![PHP Version Require](https://poser.pugx.org/phpolar/model/require/php)](https://packagist.org/packages/phpolar/model)

### Example 1

```php
<!DOCTYPE html>
<?php
namespace MyApp;

use Phpolar\Phpolar\FormControlTypes;

(function (PersonForm $view) {
?>
<html>
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
<?php
})($this);
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
|     19 kB    |   175 kB   |

[def]: https://packagist.org/packages/phpolar/model
