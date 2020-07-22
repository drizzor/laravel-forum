<template>
  <div class="card mb-3">
    <div class="card-header" :id="'reply-' + id" :class="isBest ? 'bg-primary text-white' : ''">
      <div class="d-flex justify-content-start align-items-center">
        <div class="flex-grow-1">
          <a
            :class="isBest ? 'text-warning' : ''"
            :href="'/profiles/' + reply.owner.name"
            v-text="reply.owner.name"
          ></a>
          Posté
          <span v-text="ago"></span>
        </div>

        <div v-if="signedIn">
          <favorite :reply="reply"></favorite>
        </div>
      </div>
    </div>

    <div class="card-body">
      <div v-if="editing">
        <form @submit="update">
          <div class="form-group">
            <wysiwyg v-model="body"></wysiwyg>
            <!-- <textarea class="form-control" v-model="body" required></textarea> -->
          </div>

          <button class="btn btn-sm btn-outline-success">
            <i class="fas fa-check"></i> Valider
          </button>

          <button class="btn btn-sm btn-outline-secondary" @click="cancel" type="button">
            <i class="fas fa-undo"></i> Annuler
          </button>
        </form>
      </div>

      <!-- v-html au lieux de v-text afin de correctement interpreter les balises HTML -->
      <div v-else v-html="body"></div>
    </div>

    <div
      class="card-footer d-flex"
      v-if="authorize('owns', reply) || authorize('owns', reply.thread)"
    >
      <div v-if="authorize('owns', reply)">
        <button class="btn btn-dark btn-sm mr-2" @click="editing = true" v-if="!editing">
          <i class="fas fa-pencil-alt pr-1"></i> Editer
        </button>

        <button class="btn btn-danger btn-sm" @click="destroy" v-if="!editing">
          <i class="fas fa-trash pr-1"></i> Supprimer
        </button>
      </div>

      <button
        class="ml-auto btn btn-sm btn-warning"
        title="Meilleur Réponse?"
        @click="markBestReply"
        v-if="authorize('owns', reply.thread)"
      >
        <i class="far fa-star"></i>
      </button>
    </div>
  </div>
</template>

<script>
import Favorite from "./Favorite.vue";
import moment from "moment"; // momentjs est un petit module permettant de travailler sur les dates (voir site)

export default {
  props: ["reply"],

  components: { Favorite },

  data() {
    return {
      editing: false,
      id: this.reply.id,
      body: this.reply.body,
      isBest: this.reply.isBest // Récupère la variable init dans le modèle Reply
    };
  },

  computed: {
    ago() {
      moment.locale("fr");
      var m = moment(this.reply.created_at);
      return m.fromNow();
      // return moment(this.reply.created_at).fromNow();
    }
    // signedIn() {
    //   return window.App.signedIn;
    // }
    // N'est plus exploité voir fichier authorizations dans JS
    // canUpdate() {
    //   return this.authorize(user => this.reply.user_id == user.id);
    //   // return this.reply.user_id == window.App.user.id;
    // }
  },

  created() {
    // Appel de notre event afin de procéder au toggle du marquage de la 'best reply'
    window.events.$on("best-reply-selected", id => {
      if (id === this.id) this.isBest = true;
      else this.isBest = false;
    });
  },

  methods: {
    update() {
      axios
        .put("/replies/" + this.id, {
          body: this.body
        })
        .catch(error => {
          flash(error.response.data.errors.body[0], "danger");
        });

      this.editing = false;

      flash("Modification effectuée!");
    },

    cancel() {
      this.body = this.reply.body;
      this.editing = false;
    },

    destroy() {
      axios.delete("/replies/" + this.reply.id);

      // Crée un event vers Replies.vue parent de Reply
      // Cela va signifier à replies qu'une réponse à été supprimée et qu'il faut mettre à jour la liste
      this.$emit("deleted", this.reply.id);

      // $(this.$el).fadeOut(800);

      flash("Réponse supprimée!");
    },

    markBestReply() {
      // this.isBest = true;

      axios.post("/replies/" + this.reply.id + "/best");

      // Préparation de l'evenement qui servira à faire le toggle sur le marquage de la meilleur réponse - Si une autre réponse avait déjà été mise en surbrillance elle reprendra sa forme normal si on sélectionne une autre réponse comme étant meilleur
      window.events.$emit("best-reply-selected", this.reply.id);
    }
  }
};
</script>
