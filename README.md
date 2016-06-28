# GM WP Hasher

> Standalone password hasher and hash-checker to mimic WordPress hasher.

## Usage

```php
$hasher = new Gm\WpHasher\WpHasher();
$hasher->check('my_plain_text', $password); // return bool
```