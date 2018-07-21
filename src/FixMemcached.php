<?php
namespace tasofen;

class FixMemcached extends \Memcached 
{
    function getAllKeys() {
        $keys = parent::getAllKeys();

        if ($keys !== false) {
            return $keys;
        }


        $keys = [];
        $servers = $this->getServerList();

        foreach($servers as $server) {
            $s = fsockopen($server['host'], $server['port']);
            if (!$s) {
                continue;
            }
            
            fwrite($s, 'stats items'.PHP_EOL);
            $items = [];
            $pattern = '#STAT\s+items:([0-9]+):number\s+([0-9]+)#';

            while (!feof($s)) {
                $line = fgets($s, 1024);
                preg_match($pattern, $line, $res);

                if ($res) {
                    $items[$res[1]] = $res[2];
                }

                if (trim($line) == 'END') {
                    break;
                }
            }

            
            $pattern = '#^ITEM\s+(\S+).*#';
            
            foreach($items as $key => $size) {
                fwrite($s, 'stats cachedump '.$key.' '.$size.PHP_EOL);

                while (!feof($s)) {
                    $line = fgets($s, 1024);

                    if (trim($line) == 'END') {
                        break;
                    }

                    preg_match($pattern, $line, $res);

                    if ($res) {
                        $keys[ $res[1] ] = true;
                    }
                }
            }

            fclose($s);
        }

        return array_keys($keys);
    }
}
