# Simple PHP Encryption
Simple PHP file that encrypts and decrypts data that could be sent to other places based on a Key.

This file provides a easy way to do the following:
- Generate and verify CSRF tokens
- Encrypt and decrypt sensitive data using AES-256-GCM

## Token Usage

### Include in the top of PHP files

```php
require_once 'path/security.php';
```

### Verify Token on Form Submissions

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'])) {
        die("Invalid CSRF token.");
    }

    // Proceed with processing data
}
```

### Add token into HTML Forms
```html
<form method="POST" action="submit.php">
  <input type="hidden" name="csrf_token" value="<?php echo getCsrfToken(); ?>">
  <!-- Other form fields -->
  <input type="submit" value="Submit">
</form>
```

## Encryption/Decryption

### Generating & Securing Key

To keep your encryption keys safe, store them **outside your web root**, such as in `/etc/`.

1. In your terminal, run:
```bash
openssl rand -out /etc/myapp.key 32
```

2. Restrict file access so only your web can read it:
```bash
chown www-data:www-data /etc/myapp.key
chmod 400 /etc/myapp.key
```

### Encrypt

```php
$key = loadEncryptionKey();

$data = 'Sensitive data to encrypt';

$encrypted = secureEncrypt($data, $key);
echo "Encrypted: $encrypted";
```

### Decrypt
```php
$key = loadEncryptionKey();

$encryptedData = 'ce9GgwBi1JWgVns5+0QenUUxonB8618vLusHodz2HsUzVowkhNJvj4FVIAm1gzmD';

$decrypted = secureDecrypt($encryptedData, $key);
echo "Decrypted: $decrypted";
```

