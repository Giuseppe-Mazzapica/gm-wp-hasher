# GM WP Hasher

> Standalone password hasher and hash-checker to mimic WordPress hasher.

## Why?

Well, if you are getting rid of WordPress, you may want to need a way to check WordPress users
passwords **without** WordPress.

## Usage to check passwords

```php
$hasher = new Gm\WpHasher\WpHasher();
$hasher->check('plain_text_password', $hashed_password); // return bool
```

## Usage to hash passwords

```php
$hasher = new Gm\WpHasher\WpHasher();
$hasher->hash('plain_text_password');
```

**Note**: Considering that nowadays there're better ways to encode passwords, only use this lib to
encode passwords if you want a WordPress compatible hash.


## More Info

[Portable PHP password hashing framework](http://www.openwall.com/phpass/) on which WordPress code
is based on, had possibility to use different hashing algorithms, this library allow that as well.

Available algorithms are:

- Blowfish
- Extended DES-based

To encode using these algorithms you need an implementation of `Gm\WpHasher\Hasher\HasherInterface` 
and pass it to `hash()` as 2nd argument:

```php
$hasher = new Gm\WpHasher\WpHasher();
$hasher->hash('plain_text_password', new Gm\WpHasher\Hasher\BlowfishHasher());
```

Example above uses Blowfish, to use Extended DES-based the class to use is `Gm\WpHasher\Hasher\ExtendedDesHasher`.

**Note**: Considering that WordPress does not actually use these algorithms and considering that
nowadays there're better ways to encode passwords, I can't actually think at a good reason to use this
feature.
 
 
## License
 
**Creative Commons Zero v1.0 Universal**.

Code used by WordPress is a slightly modified version of
[Portable PHP password hashing framework](http://www.openwall.com/phpass/)
originally developed by Solar Designer (solar at openwall.com) and in public domain.

Being this just a refactoring of WordPress code, I'm using public domain as well.