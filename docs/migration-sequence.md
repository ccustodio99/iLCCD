# 🗄️ Migration Sequence

This project includes a pair of migrations around the `audit_trails.comment` column.
Run them in chronological order so the column is first created then removed.
If you ran migrations manually or out of sequence, the new safeguard migration
`2025_06_21_000000_drop_audit_trails_comment_if_exists.php` will ensure the
column is dropped when present.
