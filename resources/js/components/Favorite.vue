<template>
  <button type="submit" :class="classes" class="btn-sm" @click="toggle">
    <i class="fas fa-heart pr-2"></i>
    <span v-text="count"></span>
  </button>
</template>

<script>
export default {
  props: ["reply"],

  data() {
    return {
      count: this.reply.favoritesCount,
      active: this.reply.isFavorited
    };
  },

  computed: {
    classes() {
      return ["btn", this.active ? "btn-success" : "btn-secondary"];
    },

    endpoint() {
      return "/replies/" + this.reply.id + "/favorites";
    }
  },

  methods: {
    toggle() {
      return this.active ? this.destroy() : this.create();
    },

    create() {
      axios.post(this.endpoint);

      this.active = true;
      this.count++;
    },

    destroy() {
      axios.delete(this.endpoint);

      this.active = false;
      this.count--;
    }
  }
};
</script>