<template>
  <button :class="classes" @click="subscribe" v-text="btnTxt"></button>
</template>

<script>
export default {
  props: ["active"],

  data() {
    return {
      btnTxt: this.active ? "ABONNÉ" : "S'ABONNER"
    };
  },

  computed: {
    classes() {
      return [
        "btn btn-block",
        this.active ? "btn-primary" : "btn-outline-primary"
      ];
    }
  },

  methods: {
    subscribe() {
      let requestType = this.active ? "delete" : "post";

      axios[requestType](location.pathname + "/subscriptions");

      this.btnTxt = this.active ? "S'ABONNER" : "ABONNÉ";

      this.active = !this.active;
    }
  }
};
</script>