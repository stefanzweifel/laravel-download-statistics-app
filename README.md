# Laravel Download Statistics

> Aggregated download statistics for [Laravel](https://laravel.com)


## How are numbers calculated

All download numbers are based on the download statistics for Laravel on [packagist.org](https://packagist.org/packages/laravel/framework). [A Job](https://github.com/stefanzweifel/laravel-download-statistics-app/blob/master/app/Jobs/FetchDownloadsForVersionJob.php) parses the download statistics and stores them in a local database.

The data is then either grouped by Minor Version or Month.

You can find the Controllers [here](https://github.com/stefanzweifel/laravel-download-statistics-app/tree/master/app/Http/Controllers). The corresponding MySQL queries to group the data is available [here](https://github.com/stefanzweifel/laravel-download-statistics-app/blob/master/app/DownloadsPerMonth.php#L30-L67).

## Local development

> TBD

## Updating download numbers

Download statistic numbers can easily be updated by running:

```shell
php artisan app:fetch-downloads
```

**Important:** The command dispatches Jobs to download the actual numbers. For best performance, setup a local queue environment with Redis, change `QUEUE_CONNECTION` to `redis` and run `php artisan horizon`. Laravel Horizon will take care of the queues.


To get download statistics for a specific month run 

```shell
php artisan app:fetch-downloads 2019-01
```

## License

MIT
