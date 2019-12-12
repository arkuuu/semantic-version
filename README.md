# SemanticVersion
A small library to handle semantic versions and compare them to each other.

This is a small library with restricted real life use.
I mainly created it to practice unit testing, github actions and publishing to packagist.
If you really need a library like this, try [Composer\Semver](https://packagist.org/packages/composer/semver).

## Installation
Use composer to install from packagist.
```
composer require arkuuu/semantic-version
```

## Usage
```php
$currentVersion = new Version('1.0.17');
$minimalVersion = new Version('1.0');
$versionOk = $currentVersion->isGreaterOrEqual($minimalVersion);
// $versionOk will be "true"


$versionA = new Version('1.0.17');
$versionB = new Version('1.0.25');
$sameVersions = $versionA->isSame($versionB);
// $sameVersions will be "false"
```

## Ideas for the future
- Refactor comparisons into own class
- Support check against version constraints (`^`, `~` etc)
- Support wildcards (e.g. `1.4.*`)
- Support more comparisons (e.g. _less than_)
