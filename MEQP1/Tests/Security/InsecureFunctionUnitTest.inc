<?php

assert('2 < 1');

$func = create_function('$a,$b', 'return "ln($a) + ln($b) = " . log($a * $b);');

exec('whoami');

passthru('test.zip', $err);

$handle = popen('/bin/ls', 'r');

proc_open('php', [0 => ['pipe', 'r']], $pipes, '/tmp', ['some_option' => 'test']);

shell_exec('ls -l');

system('ls', $retval);

md5($text);

unserialize('');

serialize([]);

pcntl_exec('path/goes/here');

srand();

mt_srand();

mt_rand();
