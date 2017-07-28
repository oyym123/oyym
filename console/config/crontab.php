;[Example]
;run_time = 秒 分 时 日 月 周
;cd_dir = 执行common的目录（建议绝对路径）
;common = 执行的命令
;log_dir = 日志输出文件（建议绝对路径）

;[test1]
;run_time = */10 * * * * *
;cd_dir = /home/php-crontab/
;common = php demo2.php
;log_dir = /home/php-crontab/log/test1.log

;[test2]
;run_time = 0/4 * * * * *
;cd_dir = C:\xampp\htdocs\zcdb\console\
;common = C:\xampp\htdocs\zcdb\yii product/crontab
;log_dir = D:\log.txt


[progress]
run_time = 1 * * * * *
cd_dir = C:\xampp\htdocs\zcdb\console\
common = C:\xampp\htdocs\zcdb\yii product/progress 2
log_dir = D:\log.txt

[product_progress]
run_time = 2 * * * * *
cd_dir = C:\xampp\htdocs\zcdb\console\
common = ..\yii crontab/crontab 2
log_dir = D:\log.txt

[start_product_progress]
run_time = */1 * * * * *
cd_dir = C:\xampp\htdocs\zcdb\console\
common = ..\yii crontab/start-crontab 2
log_dir = D:\log.txt

[product_count_down]
run_time = */10 * * * * *
cd_dir = C:\xampp\htdocs\zcdb\console\
common = ..\yii crontab/crontab 1
log_dir = D:\log.txt

[start_product_count_down]
run_time = */1 * * * * *
cd_dir = C:\xampp\htdocs\zcdb\console\
common = ..\yii crontab/start-crontab 1
log_dir = D:\log.txt