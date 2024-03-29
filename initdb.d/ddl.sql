create table tracks(
    `external_url` VARCHAR(255) primary key,
    `artists` text,
    `popularity` int,
    `duration_ms` int,
    `isrc` VARCHAR(255),
    `created_at` datetime,
    `updated_at` datetime
    );

create table users(
    `id` VARCHAR(255) primary key,
    `used_count` int default 0,
    `created_at` datetime,
    `updated_at` datetime
    );
