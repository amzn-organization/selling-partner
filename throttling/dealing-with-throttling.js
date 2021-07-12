/**
 *
 * @param {Number} requestsPerSecond The number of requests recovered by second
 * @param {Number} burst The burst of the requested endpoint
 * @returns {Number} The interval to make another request after the burst got reached
 */
function calculateThrottlingPerSecond(requestsPerSecond, burst) {
  const result = 1 / requestsPerSecond / burst;
  return result + 0.1;
}

/**
 *
 * @param {Number} requestsPerSecond The number of requests recovered by second
 * @returns {Number} The interval to make all the requests again after the burst got reached
 */
function calculateThrottling(requestsPerSecond) {
  const result = 1 / requestsPerSecond;
  return result + 0.1;
}

function convertToMs(number) {
  return Math.trunc(number * 1000);
}

// console.log(calculateThrottlingPerSecond(0.0055, 15)); // 12.221212121212123
// console.log(calculateThrottling(0.0055)); // 181.91818181818184

// Defining the requests available
let requestsAvailable = 15;
let burst = 15;
let timeToRecoverRequest = convertToMs(
  calculateThrottlingPerSecond(0.0055, burst)
);
console.log("timeToRecoverRequest", timeToRecoverRequest);

function createFeedDocument(/* params */) {
  requestsAvailable--;
  // Make a request here
}

setInterval(() => {
  if (requestsAvailable < burst) {
    requestsAvailable++;
    console.log("requestsAvailable", requestsAvailable);
  }
}, timeToRecoverRequest);
