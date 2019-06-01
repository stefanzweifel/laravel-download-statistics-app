<?php

if (! function_exists('format_download_numbers')) {
    function format_download_numbers(int $downloads)
    {
        return number_format($downloads, 0, '.', '\'');
    }
}
