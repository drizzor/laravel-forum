<template>
  <div
    class="alert alert-flash alert-dismissible fade show"
    :class="'alert-'+level"
    role="alert"
    v-show="show"
  >
    {{ body }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</template>

<script>
window.events = new Vue();

window.flash = function(message, level = "success") {
  window.events.$emit("flash", { message, level });
};

export default {
  props: ["message"],

  data() {
    return { body: this.message, level: "success", show: false };
  },

  created() {
    if (this.message) {
      this.flash();
    }

    window.events.$on("flash", data => this.flash(data));
  },

  methods: {
    // Afficher le msg flash
    flash(data) {
      if (data) {
        this.body = data.message;
        this.level = data.level;
      }
      this.show = true;
      this.hide();
    },

    // Masquer le msg flash après 3000ms
    hide() {
      setTimeout(() => {
        this.show = false;
      }, 3000);
    }
  }
};
</script>

<style>
.alert-flash {
  position: fixed;
  right: 25px;
  bottom: 25px;
}
</style>
