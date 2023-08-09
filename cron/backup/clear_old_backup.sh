#!/usr/bin/env bash
cd ~/public_html/cron/backup/files/; find . -type d -mtime +2 -exec rm -rf {} \; 2>&1