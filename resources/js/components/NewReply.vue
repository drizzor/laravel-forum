<template>
  <div>
    <div v-if="signedIn">
      <div class="form-group">
        <label for="body">Répondre</label>
        <textarea
          name="body"
          id="body"
          cols="30"
          rows="5"
          placeholder="Dire quelque chose..."
          class="form-control"
          required
          v-model="body"
        ></textarea>
        <!-- @error ('body') -->
        <!-- <div class="invalid-feedback">{{ $message }}</div> -->
        <!-- @enderror -->
      </div>

      <button type="submit" class="btn btn-primary" @click="addReply">Poster</button>
    </div>

    <p class="text-center" v-else>
      <a href="/login">Connectez-vous</a> pour participer à la discussion.
    </p>
  </div>
</template>

<script>
import "jquery.caret"; // Nécessaire au fonctionnement de at.js
import "at.js"; // Module installé avec npm permet autocompletion nom user

export default {
  data() {
    return {
      body: ""
    };
  },

  mounted() {
    // Utilisation de l'outil at.js installé via npm
    $("#body").atwho({
      at: "@",
      delay: 750, // Evite de lancer la requete DB trop rapidement (surcharge)
      callbacks: {
        remoteFilter: function(query, callback) {
          $.getJSON("/api/users", { name: query }, function(usernames) {
            callback(usernames);
          });
        }
      }
    });
  },

  methods: {
    addReply() {
      axios
        .post(location.pathname + "/replies", { body: this.body })
        .catch(error => {
          flash(error.response.data.errors.body[0], "danger");
        })
        .then(({ data }) => {
          this.body = "";

          flash("Votre réponse a été envoyée.");

          this.$emit("created", data);
        });
    }
  }
};
</script>
