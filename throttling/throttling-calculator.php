<?php

/**
 * Calculate how many seconds a request lasts to be recovered allowing to request again without getting throttling.
 *
 * @param float $requestsPerSecond
 * @param float $burst
 * @return float
 */
function calculateThrottlingPerSecond($requestsPerSecond, $burst) {
  $result = (1 / $requestsPerSecond) / $burst;
  return $result + 0.1;
}

/**
 * Calculate the total time (in seconds) that should be waited to recover from all the bursts.
 *
 * @param float $requestsPerSecond
 * @return float
 */
function calculateThrottling($requestsPerSecond) {
  $result = 1 / $requestsPerSecond;
  return $result + 0.1;
}


echo calculateThrottlingPerSecond(0.0055, 15) . PHP_EOL; // 12.221212121212123
echo calculateThrottling(0.0055); // 181.91818181818184