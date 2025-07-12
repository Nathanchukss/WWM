<?php
function get5050Options($choices, $correctIndex) {
  $wrongIndexes = array_diff(array_keys($choices), [$correctIndex]);
  shuffle($wrongIndexes);
  $twoOptions = [$correctIndex, $wrongIndexes[0]];
  sort($twoOptions);
  return $twoOptions;
}
?>
