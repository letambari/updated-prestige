#!/usr/bin/env bash
NAMEDATE=`date +%F_%H-%M`_`whoami`
NAMEDATE2=`date `
mkdir ~/public_html/cron/backup/files/$NAMEDATE -m 0755
tar czf ~/public_html/cron/backup/files/files.tar.gz ~/public_html
echo "This is the Files backup for feltonassets.com on $NAMEDATE2" | mailx  -a ~/public_html/cron/backup/files/$NAMEDATE/files.tar.gz -s "feltonassets.com Files attached" -- techbackup1290@gmail.com
chmod -R 0644 ~/public_html/cron/backup/files/$NAMEDATE/*
exit 0