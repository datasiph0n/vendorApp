<?php
//define('IN_MYBB', 1);
//require "../global.php";

if(isset($_POST['address'])) {
  $uSale = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", 0, 12));
  $buyer_uid = get_buyer_userid($_POST['buyer']);
  send_priv($_POST['buyer'], $_POST['amount'], $_POST['description'], $_POST['buyerinvite'], $_POST['paymentinvite']);
}


function get_buyer_userid($username) {
  $user = get_user($username); // needs sanitizing
  return $user['uid'];
}

function send_priv($buyer, $amount, $description, $buyerinvite, $paymentinvite) {
    global $db, $mybb;
    require_once MYBB_ROOT."inc/datahandlers/pm.php";


    $buyer_uid = get_buyer_userid($buyer); // needs sanitizing
    $pmhandler = new PMDataHandler();
    $message = "Hello $buyer,</br></br> $mybb->user['username'] has requested that you pay: $amount for product:</br> $description </br> Please find all the needed information below: </br></br> Buyer Invite: $buyerinvite </br> Payment Invite: $paymentinvite </br>";
    $pm = array(
        "subject" => "New Escrow Request",
        "message" => $message,
        "fromid" => $mybb->user['uid'], // could map this as "Escrow" forum account?
        "toid" => array($buyer_uid)//array($user['uid'])
    );

    $pm['options'] = array(
        "signature" => 0,
        "disablesmilies" => 0,
        "savecopy" => 0,
        "readreceipt" => 0
    );

    $pmhandler->set_data($pm);
    // Now let the pm handler do all the hard work.
    if(!$pmhandler->validate_pm())
    {
        $pm_errors = $pmhandler->get_friendly_errors();
        return $pm_errors;
    } else {
        $pminfo = $pmhandler->insert_pm(); 
        return $pminfo;
    }
}

?>

<html lang="en">
  <head>
    <!--
    Donation Address: 1MnKnd2t7Tfpy1Cs8bm5DraC9HzrHmHoso
    GitHub Repository: https://github.com/mannkind/bitescrow.org
    -->

    <title>Bitcoin Escrow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.css" rel="stylesheet"></link>
    <link href="css/bootstrap-responsive.css" rel="stylesheet"></link>
    <link href="css/bitescrow.css" rel="stylesheet"></link>
    <script src="js/array.map.js" type="text/javascript"></script>
    <script src="js/crypto.js" type="text/javascript"></script>
    <script src="js/crypto.hmac.js" type="text/javascript"></script>
    <script src="js/crypto.sha256.js" type="text/javascript"></script>
    <script src="js/crypto.pbkdf2.js" type="text/javascript"></script>
    <script src="js/crypto.aes.js" type="text/javascript"></script>
    <script src="js/crypto.blockmodes.js" type="text/javascript"></script>
    <script src="js/crypto.ripemd160.js" type="text/javascript"></script>
    <script src="js/securerandom.js" type="text/javascript"></script>
    <script src="js/ellipticcurve.js" type="text/javascript"></script>
    <script src="js/biginteger.js" type="text/javascript"></script>
    <script src="js/bitcoinjs-lib.base58.js" type="text/javascript"></script>
    <script src="js/bitcoinjs-lib.address.js" type="text/javascript"></script>
    <script src="js/bitcoinjs-lib.ecdsa.js" type="text/javascript"></script>
    <script src="js/bitcoinjs-lib.eckey.js" type="text/javascript"></script>
    <script src="js/bitcoinjs-lib.util.js" type="text/javascript"></script>
    <script src="js/bitcoin.escrow.js" type="text/javascript"></script>
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/bootstrap.js" type="text/javascript"></script>
    <script src="js/bitescrow.js" type="text/javascript"></script>
    <script>
    $(document).ready(function () {
      $("#generate-all").trigger('click');
      $('#genForm').submit(function(){ 
        $('#response').html("<b> Loading Response... </b>");
        $.ajax({
          type: 'POST',
          url: 'index.php',
          data: $(this).serializeArray(),
          contentType: "application/x-www-form-urlencoded",
          success: function (data) {
            alert('Sale Created.')
          },
          error: function (xhr, ajaxOptions, thrownError) {}
        })
        .done(function(data) {
          $('#response').html("Completed");
        })
        .fail(function() {
          alert("Posting Failed.");
        });
        alert(data);
        return false;
      });
    });
    </script>
  </head>
  <body alink="#00ff00" link="#00c000" text="#008000" vlink="#00c000">
      <div class="container">
        <center>
          <b><a href="index.php">[Home]</a>-
          <a href="">[Forum]</a>-
          <a href="">[Escrow]</a></b>
          <table width="668" border="0" cellpadding="3" cellspacing="3" class="main">
            <tr>
              <td><img src='http://siph0n.in/index_files/banner4.png' alt='siph0n'></td>
            </tr>
            <tr>
              <td>
                <form name="genForm" id="genForm" role="form" class="form-horizontal" method="post" action="#">
                  <label for="buyer">Buyer Forum Username:</label>
                  <input class="form-control" type="text" id="buyer" name="buyer" placeholder="Buyer's username." class="span8"/>
                  <label for="amount">Amount required: (btc's)</label>
                  <input class="form-control" type="number" step='any' id="amount" name="amount" placeholder="Amount required." class="span8"/>
                  <label for="description">Sales Description:</label>
                  <input class="form-control" type="text" id="description" name="description" placeholder="Brief sales description." class="span8"/></br></br>

                  <input id="submit" name="submit" value="Send" class="btn btn-primary" type="submit"> </br> </br>
                  <button id="generate-all" type="hidden" class="btn btn-primary" data-loading-text=" ... Generating ... ">Generate Escrow Invitations</button></br>
                  <!--<label for="address">Address:</label>--><input class="form-control" type="hidden" id="address" name="address" placeholder=" ... not yet generated ... " class="span8" />
                  <!--<label for="sellerInvite">Seller Invite:</label>--><input class="form-control" type="hidden" id="sellerInvite" name="sellerInvite" placeholder=" ... not yet generated ... " class="span8" />
                  <!--<label for="buyerInvite">Buyer Invite:</label>--><input class="form-control" type="hidden" id="buyerInvite" name="buyerInvite" placeholder=" ... not yet generated ... " class="span8" />
                  <!--<label for="paymentInvite">Payment Invite:</label>--><input class="form-control" type="hidden" id="paymentInvite" name="paymentInvite" placeholder=" ... not yet generated ... " class="span8" />
                </form>
              </td>
              <td></td>
            </tr>
          </table>
          <div id='response'></div>
        </center>
      </div>
  </body>
</html>
