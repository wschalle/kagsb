<style>
.btn-kagsso {
	font-family: "uni 05_53", fixedsys, sans-serif;
  font-size: 20px;
  line-height: 30px;
  background-color: #1A6A1A;
  background-image: -webkit-linear-gradient(top, #51A351, #1A6A1A);
  background-image: -o-linear-gradient(top, #51A351, #1A6A1A);
  background-image: linear-gradient(to bottom, #51A351, #1A6A1A);
  height: 36px;
}
.btn-kagsso:hover,
.btn-kagsso:active,
.btn-kagsso.active,
.btn-kagsso.disabled,
.btn-kagsso[disabled] {
  color: #ffffff;
  background-color: #1A6A1A;
  *background-color: #1A6A1A;
}
.btn-kagsso:active,
.btn-kagsso.active {
  background-color: #1A6A1A \9;
}

  @font-face {
    font-family: "uni 05_53";

    /* IE is one mad cunt that doesn't like .ttf files.  */
    src: url(assets/fonts/uni05_53.eot);
  }
  @font-face {
    font-family: "uni 05_53";
    src: url(assets/fonts/uni05_53.ttf);
  }
</style>
<script>
  $(function() {
    $('#whatsthis').popover();
  });
</script>

<div class="container">
  <center>
    <h1>Log In</h1>
    <a href="https://sso.kag2d.com?sso_provider=<?php echo getConfig('ssoProvider')?>" class="btn btn-success btn-kagsso">Sign on via KAG2d.com</a>
    <br />
    <a id="whatsthis" data-placement="bottom" data-original-title="What's this?" data-content="This page will send you to kag2d.com to sign in. When this happens, 
        we never deal with verifying your password, so we never have to see it.
        When you're done, the KAG site tells us if you're legit or not, and then
        you get to use the service. Piece of cake!">What's this?</a>

  </center>
</div>
  