#!/usr/bin/env bash
NAMEDATE=`date +%F_%H-%M`_`whoami`
NAMEDATE2=`date `
mkdir ~/public_html/cron/backup/files/$NAMEDATE -m 0755
mysqldump -u feltrahq_boss -p"felton21." feltrahq_data | gzip > ~/public_html/cron/backup/files/$NAMEDATE/db.sql.gz
echo "This is the database backup for feltonassets.com on $NAMEDATE2" | mailx  -a ~/public_html/cron/backup/files/$NAMEDATE/db.sql.gz -s "feltonassets.com Database attached" -- techbackup1290@gmail.com
chmod -R 0644 ~/public_html/cron/backup/files/$NAMEDATE/*
exit 0