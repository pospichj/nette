<?php

/**
 * Test: Nette\Caching\Storages\FileStorage & load or save.
 *
 * @author     David Grudl
 * @package    Nette\Caching
 * @phpversion 5.3
 */

use Nette\Caching\Cache,
	Nette\Caching\Storages\FileStorage;



require __DIR__ . '/../bootstrap.php';



// key and data with special chars
$key = '../' . implode('', range("\x00", "\x1F"));
$value = range("\x00", "\xFF");

$cache = new Cache(new FileStorage(TEMP_DIR));

Assert::false( isset($cache[$key]), 'Is cached?' );


// Writing cache using Closure...
$res = $cache->load($key, function(& $dp) use ($value) {
	$dp = array(
		Cache::EXPIRATION => time() + 2,
	);
	return $value;
});

Assert::true( $res === $value, 'Is result ok?' );

Assert::true( $cache->load($key) === $value, 'Is cache ok?' );

// Sleeping 3 seconds
sleep(3);
clearstatcache();
Assert::false( isset($cache[$key]), 'Is cached?' );
