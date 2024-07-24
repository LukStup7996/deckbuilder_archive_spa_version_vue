<script>
export default {
  name: "cover-page",
};
</script>

<template>
  <div class="container fluid">
    <div class="col sm" align-self="center">
      <h4>Want to build a new deck or browse existing ones?</h4>
    </div>
    <div class="col sm">
      <img
        class="card-img-top"
        src="../assets/img/senseis-divining-top-2x2-background-2560x1600.jpg"
        alt="card image cap"
        self-align="left"
      />
      <div class="row form-control" align-self="left">
        <h5>Check on all the cards of your favourite Trading Card Game!</h5>
        <p>
          Starting with THE Magic: the Gathering! Expect to find our archives
          filled with cards and decklists for your perusal!
        </p>
        <RouterLink to="/card-archive">Check our Databases!</RouterLink>
      </div>
    </div>
    <div class="col sm">
      <img
        class="card-img-top"
        src="../assets/img/mana-vault-2x2-background-1920x1080.jpg"
        alt="card image cap"
        self-align="left"
      />
      <div class="row form-control" align-self="right">
        <h5>Want to build your own decks?</h5>
        <p>
          Just open the sandbox and get started! You can allways change your
          format and deck name on the fly!
        </p>
        <RouterLink to="/about">Learn more about us!</RouterLink>
      </div>
    </div>
    <div class="col sm">
      <img
        class="card-img-top"
        src="../assets/img/mtg Polluted Delta Wallpaper.jpg"
        alt="card image cap"
        self-align="left"
      />
      <div class="row form-control" align-self="left">
        <h5>Not part of the Archivers yet?</h5>
        <p>Join us today!</p>
        <RouterLink to="/">Stay up to date!</RouterLink>
      </div>
    </div>
  </div>
</template>
