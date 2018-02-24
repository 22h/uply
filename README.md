# uply
a simple service for checking online times and ssl certificates

## available checks

- status code
- google page speed
- ssl certificate expire 

## cronjob
```
* * * * * php /path/to/project/bin/console monitor:loop:start
```