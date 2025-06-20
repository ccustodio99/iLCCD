# Licensing Workflow

The system requires a valid license record to operate. Licenses are created using the `license:generate` Artisan command which outputs a signed key.  The key encodes the license ID and expiry timestamp and is protected using an HMAC signature.

1. **Generate a license**
   ```bash
   php artisan license:generate --days=30
   ```
   The command stores the license in the database and prints the encoded string which can be used for activation.
2. **Activate or renew**
   Submit the encoded string through the activation or renewal form.  The application verifies the signature and expiry before enabling the license.
3. **Runtime checks**
   Every request passes through the `CheckLicense` middleware.  If the current license is missing or expired, access is denied.
4. **Scheduled validation**
   A daily task in `app/Console/Kernel.php` disables the system once the license expires.

For commercial deployments, ensure a valid license is generated and kept up to date.
