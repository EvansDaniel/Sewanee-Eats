# SewaneeEats

Make sure to open up `crontab -e` in the terminal and add this line
`* * * * * php /path/to/base/project/dir/artisan shedule:run >> /dev/null 2>&1`