<?php
    // Update the path below to your autoload.php,
    // see https://getcomposer.org/doc/01-basic-usage.md
    require_once '/path/to/vendor/autoload.php';
    use Twilio\Rest\Client;

    $sid    = "AC21933f4be144ac016f0c826fb6539f1f";
    $token  = "7c42d608984ee76419d6a551067a2490";
    $twilio = new Client($sid, $token);

    $message = $twilio->messages
      ->create("+447379393184", // to
        array(
          "from" => "+447458195113",
          "body" => hey
        )
      );

print($message->sid);