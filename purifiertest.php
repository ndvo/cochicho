<?php 
require_once 'htmlpurifier/library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);


$testcases = [
  'Nelson',
  'Nelson do Vale Oliveira',
  "important subject;",
  "I'd better go", 
  "<p>Can I write HTML in here?</p>",
  "<ul><li>Shouldn't be able to do this</li></ul>",
  "Now, lets see: <script src=\"http://mysite/test.js\"></script>",
  "or perhaps: <script>function test(){alert('test')}();}"
];
foreach ($testcases as $t){
  echo "Test case: $t \n";
  echo "\tresult: \t".$purifier->purify($t). "\n\n";
}
?>
