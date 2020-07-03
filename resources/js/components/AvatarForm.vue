<template>
  <div>
    <div class="d-flex justify-content-start">
      <img :src="avatar" width="60" height="60" class="rounded-circle mr-2" />
      <h1 v-text="ucFirst(user.name)" class="display-4 text-muted align-items-center"></h1>
    </div>

    <hr />

    <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
      <image-upload name="avatar" @loaded="onLoad" class="mb-5"></image-upload>
    </form>
  </div>
</template>

<script>
import ImageUpload from "./ImageUpload.vue";

export default {
  props: ["user"],

  components: { ImageUpload },

  data() {
    return {
      avatar: this.user.avatar_path
    };
  },

  computed: {
    canUpdate() {
      return this.authorize(user => user.id === this.user.id);
    }
  },

  methods: {
    onLoad(avatar) {
      this.avatar = avatar.src;

      // Persist to the server
      this.persist(avatar.file);
    },

    persist(avatar) {
      // Comme il s'agit d'un fichier on ne peut pas faire une simple requete axios
      let data = new FormData();

      data.append("avatar", avatar);

      axios
        .post(`/api/users/${this.user.name}/avatar`, data)
        .then(() => flash("Avatar chargé !"));
    },

    // Forcer première lettre en upperCase
    ucFirst(value) {
      return value.charAt(0).toUpperCase() + value.slice(1);
    }
  }
};
</script>  