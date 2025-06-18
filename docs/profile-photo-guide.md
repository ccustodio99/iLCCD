# 🖼️ Profile Photo Setup & Troubleshooting

This guide explains how uploaded profile pictures are stored and how to make them display correctly in the application.

## Storage Location

- Profile photos are saved on the **public** disk under `storage/app/public/profile_photos`.
- The web server expects a symbolic link from `public/storage` to this directory. Laravel creates it with:

```bash
php artisan storage:link
```

If profile pictures upload successfully but do not appear, verify that this symlink exists.

## Correct URL

`Storage::disk('public')->url()` prefixes the value of `APP_URL` from your `.env` file. When using `php artisan serve`, set:

```env
APP_URL=http://localhost:8000
```

Then clear the cached configuration so Laravel uses the new value:

```bash
php artisan config:clear
```

After ensuring the symlink and URL are correct, reload the page. Images will load from `/storage/profile_photos/...`.
