<?php

Route::get('/', 'HomepageController')->name('home');

Route::get('downloads/by-version/{version}/', 'DownloadsByVersionController')->name('downloads.byVersion');
Route::get('downloads/by-month/{date}/', 'DownloadsByMonthController')->name('downloads.byMonth');
